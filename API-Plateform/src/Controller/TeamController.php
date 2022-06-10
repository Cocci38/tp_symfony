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
            //echo "&nbsp;&nbsp;" .  $tableTeam['id'] . "->" . count($tableTeam['position']);
$key = $tableTeam['firstname'] . $tableTeam['lastname'];



            foreach ($tableTeam['position'] as $position) {
                //$positionLabel['label'] = [];
                $positionLabel['label']= $position->getLabel();
                //var_dump($positionLabel);
                if (count($tableTeam['position']) >1 /*&& isset($tableTeam['label'])*/) {
                   // foreach ($positionLabel as $positionTeam) {
                        // echo ' coucou ';
                        //var_dump($tableTeam['label']);

                        if (!isset ($tableTeam['label'] )) {
                            $tableTeam['label']  = $positionLabel['label'];
                        } else {
                        
                            $tableTeam['label'] = $tableTeam['label'] .' / ' .  $positionLabel['label'] ;
                            var_dump($tableTeam['label']);
                        }
                   // }
                }else {
                    $tableTeam['label'] = $position->getLabel();
                    
                    //var_dump($tableTeam['label']);
                }
                // if ($tableTeam['id'] = $position->getId()) {
                //     $tableTeam['label'] = $position->getLabel();
                //     //var_dump($tableTeam);
                // }
                $equipe[$key] = $tableTeam;
            } // end foreach
        }
        // foreach ($equipe as $table) {
        //     $hierarchie[] = $table['supervisor'];
        // }
        // $unique = array_values(array_unique($hierarchie));



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