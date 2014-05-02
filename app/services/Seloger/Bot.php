<?php

class Seloger_Bot extends BaseController
{
    protected $layout = NULL;

    public function getFilldatabase()
    {
        $search = new Seloger\Search();

        $search->type('rent');
        $search->order('date_desc');
        $search->property('appartement');
        $search->zipcode(['750115']);
        $search->price(800, 2500);

        $results = $search->run();

        if ($results->nbTrouvees == 0)
        {
            var_dump('No Results');
            return;
        }

        $list = [];
        $ids = [];
        foreach ($results->annonces->annonce as $key => $annonce)
        {
            $ids[] = $annonce->idAnnonce;

            $galery = [];
            if (isset($annonce->photos->photo))
            {
                foreach ($annonce->photos->photo as $photo)
                {
                    if (!isset($photo->ordre))
                        continue;

                    $galery[] = [
                        'order' => $photo->ordre,
                        'url' => $photo->stdUrl
                    ];
                }
            }

            $list[$annonce->idAnnonce] = [
                'content' => $annonce->descriptif,
                'date_created' => $annonce->dtCreation,
                'id_post_type' => $annonce->idTypeTransaction == 1 ? 2 : 1,
                'id_post_property_type' => 1,
                'surface_living' => $annonce->surface,
                'price' => $annonce->prix,
                'addresse' => [
                    'id_city' => 30840,
                    'origin' => 'post'
                ],
                'bathroom' => $annonce->nbsallesdebain,
                'wc' => (bool)$annonce->nbtoilettes,
                'parking' => (bool)$annonce->nbparkings,
                'balcony' => $annonce->siterrasse == 'False' ? false : true,
                'source' => [
                    'site' => 'Seloger',
                    'id_post' => $annonce->idAnnonce
                ],
                'galerie' => $galery
            ];
        }
        print_r($list);
        die();
        print_r($results);
        die();
    }
}
?>
