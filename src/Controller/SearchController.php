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
    /**
     * @Route("/search", name="search")
     *Les photos de Curiosity sont intéressantes mais vous devez maintenant récupérer toutes sortes de photos selon la recherche de l'utilisateur.
     *Pour ce faire vous allez créer un Controller Search dans lequel vous allez créer une route /search qui va retourner une template avec le contenu suivant :

     *Comme pour la récupération des photos de Curiosity, vous en afficherez une aléatoire parmi les résultats de la recherche et vous l'afficherez sur la page.
     
     *L'utilisateur doit pouvoir entrer sa recherche dans un input, il utilisera un bouton pour lancer la recherche correspondant à ce qu'il a indiqué dans l'input.
     */



    //Dans la partie 3, j'ai amélioré le code en ajoutant un rendu à /search et en déléguant le traitement des requêtes, tant depuis l'URL (/search/{query}) que depuis un formulaire, grâce à la méthode POST.
    #[Route('/search', name: 'route_search')]
    public function index(Request $request): Response
    {
        $query = $request->get('data') ?: 'Mars';
        $random_photo = $this->getImage($request, $query);

        return $this->render('search/search.html.twig', [
            'title' => 'Recherche sur ' . $query,
            'imageUrl' => $random_photo[0],
            'query' => $query,
            'description' => $random_photo[1]['collection']['items'][$random_photo[2]]['data'][0]['description'] ?? 'Aucune information',
            'titles' => $random_photo[1]['collection']['items'][$random_photo[2]]['data'][0]['title'] ?? 'Aucune information',
            'photographer' => isset($random_photo[1]['collection']['items'][$random_photo[2]]['data'][0]['photographer']) ? $random_photo[1]['collection']['items'][$random_photo[2]]['data'][0]['photographer'] : 'Aucune information',
            'date_created' => $random_photo[1]['collection']['items'][$random_photo[2]]['data'][0]['date_created'] ?? 'Aucune information',
        ]);
    }

    #[Route('/search/{query}', name: 'route_query_search')]
    public function search(Request $request, string $query): Response
    {

        $random_photo = '';
        $random_photo = $this->getImage($request, $query);

        return $this->render('search/search.html.twig', [
            'title' => 'Recherche sur ' . $query,
            'imageUrl' => $random_photo[0],
            'query' => $query,
            'description' => $random_photo[1]['collection']['items'][$random_photo[2]]['data'][0]['description'] ?? 'Aucune information',
            'titles' => $random_photo[1]['collection']['items'][$random_photo[2]]['data'][0]['title'] ?? 'Aucune information',
            'photographer' => isset($random_photo[1]['collection']['items'][$random_photo[2]]['data'][0]['photographer']) ? $random_photo[1]['collection']['items'][$random_photo[2]]['data'][0]['photographer'] : 'Aucune information',
            'date_created' => $random_photo[1]['collection']['items'][$random_photo[2]]['data'][0]['date_created'] ?? 'Aucune information',
        ]);
    }

    #[Route('/searchback/{query}', name: 'route_query_searchback')]
    public function searchback(Request $request, string $query): Response
    {

        $random_photo = $this->getImage($request, $query);


        return $this->render('search/searchback.html.twig', [
            'title' => 'Recherche sur ' . $query,
            'imageUrl' => $random_photo[0],
            'query' => $query
        ]);
    }

    public function getImage($request, $query)
    {
        $random_photo = '';
        $returned_data = $this->API_NASA('https://images-api.nasa.gov/search?q=' . $query, 'GET');

        $shuffle_number = range(0, count($returned_data['collection']['items']) - 1);
        shuffle($shuffle_number);
        $random_index = $shuffle_number[0];

        if ($returned_data['collection']['items'] and $returned_data['collection']['items'][$random_index]) {
            try {
                $random_photo = $returned_data['collection']['items'][$random_index]['links'][0]['href'];
            } catch (\Exception $e) {
                $random_photo = '';
            }
        }

        return [$random_photo, $returned_data, $random_index];
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
