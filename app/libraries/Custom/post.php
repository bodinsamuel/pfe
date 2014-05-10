<?php namespace Custom;

class Post
{
    const NEED_VALIDATION = 0;
    const VALIDATED = 1;
    const DELETED = -1;

    const POST_TYPE_SELL = 1;
    const POST_TYPE_LOCATION = 2;

    /**
     * Full process for creating a Post
     * @param  array $inputs array of input
     * @return {[type]}         [description]
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

                if (isset($inputs['source']))
                {
                    $sourced = Post\Source::upsert($id_post, $inputs['source']['name'], $inputs['source']['id']);
                    if ($id_post === -1)
                        throw new \Exception("[POST CREATE] failed inserting source");

                    $inputs['sourced'] = TRUE;
                }

                // Everything went well, so good to go
                \DB::commit();

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
            'id_post_type', 'id_post_detail', 'id_gallery', 'id_address', 'content'
        ]);
        $inputs['status'] = self::NEED_VALIDATION;
        $inputs['id_user'] = \User::getIdOrZero();

        // Query
        $query = 'INSERT INTO posts
                              (id_post_type, id_post_detail, id_gallery, id_user,
                               id_address, content, date_created, date_updated,
                               date_closed, status)
                       VALUES (:id_post_type, :id_post_detail, :id_gallery, :id_user,
                               :id_address, :content, NOW(), NOW(), "NULL", :status)';

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
                'id_post_detail' => 'required|integer',
                'id_gallery' => 'required|integer',
                'id_address' => 'required|integer',
                'content'    => 'required'
            ]
        );
    }

    public static function search($search)
    {
        $query = 'SELECT *
                   FROM posts
                   JOIN addresses
                        ON posts.id_address = addresses.id_address
                   JOIN geo_cities
                        ON addresses.id_city = geo_cities.id_city
                   JOIN geo_countries
                        ON geo_cities.id_country = geo_countries.id_country
                  WHERE zipcode = :search
               ORDER BY posts.date_created DESC';

        return \DB::select($query, ['search' => $search]);
    }
}