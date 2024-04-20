<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'route_search')]
    public function index(Request $request): Response
    {
        $query = $request->request->get('data');
        $random_photo = '';


        if (empty($query)) {
            $query = "Mars";
        }

        $returned_data = $this->API_NASA('https://images-api.nasa.gov/search?q=' . $query, 'GET');

        //return $this->json($returned_data['collection']['items'][0]['links']);

        $shuffle_number = range(0, count($returned_data['collection']['items']) - 1);
        shuffle($shuffle_number);
        $random_index = $shuffle_number[0];

        if ($returned_data['collection']['items'] and $returned_data['collection']['items'][$random_index]) {
            $random_photo = $returned_data['collection']['items'][$random_index]['links'][0]['href'];
        }

        return $this->render('search/search.html.twig', [
            'title' => 'Recherche sur '.$query,
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
