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
                $price = Post\Price::insert($id_post, $inputs['price']);
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

                if ($inputs['medias'])
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
            'id_address', 'surface_living', 'room', 'content'
        ]);
        $inputs['status'] = Acl::isAtLeast('root') ? Cnst::Validated : Cnst::NEED_VALIDATION;
        $inputs['id_user'] = \User::getIdOrZero();

        // Query
        $query = 'INSERT INTO posts
                              (id_post_type, id_property_type, id_post_detail,
                               id_gallery, id_user, id_address, surface_living,
                               room, content,
                               date_created, date_updated, date_closed, status)
                       VALUES (:id_post_type, :id_property_type, :id_post_detail,
                               :id_gallery, :id_user, :id_address, :surface_living,
                               :room, :content,
                               NOW(), NOW(), "NULL", :status)';

        $stmt = \DB::statement($query, $inputs);
        if ($stmt === FALSE)
            return -1;

        return \DB::getPdo()->lastInsertId();
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
                'surface_living' => 'required|integer',
                'room' => 'required|integer',
                'content'    => 'required'
            ]
        );
    }

    public function delete($id_post)
    {
        $return = [
            'post'    => FALSE,
            'gallery' => FALSE
        ];

        try {
            // Begin inserting everything
            \DB::beginTransaction();
            $query = 'UPDATE posts
                         SET status = ' . Cnst::DELETED . '
                       WHERE id_post = ' . (int)$id_post;

            $post = \DB::statement($query, $inputs);
            if ($post === FALSE)
                return -1;

            $return['post'] = $post;

            $return['gallery'] = Gallery::delete($id_gallery);

            $elastic = new Custom\Elastic\Post();
            $deletion = $elatic->delete($id_post);
            print_r($deletion);


        } catch (Exception $e) {
            \DB::rollback();

            if (\App::environment('dev'))
                throw $e;

            $return['errors'][] = Lang::get('global.error.oops');
        }

        return $return;
    }

    public static function select($ids_posts, $opts = [])
    {
        // Query
        $query = 'SELECT posts.id_post,
                         posts.id_post_type,
                         posts.id_property_type,
                         posts.id_post_detail,
                         posts.id_gallery,
                         posts.id_user,
                         posts.exclusivity,
                         posts.price,
                         posts.surface_living,
                         posts.room,
                         posts.content,
                         posts.date_created,
                         posts.date_updated,
                         posts.date_closed,
                         posts.status,

                         addresses.id_address,
                         addresses.id_user,
                         addresses.id_city,
                         addresses.longitude,
                         addresses.latitude,

                         geo_cities.name AS city_name,
                         geo_cities.zipcode,

                         geo_countries.id_country,
                         geo_countries.iso2 AS country_code,
                         geo_countries.name_full AS country_name,

                         galleries.media_count AS has_photo,
                         galleries.id_cover
                   FROM posts
                   JOIN addresses
                        ON posts.id_address = addresses.id_address
                   JOIN geo_cities
                        ON addresses.id_city = geo_cities.id_city
                   JOIN geo_countries
                        ON geo_cities.id_country = geo_countries.id_country
                   JOIN galleries
                        ON galleries.id_gallery = posts.id_gallery
                  WHERE posts.id_post IN (' . implode(",", $ids_posts) . ')
                        AND posts.status = ' . Cnst::VALIDATED . '
                        AND posts.date_closed < NOW()
               ORDER BY posts.date_created DESC';

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

    private static function make_title($id_property_type, $surface_living, $room = NULL, $zipcode = NULL)
    {
        $title = self::$property_type[$id_property_type];
        if ($surface_living > 0)
            $title .= $surface_living . 'm²';

        return $title;
    }
}
