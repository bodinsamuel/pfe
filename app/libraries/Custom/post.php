<?php namespace Custom;

class Post
{
    const POST_TYPE_SELL = 1;
    const POST_TYPE_LOCATION = 2;

    public static $property_type = [
        1 => 'appartemment',
        2 => 'maison'
    ];

    /**
     * Full process for creating a Post
     * @param  array $inputs array of input
     * @return array
     */
    public static function create($inputs)
    {
        $return = ['errors' => []];

        $return['inputs'] = &$inputs;

        // Validate all fields before insert
        $validation = Post::validate_all($inputs);

        if ($validation['failed'] === TRUE)
        {
            $return['errors'] = $validation['errors'];
            return $return;
        }
        else
        {

            try {
                // Begin inserting everything
                \DB::beginTransaction();

                // address
                $id_address = Address::upsert($inputs['address']);
                if ($id_address === -1)
                    throw new \Exception("[POST CREATE] failed inserting address");

                $inputs['post']['id_address'] = $id_address;

                // post details
                $id_post_detail = Post\Details::insert($inputs['details']);
                if ($id_post_detail === -1)
                    throw new \Exception("[POST CREATE] failed inserting details");

                $inputs['post']['id_post_detail'] = $id_post_detail;

                // Gallery
                $id_gallery = Gallery::create();
                if ($id_gallery === -1)
                    throw new \Exception("[POST CREATE] failed inserting gallery");

                $inputs['post']['id_gallery'] = $id_gallery;

                // post validate
                $validation = Post::validate($inputs['post']);
                if ($validation->fails())
                    throw new \Exception("[POST CREATE] failed validating post");

                // post insert
                $id_post = Post::insert($inputs['post']);
                if ($id_post === -1)
                    throw new \Exception("[POST CREATE] failed inserting post");

                $inputs['id_post'] = $id_post;

                // Pricing
                $price = Post\Price::insert($id_post, $inputs['price']['value'], $inputs['price']['type']);
                if ($price === -1)
                    throw new \Exception("[POST CREATE] failed inserting post");


                if (isset($inputs['source']))
                {
                    $sourced = Post\Source::upsert($id_post, $inputs['source']['name'], $inputs['source']['id']);
                    if ($id_post === -1)
                        throw new \Exception("[POST CREATE] failed inserting source");

                    $inputs['sourced'] = TRUE;
                }

                // Everything went well, so good to go
                \DB::commit();

                if (isset($inputs['medias']))
                {
                    $batch = Media::upload($inputs['post']['id_gallery'], $inputs['medias']);
                    if ($batch['failed'] === TRUE)
                        throw new \Exception("[POST CREATE] failed uploading");

                    $inputs['upload'] = $batch['data'];
                }

            } catch (Exception $e) {
                \DB::rollback();

                if (\App::environment('dev'))
                    throw $e;

                $return['errors'][] = Lang::get('global.error.oops');
                return $return;
            }

            return $return;
        }
    }

    /**
     * Full process for validating a post
     * @param  array $inputs
     * @return array
     */
    public static function validate_all($inputs)
    {
        $return = ['failed' => FALSE, 'errors' => FALSE];

        // Validate address
        $val_address = Address::validate($inputs['address']);
        if ($val_address->fails())
            $return['failed'] = TRUE;

        // Validate details
        $val_details = Post\Details::validate($inputs['details']);
        if ($val_details->fails())
            $return['failed'] = TRUE;

        // Some validation failed
        if ($return['failed'] === TRUE)
        {
            $return['errors'] = array_merge_recursive($val_address->messages()->toArray(),
                                                      $val_details->messages()->toArray());
        }

        return $return;
    }

    /**
     * Insert new post
     * @param  array $inputs
     * @return uint
     */
    public static function insert($inputs)
    {
        $inputs = array_only($inputs, [
            'id_post_type', 'id_property_type', 'id_post_detail', 'id_gallery',
            'id_address', 'content'
        ]);
        $inputs['status'] = Acl::isAtLeast('root') ? Cnst::VALIDATED : Cnst::NEED_VALIDATION;
        $inputs['id_user'] = \User::getIdOrZero();

        // Query
        $query = 'INSERT INTO posts
                              (id_post_type, id_property_type, id_post_detail,
                               id_gallery, id_user, id_address, content,
                               date_created, date_updated, date_closed, status)
                       VALUES (:id_post_type, :id_property_type, :id_post_detail,
                               :id_gallery, :id_user, :id_address, :content,
                               NOW(), NOW(), "NULL", :status)';

        $stmt = \DB::statement($query, $inputs);
        if ($stmt === FALSE)
            return -1;

        $lid = \DB::getPdo()->lastInsertId();

        // Publish to Beanstalkd that this post need to be inserted in elastic
        if ($inputs['status'] == Cnst::VALIDATED)
        {
            $bean = Custom\Singleton::getBeanstalkd();
            $bean->sendEvents([
                'action' => 'PostElasticUpsert',
                'data' => [
                    'id_post' => $lid
                ]
            ]);
        }

        return $lid;
    }

