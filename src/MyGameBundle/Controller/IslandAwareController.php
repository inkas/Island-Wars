<?php

namespace MyGameBundle\Controller;

use MyGameBundle\Entity\Island;
use MyGameBundle\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IslandAwareController extends Controller
{
    protected function getIsland()
    {
        $session = $this->get('session');
        /** @var Player $user */
        $user = $this->getUser();
        $island = $session->get('island_id');
        if ($island == null) {
            $island = $user->getIslands()[0]->getId();
            $session->set('island_id', $island);
        }

        return $island;
    }

    public function resourcesAction()
    {
        $id = $this->getIsland();
        $island = $this->getDoctrine()->getRepository(Island::class)->find($id);

        return $this->render('islands/partials/resources.html.twig', [
            'island' => $island
        ]);
    }
}
