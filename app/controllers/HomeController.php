<?php

class HomeController extends BaseController {

    /**
     * Homepage
     */
	public function run()
	{
		return View::make('home/home');
	}

}