    /**
     * Validate a posts array
     * @param  array $inputs
     * @return object
     */
    public static function validate($inputs)
    {
        return \Validator::make(
            $inputs, [
                'id_post_type' => 'required|integer',
                'id_property_type' => 'required|integer',
                'id_post_detail' => 'required|integer',
                'id_gallery' => 'required|integer',
                'id_address' => 'required|integer',
                'content'    => 'required'
            ]
        );
    }

    public static function select($ids_posts = NULL, $opts = [])
    {
        $where = [];

        if (!empty($ids_posts))
            $where[] = 'posts.id_post IN (' . implode(",", $ids_posts) . ')';

        if (isset($opts['status']))
            $where[] = 'posts.status = ' . $opts['status'];
        else
            $where[] = 'posts.status = ' . Cnst::VALIDATED;

        $where[] = 'posts.date_closed < NOW()';
        // Query
        $query = 'SELECT posts.id_post,
                         posts.id_post_type,
                         posts.id_property_type,
                         posts.id_post_detail,
                         posts.id_gallery,
                         posts.id_user,
                         posts.exclusivity,
                         posts.price,
                         posts.content,
                         posts.date_created,
                         posts.date_updated,
                         posts.date_closed,
                         posts.status,

                         posts_details.room,
                         posts_details.surface_living,

                         addresses.id_address,
                         addresses.id_user,
                         addresses.longitude,
                         addresses.latitude,

                         galleries.media_count AS has_photo,
                         galleries.id_cover,
                         media.hash AS cover_hash,
                         media.title AS cover_title,
                         media.extension AS cover_extension,

                         geo_cities.id_city AS city_id,
                         geo_cities.name AS city_name,
                         geo_cities.zipcode AS city_zipcode,

                         geo_countries.id_country AS country_id,
                         geo_countries.iso2 AS country_code,
                         geo_countries.name AS country_name,

                         geo_cities.admin1_id,
                         admin1.name AS admin1_name,

                         geo_cities.admin2_id,
                         admin2.name AS admin2_name,

                         geo_cities.admin3_id
                   FROM posts
                   JOIN posts_details
                        ON posts_details.id_post_detail = posts.id_post_detail
                   JOIN addresses
                        ON posts.id_address = addresses.id_address
                   JOIN geo_cities
                        ON addresses.id_city = geo_cities.id_city
                   JOIN geo_countries
                        ON geo_cities.id_country = geo_countries.id_country
                   JOIN galleries
                        ON galleries.id_gallery = posts.id_gallery

              LEFT JOIN media
                        ON media.id_media = galleries.id_cover

              LEFT JOIN geo_states AS admin1
                        ON admin1.id_state = geo_cities.admin1_id
              LEFT JOIN geo_provinces AS admin2
                        ON admin2.id_province = geo_cities.admin2_id

                  WHERE ' . implode(' AND ', $where) . '
               ORDER BY posts.date_created DESC';

        if (isset($opts['limit']))
            $query .= ' LIMIT ' . (int)$opts['limit'];

        if (isset($opts['offset']))
            $query .= ' OFFSET ' . (int)$opts['offset'];

        $select = \DB::select($query);

        $final = ['markers' => [], 'posts' => [], 'count' => 0];
        if (empty($select))
            return $final;

        $ids_galleries = [];
        $final['count'] = count($select);
        foreach ($select as $k => &$value)
        {
            $value->title = self::make_title($value->id_property_type, $value->surface_living);
            $final['posts'][$value->id_post] = $value;
            $ids_galleries[] = $value->id_gallery;
        }

        if (!isset($opts['galleries']) || (isset($opts['galleries']) && $opts['galleries'] === TRUE))
        {
            $galleries = Gallery::select($ids_galleries);
            foreach ($final['posts'] as $key => &$value)
            {
                if (isset($galleries[$value->id_gallery]))
                    $value->gallery = $galleries[$value->id_gallery];
                else
                    $value->gallery = ['count' => 0, 'media' => []];
            }
        }

        return $final;
    }

    public static function light($id_post)
    {
        $query = 'SELECT posts.id_post,
                         posts.id_post_type,
                         posts.id_property_type,
                         posts.id_post_detail,
                         posts.id_gallery,
                         posts.id_user,
                         posts.exclusivity,
                         posts.price,
                         posts.content,
                         posts.date_created,
                         posts.date_updated,
                         posts.date_closed,
                         posts.status
                    FROM posts
                   WHERE id_post = ' . (int)$id_post;

         return \DB::select($query);
    }

    public static function make_title($id_property_type, $surface_living, $room = NULL, $zipcode = NULL)
    {
        $title = ucfirst(self::$property_type[$id_property_type]);
        if ($surface_living > 0)
            $title .= ', ' . $surface_living . 'm²';

        return $title;
    }
}
