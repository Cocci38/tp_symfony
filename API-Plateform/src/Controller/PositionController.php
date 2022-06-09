<?php

namespace App\Controller;

use App\Entity\Position;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class PositionController extends AbstractController
{
    /**
     * @Route("/users/", name="app_default")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $users = $doctrine->getRepository(Position::class)->findAll();
        return $this->render('default/users/users.html.twig', [
            'users' => $users
        ]);
    }
}