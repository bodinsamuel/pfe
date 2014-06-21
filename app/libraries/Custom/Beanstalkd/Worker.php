<?php namespace Custom\Beanstalkd;

class Worker
{
    public static function PostElasticUpsert($data)
    {
        if (!isset($data['id_post']))
            return FALSE;

        // *************************** PROCESSING *************
        $elastic = new \Custom\Elastic\Post();

        $post = \Custom\Post::select([(int)$data['id_post']], ['galleries' => FALSE, 'markers' => FALSE]);
        $inserting = $elastic->insert($post['posts']);
        // *************************** END ********************

        if (!empty($inserting['error']))
        {
            Log::error('[Elastic] failed upserting => ' . $data['id_post']);
            Log::error($inserting['error']);
            return FALSE;
        }

        return TRUE;
    }

    public function ImageRatio($data)
    {
        if (!isset($data['id_media']) || !isset($data['ratio']))
            return FALSE;


        return TRUE;
    }
}
