<?php

namespace MyGameBundle\Controller;

use Doctrine\ORM\Mapping\Cache;
use MyGameBundle\Entity\Player;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/", name="game_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        if ($user) {
            /** @var Player $user */
            $session = $this->get('session');
            if (!$session->has('island_id')) {
                $session->set('island_id', $user->getIslands()[0]->getId());
            }
        }
        return $this->render('base.html.twig');
    }
}
