<?php namespace Custom\Post;

class Admin
{
    public static function validate($id_post)
    {
        $return = [
            'post'    => FALSE,
            'gallery' => FALSE
        ];

        $post = \Custom\Post::light($id_post);
        if (empty($post))
            return $return;

        try {
            // Begin inserting everything
            \DB::beginTransaction();

            // validate post
            $return['post'] = self::status($id_post, \Custom\Cnst::VALIDATED);
            if ($return['post'] !== TRUE)
                throw new Exception("[POST] can't validate post");

            // validate gallery
            $return['gallery'] = \Custom\Gallery\Admin::validate($post[0]->id_gallery);
            if ($return['gallery'] !== TRUE)
                throw new Exception("[POST] can't validate gallery");

            // Upsert from elastic search
            // ELASTIC
            $bean = \Custom\Singleton::getBeanstalkd();
            $bean->sendEvents([
                'action' => 'PostElasticUpsert',
                'data' => [
                    'id_post' => $id_post
                ]
            ]);

            \DB::commit();
        } catch (Exception $e) {
            \DB::rollback();

            if (\App::environment('dev'))
                throw $e;

            $return['errors'][] = \Lang::get('global.error.oops');
        }

        return $return;
    }

    public static function delete($id_post)
    {
        $return = [
            'post'    => FALSE,
            'gallery' => FALSE
        ];

        $post = \Custom\Post::light($id_post);
        if (empty($post))
            return $return;

        try {
            // Begin inserting everything
            \DB::beginTransaction();

            // delete post
            $return['post'] = self::status($id_post, \Custom\Cnst::DELETED);
            if ($return['post'] !== TRUE)
                throw new Exception("[POST] can't delete post");

            // delete gallery
            $return['gallery'] = \Custom\Gallery\Admin::validate($post[0]->id_gallery);
            if ($return['gallery'] !== TRUE)
                throw new Exception("[POST] can't validate gallery");

            // Delete from elastic search
            // ELASTIC
            $bean = \Custom\Singleton::getBeanstalkd();
            $bean->sendEvents([
                'action' => 'PostElasticDelete',
                'data' => [
                    'id_post' => $id_post
                ]
            ]);

            \DB::commit();

        } catch (Exception $e) {
            \DB::rollback();

            if (\App::environment('dev'))
                throw $e;

            $return['errors'][] = \Lang::get('global.error.oops');
        }

        return $return;
    }

    public static function status($id_post, $status)
    {
        $query = 'UPDATE posts
                     SET posts.status = ' . (int)$status . '
                   WHERE posts.id_post = ' . (int)$id_post;

        $post = \DB::statement($query);
        if ($post === FALSE)
            return -1;

        return TRUE;
    }
}
