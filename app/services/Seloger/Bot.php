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
                    'surface_living' => isset($annonce->surface) ? (int)$annonce->surface : 0,
                    'room' => 0,
                    'content' => $annonce->descriptif,
                    'date_created' => $annonce->dtCreation,
                    'status' => TRUE
                ],
                'details' => [
                    'bathroom' => $annonce->nbsallesdebain,
                    'wc' => (bool)$annonce->nbtoilettes,
                    'garage' => (bool)$annonce->nbparkings,
                    'balcony' => $annonce->siterrasse == 'False' ? false : true,
                ],
                'price' => $annonce->prix,
                'address' => [
                    'address1' => 'NULL',
                    'id_city' => 30840,
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
