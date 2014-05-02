<?php

class PostController extends BaseController
{

    public function get_one()
    {
        # code...
    }

    public function get_create()
    {
        $data = ['__page_title' => 'Create Post'];

        return View::make('post/create', $data);
    }

    public function post_create()
    {
        $inputs = Input::all();
        $inputs['id_user'] = \User::getIdOrZero();

        // Validate all fields before insert
        $validation = Custom\Post::validate_all($inputs);

        if ($validation['failed'] === TRUE)
        {
            return Redirect::to('post/create')->withInput()
                            ->withErrors($validation['errors']);
        }
        else
        {

            try {
                // Begin inserting everything
                DB::beginTransaction();

                // address
                $id_address = \Custom\Address::upsert($inputs);
                if ($id_address === -1)
                    throw new Exception("[POST CREATE] failed inserting address");

                $inputs['id_address'] = $id_address;

                // post details
                $id_post_detail = \Custom\PostDetails::insert($inputs);
                if ($id_post_detail === -1)
                    throw new Exception("[POST CREATE] failed inserting details");

                $inputs['id_post_detail'] = $id_post_detail;

                // post
                $inputs['id_gallery'] = 0;
                $validation = \Custom\Post::validate($inputs);
                if ($validation->fails())
                    throw new Exception("[POST CREATE] failed validating post");

                $id_post = \Custom\Post::insert($inputs);
                if ($id_post === -1)
                    throw new Exception("[POST CREATE] failed inserting post");

                $inputs['id_post'] = $id_post;

                // Everything went well, so good to go
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                if (App::environment('dev')) throw $e;

                return oops('post/create');
            }

            $success = Lang::get('post.success.creation');
            return Redirect::to('/post/' . $inputs['id_post'] . '/bla')->with('flash.notice.success', $success);
        }
    }

    public function get_edit()
    {
        # code...
    }

    public function post_edit()
    {
        # code...
    }

    public function get_delete()
    {
        # code...
    }

    public function delete_delete()
    {
        # code...
    }
}
