<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class MarsController extends AbstractController
{
    /** 
     * Vous allez tout d'abord devoir créer un Controller Mars dans lequel vous allez créer une route 
     * /mars (vous gérerez les routes à l'aide des annotations et non du fichier routes.yaml). 
     * Cette route devra retourner "Hello Mars!".
     */

    #[Route('/Mars', name: 'route_mars')]
    public function mars(): Response
    {
        return $this->render('mars/hello-mars.html.twig', [
            'message' => 'Hello Mars!',
        ]);
    }

    /**
     * Vous allez ensuite créer une route /curiosity  qui va retourner une template avec le contenu suivant :
     * 
     * Vous devez faire appel à l'API de la NASA afin de récupérer, aléatoirement, une photo prise par le rover Curiosity lors de son voyage sur Mars. Dans un souci d'esthétique, vous devrez récupérer uniquement les photos de la caméra de navigation de Curiosity.
     * 
     * Pour ce faire, l'utilisateur doit pouvoir choisir la date de la photo qui va être récupérée à l'aide d'un input. Une fois la photo aléatoire récupérée, vous devrez l'afficher dans la page. Si jamais aucune photo n'est trouvée pour la date, pensez à n'afficher qu'aucune photo n'a été trouvée.
     */

    #[Route('/curiosity', name: 'route_curiosity')]
    public function index(Request $request): Response
    {
        $time = $request->request->get('data');
        $random_photo = '';
        $APIKEY = $this->getParameter('app.APIKEY_NASA');
    
        if (empty($time)) {
            $randomYear = rand(2012, 2024); 
            $randomMonth = rand(1, 12);
            $randomDay = rand(1, 28); 
            $time = $randomYear . '-' . sprintf("%02d", $randomMonth) . '-' . sprintf("%02d", $randomDay);
        }
    
        $returned_data = $this->API_NASA('https://api.nasa.gov/mars-photos/api/v1/rovers/curiosity/photos?earth_date=' . $time . '&camera=NAVCAM&api_key=' . $APIKEY . '', 'GET');
    
        $shuffle_number = range(0, count($returned_data['photos']) - 1);
        shuffle($shuffle_number);
        $random_index = $shuffle_number[0];
    
        if ($returned_data['photos'] && $returned_data['photos'][$random_index]) {
            $random_photo = $returned_data['photos'][$random_index]['img_src'];
        }
    
        return $this->render('mars/mars-curiosity.html.twig', [
            'message' => 'Hello Mars!',
            'imageUrl' => $random_photo,
        ]);
    }
    

    private function API_NASA(string $endpoint, string $method)
    {
        $client = HttpClient::create();

        try {
            $response = $client->request($method, $endpoint);

            if ($response->getStatusCode() === 200) {
                $content = $response->getContent();

                $data = json_decode($content, true);

                return $data;
            } else {
                throw new \Exception('La requête a échoué');
            }
        } catch (ExceptionInterface $e) {

            throw new \Exception('Une erreur s\'est produite lors de la requête : ' . $e->getMessage());
        }
    }
}
