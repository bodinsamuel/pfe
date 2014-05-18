<?php

class Pap_Bot extends BaseController
{
    protected $layout = NULL;

    public function getFilldatabase()
    {
        $request = new PAP\Request();

        $request->type = 'recherche';
        $request->setParams('produit', 'location');
        $request->setParams('nb_resultats_par_page', 10);
        $request->setParams('tri', 'date-desc');
        $request->setParams('prix', ['max' => 2500, 'min' => 1000]);

        $response = $request->run();
        var_dump($response);

        $request = new PAP\Request();
        $request->type = 'detail';

        $request->setParams('id', 400002489);
        $response = $request->run();
        var_dump($response);
        die();
    }
}
