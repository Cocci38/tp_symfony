<?php

namespace App\Controller;

use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Controller\TeamController;

class PositionController extends AbstractController
{
    /**
     * @Route("/users", name="app_users")
     */
    public function recursive(): Response
    {
        $tab = ['A' => null, 'B' => 'A', 'C' => 'A', 'D' => 'B', 'E' => 'B'];
        $element = array_keys($tab);
        // $level = ['A'=>0, 'B'=>'1', 'C'=>'1', 'D'=>'2', 'E'=>'2'];
        //var_dump($tab);

        /* La fonction hierarchie pour établir le nombre de niveaux 
        * $element = ne bouge pas, c'est de la d'où l'on part (se sont les clés du tableau $tab)
        * $croll = c'est le pointeur qui se déplace depuis le poin de départ ($element)
        * $level = le tableau que l'on construit et qui contiendra les niveaux
        */
        function hierarchie($tab, $element, $croll, &$level)
        {
             // Condition de sortie (si le pointeur = 0, on sort)
            if ($tab[$croll] == null) {
                $level[$croll] = 0;
                return 0;
            }
            // Pour contruire $level on part de 1 et si ce n'est pas 1, on incrémente
            if (!isset($level[$element])) {
                $level[$element] = 1;
            } else {
                $level[$element]++;
            }
            // $tab[$croll] = le supérieur de l'élément
            hierarchie($tab, $element, $tab[$croll], $level);
        }

        $level = [];

        foreach ($tab as $index => $data) {
            // le deuxième $index par du même endroit que l'index
            hierarchie($tab, $index, $index, $level);
        }

        $keys = array_keys($tab);
        $values = array_values($tab);
        
        // Compare le tableau pour calculer les $keys(clef) qui ne sont pas dans $values(valeur)
        $leaves = array_diff($keys, $values);
        //var_dump($level);

        // La fonction order pour déterminer les branches 
        function order($tab, $leaves, $prf, $level, &$order)
        {
            // Condition de sortie (si la profondeur = 0, on sort)
            if ($prf == 0) {
                return 0;
            }
            // var_dump('prf : ' . $prf);
            /* $leaves = les feuilles se sont les extrémités, les éléments qui ne sont pas des valeurs
            * On part des feuilles des plus basse pour remonter et trouver l'odre. 
            * On fait un 1er passage sur le niveau 2 pour déterminer qui si touve puis on le fait sur le niveau 1 et à 0 on sort
            */
            foreach ($leaves as $leaf) {

                if ($level[$leaf] == $prf) {
                    $order[] = [$leaf];
                    //var_dump('order ');
                }
            }
            // error_log("prf : " . $prf . " - " . print_r($order, 1));
            foreach ($order as $index => $chaine) {
                array_unshift($chaine, $tab[$chaine[0]]);
                $order[$index] = $chaine;
            }
            order($tab, $leaves, $prf - 1, $level, $order);
            // error_log("order : " . $order . " - " . print_r($chaine,1));
        }
        $order = [];
        order($tab, $leaves, max($level), $level, $order);

        $result = [];
        foreach ($order as $ordre) {
        /* array_merge() = rassemble les éléments d'un ou de plusieurs tableaux en ajoutant les valeurs de l'un à la fin de l'autre. 
        * Le résultat est un tableau.
        * Si les tableaux d'entrées ont des clés en commun, alors la valeur finale pour cette clé écrasera la précédente.
        */
            $result = array_merge($result, $ordre);
        }
        /** array_unique = on supprime les doublons
         * array_values = pour éviter les trous dans le tableau
         */
        $result = array_values(array_unique($result));

        //error_log(print_r($result,1));
        return $this->render('default/users.html.twig', [
            'result' => $result,
            'level' => $level,
            'key' => $keys
        ]);
    }
}