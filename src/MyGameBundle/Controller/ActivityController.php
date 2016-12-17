<?php

namespace MyGameBundle\Controller;

use MyGameBundle\Entity\Activity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ActivityController extends Controller
{
    /**
     * @Route("/activities")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Method("GET")
     */
    public function jsonActivities(){
        $player = $this->getUser();
        $activities = $this->getDoctrine()->getRepository(Activity::class)->findAllActivity($player->getId(), 10);
        $data = [];
        $now = new \DateTime('now');
        $i = 0;

        /** @var Activity $activity */
        foreach($activities as $activity){
            $interval = date_diff($activity->getTime(), $now);

            if($activity->getWinner()->getId() === $player->getId()) {
                $data[$i]['name'] = htmlentities($activity->getLoser()->getUsername());
                $data[$i]['type'] = 'victory';
            }
            else {
                $data[$i]['name'] = htmlentities($activity->getWinner()->getUsername());
                $data[$i]['type'] = 'loss';
            }

            $data[$i]['time'] = $interval->format('%Hh %im %ss ago');
            $i++;
        }

        return $this->json($data);
    }
}
