<?php namespace Services\Seloger;

class Bot extends \BaseController
{
    protected $layout = NULL;

    public function getFilldatabase()
    {
        $search = new \Seloger\Search();

        $search->type('rent');
        $search->order('date_desc');
        $search->property('appartement');
        $search->zipcode([
            '750101', '750102', '750103', '750104', '750105', '750106', '750107',
            '750108', '750109', '750110', '750111', '750112', '750113', '750114',
            '750115', '750116', '750117', '750118', '750119', '750120'
        ]);
        $search->price(700, 5500);

        $results = $search->run();
        // print_r($results);
        // die();

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

            $gallery = [];
            if (isset($annonce->photos->photo))
            {
                foreach ($annonce->photos->photo as $photo)
                {
                    if (!isset($photo->ordre))
                        continue;

                    $gallery[] = [
                        'order' => $photo->ordre,
                        'url' => $photo->stdUrl,
                        'title' => 'location appartement Paris - 15ème arrondissement - 75015'
                    ];
                }
            }

            $list[$annonce->idAnnonce] = [
                'post' => [
                    'id_post_type' => $annonce->idTypeTransaction == 1 ? 2 : 1,
                    'id_property_type' => 1,
                    'content' => $annonce->descriptif,
                    'date_created' => $annonce->dtCreation
                ],
                'details' => [
                    'condition' => $annonce->siLotNeuf === 'false' ? \Custom\Post\Details::CONDITION_USED : CONDITION_NEW,
                    'bathroom' => empty($annonce->nbsallesdebain) ? 0 : $annonce->nbsallesdebain,
                    'wc' => (bool)$annonce->nbtoilettes,
                    'garage' => (bool)$annonce->nbparkings,
                    'balcony' => ($annonce->siterrasse == 'False' ? false : true),
                    'surface_living' => isset($annonce->surface) ? (int)$annonce->surface : 0,
                    'room' => (int)$annonce->nbPiece,
                ],
                'price' => [
                    'value' => $annonce->prix,
                    'type' => ($annonce->prixUnite == '€cc*' ? \Custom\Post\Price::ALL_INCLUSIVE : \Custom\Post\Price::NOT_INCLUDED)
                ],
                'address' => [
                    'address1' => 'NULL',
                    'id_city' => \Custom\Geo::get_id_city_from_zipcode($annonce->cp),
                    'origin' => 'post',
                    'longitude' => (double)$annonce->longitude,
                    'latitude' => (double)$annonce->latitude
                ],
                'source' => [
                    'name' => 'seloger',
                    'id' => $annonce->idAnnonce
                ],
                'medias' => $gallery
            ];
        }

        // print_r($list);
        // die();

        $has = \Custom\Post\Source::has($ids, 'seloger');

        $stats = [
            'total' => count($ids),
            'new' => 0,
            'old' => 0,
            'done' => []
        ];
        foreach ($has as $id => $bool)
        {
            if ($bool === FALSE)
            {
                $stats['new']++;
                $stats['done'][$id] = \Custom\Post::create($list[$id]);
            }
            else
            {
                $stats['old']++;
            }
        }
        print_r($stats);
        print_r($has);
        die();
        print_r($list);
        die();
        print_r($results);
        die();
    }
}
?>
