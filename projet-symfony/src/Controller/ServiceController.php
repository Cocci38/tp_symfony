<?php

namespace App\Controller;

use App\Entity\UserService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Flex\Unpack\Result;

class ServiceController extends AbstractController
{
    /**
     * @Route("/service", name="app_service")
     */
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ServiceController.php',
        ]);
    }

    /**
     * @Route("/service/create", name="app_service_create")
     */
    public function createAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userService = new UserService();
        $userService->setNom('Lulu');
        $userService->setAge(22);

        // informe Doctrine que l’on veut ajouter

        $entityManager->persist($userService);

        // Executer la requête et d’envoyer tout ce qui à été persisté avant a la BD
        $entityManager->flush();

        return new Response('Utilisateur ajouté ');
    }

        /**
     * @Route("/service/search", name="app_service_search")
     */
    public function searchAction()
    {
        $id = 1;
        $repository = $this->getDoctrine()->getRepository(UserService::class);
        
        // Récupèrer l'objet en fonction de l'Id 
        $personne['find'] = $repository->find($id);
        
        // Rechercher un seul produit par son nom
        $personne['findOneBy'] = $repository->findOneBy([
            'nom' => 'Lulu',
            'age' => '22',
    ]);
        
        $personnne['findOneBy'] = $repository->findBy(
            ['nom' => 'Lulu'],
            ['age' => 'ASC'], // le deuxième paramètre permet de définir l'ordre
            10, // le troisième la limite
            0 // et à partir duquel on récupère (OFFSET en MySQL)
        );
        
        $personne['findAll'] = $repository->findAll();
        echo '<pre>', print_r($personne), '</pre>';
        return new Response('Utilisateur trouvé');
    }
}