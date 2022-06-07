<?php

namespace App\Controller;

use App\Entity\Position;
use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class DefaultController extends AbstractController
{
    /**
     * @Route("/default", name="app_default")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $team = $doctrine->getRepository(Team::class)->findAll();
        return $this->render('default/index.html.twig', [
            'team' => $team
        ]);
    }
}