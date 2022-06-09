<?php

namespace App\Controller;

use App\Entity\Position;
use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class TeamController extends AbstractController
{
    /**
     * @Route("/", name="app_default")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $teams = $doctrine->getRepository(Team::class)->findAll();
        $equipe = [];
        foreach ($teams as $team) {
            $tableTeam = [];
            $tableTeam['id'] = $team->getId() . " ";
            $tableTeam['firstname'] = $team->getFirstName() . " ";
            $tableTeam['lastname'] = $team->getLastName() . " ";
            $tableTeam['supervisor'] =  "(" . $team->getSupervisor() . ") ";
            $tableTeam['position'] = $team->getPositions();
            echo "&nbsp;&nbsp;" .  $tableTeam['id'] . "->" . count($tableTeam['position']);
            foreach ($tableTeam['position'] as $position) {
                foreach ($position as $positionTeam) {
                    $tableTeam['label'] .= $position->getLabel();
                }
                if ($tableTeam['id'] = $position->getId()) {
                    $tableTeam['label'] = $position->getLabel();
                }

                $equipe[] = $tableTeam;
            }
        }

        // $position = $doctrine->getRepository(Position::class )->findAll();

        // $teams[]= $position;

        return $this->render('default/index.html.twig', [
            'teams' => $equipe
        ]);
    }

    //     /**
    //  * @Route("/", name="app_default")
    //  */
    // public function users(ManagerRegistry $doctrine): Response
    // {
    //     $teams = $doctrine->getRepository(Team::class )->findAll();
    //     $team = [];
    //     $team[] .= $teams;

    //     return $this->render('default/index.html.twig', [
    //         'team' => $teams
    //     ]);
    // }
}