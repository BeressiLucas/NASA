<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function index(): Response
    {
        return $this->render('mars/mars-curiosity.html.twig', [
            'message' => 'Hello Mars!'
        ]);
    }    
}
