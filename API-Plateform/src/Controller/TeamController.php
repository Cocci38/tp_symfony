<?php

namespace App\Controller;

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
        // Je recherche tous les éléments de l'entité Team
        $teams = $doctrine->getRepository(Team::class)->findAll();

        // Je boucle pour récupérer tous les éléments dans les getteurs
        $equipe = [];
        foreach ($teams as $team) {
            $tableTeam = [];
            $tableTeam['id'] = $team->getId() . " ";
            $tableTeam['firstname'] = $team->getFirstName() . " ";
            $tableTeam['lastname'] = $team->getLastName() . " ";
            $tableTeam['supervisor'] =  $team->getSupervisor();
            $tableTeam['position'] = $team->getPositions();

            // Je stocke dans $key le firstname et le lastname pour ne pas afficher les doublons
            $key = $tableTeam['firstname'] . $tableTeam['lastname'];

            //echo "&nbsp;&nbsp;" .  $tableTeam['id'] . "->" . count($tableTeam['position']);

            // Je boucle de nouveau sur les positions pour trouver les labels de l'entité Position 
            foreach ($tableTeam['position'] as $position) {
                $positionLabel['label'] = $position->getLabel();
                //var_dump($positionLabel);
                
                // Si l'une des positions est supérieur à 1
                if (count($tableTeam['position']) > 1) {
                    //var_dump($tableTeam['label']);

                    // Si $tableTeam est vide, on le rempli
                    if (!isset($tableTeam['label'])) {
                        $tableTeam['label']  = $positionLabel['label'];
                    // Sinon on rempli $tableTeam en concacenant les labels qui se trouve en doublon
                    } else {
                        $tableTeam['label'] = $tableTeam['label'] . ' / ' .  $positionLabel['label'];
                        //var_dump($tableTeam['label']);
                    }
                // Sinon on insère le label dans la $tableTeam['label']
                } else {
                    $tableTeam['label'] = $position->getLabel();

                    //var_dump($tableTeam['label']);
                }
                // On stocke tous nos données dans le tableau $equipe pour le lire dans Twig avec le tri de $key
                //var_dump($tableTeam);
                $equipe[$key] = $tableTeam;
                //var_dump($equipe[$key]);
            } // end foreach
        }
        foreach ($equipe as $table) {
            $hierarchie[] = $table['supervisor'];
        }
        $unique = array_values(array_unique($hierarchie));
        //var_dump($unique[1]);
        //$equipe  = $hierarchie;
        //var_dump($equipeHierarchie);

        return $this->render('default/index.html.twig', [
            'teams' => $equipe,
            'unique' => $unique
        ]);
    }
}