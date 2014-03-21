<?php namespace Custom;

class Post
{
    const NEED_VALIDATION = 0;
    const VALIDATED = 1;
    const DELETED = -1;

    const POST_TYPE_SELL = 1;
    const POST_TYPE_LOCATION = 2;

    /**
     * Full process for validating a post
     * @param  array $inputs
     * @return array
     */
    public static function validate_all($inputs)
    {
        $return = ['failed' => FALSE, 'errors' => FALSE];

        // Validate address
        $val_address = \Custom\Address::validate($inputs);
        if ($val_address->fails())
            $return['failed'] = TRUE;

        // Validate details
        $val_details = \Custom\PostDetails::validate($inputs);
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
     * @return [type]
     */
    public static function insert($inputs)
    {
        $inputs = array_only($inputs, [
            'id_post_detail', 'id_gallery', 'id_address', 'content'
        ]);
        $inputs['status'] = self::NEED_VALIDATION;
        $inputs['id_user'] = \User::getIdOrZero();

        // Query
        $query = 'INSERT INTO posts
                              (id_post_detail, id_gallery, id_user, id_address,
                               content, date_created, date_updated, date_closed,
                               status)
                       VALUES (:id_post_detail, :id_gallery, :id_user, :id_address,
                               :content, NOW(), NOW(), "NULL", :status)';

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
                'id_post_detail' => 'required|integer',
                'id_gallery' => 'required|integer',
                'id_user'    => 'required|integer',
                'id_address' => 'required|integer',
                'content'    => 'required'
            ]
        );
    }
}
