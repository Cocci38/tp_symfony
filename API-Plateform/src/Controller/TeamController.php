<?php

namespace App\Controller;

use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

use function App\Controller\hierarchie as ControllerHierarchie;

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
            $tableTeam['id'] = $team->getId();
            $tableTeam['firstname'] = $team->getFirstName();
            $tableTeam['lastname'] = $team->getLastName();
            $tableTeam['supervisor'] =  $team->getSupervisor();
            $tableTeam['photo'] = $team->getPhoto();
            $tableTeam['position'] = $team->getPositions();

            // Je stocke dans $key le firstname et le lastname pour ne pas afficher les doublons
            $key = $tableTeam['firstname'] . " " . $tableTeam['lastname'];
            $tableTeam['cle'] = $team->getFirstName() . " " . $team->getLastName();
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
                // var_dump($tableTeam);
                $equipe[$key] = $tableTeam;

                // var_dump($equipe[$key]);
            } // end foreach
        }
        foreach ($equipe as $table) {
            $element[] = $table['supervisor'];
        }
        //$unique = array_values(array_unique($element));

        foreach ($equipe as $tableKey ) {
            $keysTable [] = $tableKey['firstname'] . " " . $tableKey['lastname'];
        }
        // array_combine => Crée un tableau en utilisant un tableau pour les clés et un autre pour ses valeurs
        $tableEquipe = array_combine($keysTable, $element);
        error_log("table équipe ".print_r($tableEquipe, 1));
        // echo '<pre>', print_r($tableEquipe,1), '</pre>';die;
        //$equipe  = $element;
        //var_dump($equipeHierarchie);
        $element = array_keys($tableEquipe);
        function hierarchie($tableEquipe, $element, $scroll, &$level)
        {
            if (empty($tableEquipe[$scroll]) ) {
                $level[$scroll] = 0;
                return 0;
            }
            if (!isset($level[$element])) {
                $level[$element] = 1;
            }else {
                $level[$element]++;
            }
            
            hierarchie($tableEquipe, $element, $tableEquipe[$scroll], $level);
        }
        
        $level = [];

        foreach ($tableEquipe as $index => $data) {
            hierarchie($tableEquipe, $index, $index, $level);
        }
        // echo '<pre>', print_r($level,1), '</pre>';
        // error_log(print_r($level,1));
        $keys = array_keys($tableEquipe);
        $values = array_values($tableEquipe);

        $leaves = array_diff($keys, $values);
        
        function branche($tableEquipe, $leaves, $prf, $level, &$branche)
        {
            if ($prf == 0) {
                return 0;
            }

            foreach ($leaves as $leaf) {

                if ($level[$leaf] == $prf) {
                    $branche[] = [$leaf];
                }
            }
            foreach ($branche as $index => $chaine) {
                array_unshift($chaine, $tableEquipe[$chaine[0]]);
                $branche[$index] = $chaine;
            }
            branche($tableEquipe, $leaves, $prf - 1, $level, $branche);
        }
        $branche = [];
        branche($tableEquipe, $leaves, max($level), $level, $branche);
        
        $result = [];

        foreach ($branche as $branches) {
            $result = array_merge($result, $branches);
        }
        $result = array_values(array_unique($result));
        //echo '<pre>' , print_r($result,1), '<pre>';
        // error_log(print_r($result,1));
        
        return $this->render('default/index.html.twig', [
            'teams' => $result,
            'level' => $level,
            'equipe' => $equipe
        ]);
    }

    /**
     * @Route("/{id}", name="app_show")
     */
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $membre = $doctrine->getRepository(Team::class)->find($id);
        
            $tableMember['position'] = $membre->getPositions();

            // Je boucle de nouveau sur les positions pour trouver les labels de l'entité Position 
            foreach ($tableMember['position'] as $position) {
                $positionLabel['label'] = $position->getLabel();
                //var_dump($positionLabel);
                
                // Si l'une des positions est supérieur à 1
                if (count($tableMember['position']) > 1) {
                    //var_dump($tableMember['label']);

                    // Si $tableMember est vide, on le rempli
                    if (!isset($tableMember['label'])) {
                        $tableMember['label']  = $positionLabel['label'];
                    // Sinon on rempli $tableMember en concacenant les labels qui se trouve en doublon
                    } else {
                        $tableMember['label'] = $tableMember['label'] . ' / ' .  $positionLabel['label'];
                        //var_dump($tableMember['label']);
                    }
                // Sinon on insère le label dans la $tableMember['label']
                } else {
                    $tableMember['label'] = $position->getLabel();

                    //var_dump($tableMember['label']);
                }

        }
        return $this->render('default/show.html.twig', [
            'member' => $membre,
            'position' => $tableMember
        ]);
    }
}