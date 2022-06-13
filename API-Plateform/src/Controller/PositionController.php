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
        //var_dump($element);

        // $croll = le pointeur
        function hierarchie($tab, $element, $croll, &$level)
        {
            if ($tab[$croll] == null) {
                $level[$croll] = 0;
                return 0;
            }
            if (!isset($level[$element])) {
                $level[$element] = 1;
            } else {
                $level[$element]++;
            }
            hierarchie($tab, $element, $tab[$croll], $level);
        }

        $level = [];

        foreach ($tab as $index => $data) {
            hierarchie($tab, $index, $index, $level);
        }

        $keys = array_keys($tab);
        $values = array_values($tab);

        $leaves = array_diff($keys, $values);
        //var_dump($level);
        function order($tab, $leaves, $prf, $level, &$order)
        {
            if ($prf == 0) {
                return 0;
            }
            // var_dump('prf : ' . $prf);
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
            $result = array_merge($result, $ordre);
        }

        $result = array_unique($result);

        error_log(print_r($result,1));
        return $this->render('default/users.html.twig', [
            'level' => $order
        ]);
    }
}