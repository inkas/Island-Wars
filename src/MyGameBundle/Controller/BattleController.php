<?php

namespace MyGameBundle\Controller;

use MyGameBundle\Entity\Activity;
use MyGameBundle\Entity\GameResource;
use MyGameBundle\Entity\Island;
use MyGameBundle\Entity\IslandResource;
use MyGameBundle\Entity\IslandTroop;
use MyGameBundle\Entity\Message;
use MyGameBundle\Entity\Player;
use MyGameBundle\Entity\Troop;
use MyGameBundle\Form\MessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BattleController
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 * @package MyGameBundle\Controller
 */
class BattleController extends IslandAwareController
{
    /**
     * @Route("/enemies", name="enemies")
     */
    public function enemies(Request $request){
        /** @var Player $player */
        $player = $this->getUser();
        $resources = $this->getDoctrine()->getRepository(GameResource::class)->findBy([], ['id' => 'ASC']);
        $troops = $this->getDoctrine()->getRepository(Troop::class)->findBy([], ['id' => 'ASC']);
        $players = $this->getDoctrine()->getRepository(Player::class)->findAll();
        $playersIslands = $this->getDoctrine()->getRepository(Island::class)->findBy(['player' => $players]);
        $em = $this->getDoctrine()->getManager();
        $forms = [];
        $playerTroops = [];

        foreach($playersIslands as $island){
            $playerTroops[$island->getId()] = [];
            foreach ($island->getTroops() as $troop){
                $playerTroops[$island->getId()][$troop->getTroop()->getName()] = $troop->getAmount();
            }

            $message = new Message();

            // creates the forms with different names
            $form = $this->get('form.factory')->createNamedBuilder(
                'text_'.$island->getPlayer()->getId(),
                MessageType::class, $message
            )->getForm();

            $forms[$island->getPlayer()->getId()] = $form->createView();

            $form->handleRequest($request);

            if ($form->isValid()) {
                if ($request->request->has($form->getName())) {
                    $message->setSender($player);
                    $message->setReceiver($island->getPlayer());
                    $message->setSentOn(new \DateTime('now'));
                    $em->persist($message);
                    $em->flush();
                    return $this->redirectToRoute("enemies");
                }
            }
        }

        return $this->render('enemies/enemies.html.twig', [
            'forms'         => $forms,
            'playerIslands' => $playersIslands,
            'resources'     => $resources,
            'playerTroops'  => $playerTroops,
            'troops'        => $troops
        ]);
    }

    /**
     * @Route("/attack/{id}", name="attack")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function enemyAction($id){
        $island = $this->getDoctrine()->getRepository(Island::class)->find($this->getIsland());
        $enemyIslandTroops = $this->getDoctrine()->getRepository(IslandTroop::class)->findBy(['island' => $id]);
        $playerIslandTroops = $island->getTroops();
        $enemyPlayer = $this->getDoctrine()->getRepository(Island::class)->findOneBy(['id' => $id])->getPlayer();

        $enemyTotalDamage = $enemyTotalHealth = $totalDamage = $totalHealth = 0;

        foreach ($enemyIslandTroops as $enemyIslandTroop){
            $enemyTotalDamage += $enemyIslandTroop->getAmount() * $enemyIslandTroop->getTroop()->getAttack();
            $enemyTotalHealth += $enemyIslandTroop->getAmount() * $enemyIslandTroop->getTroop()->getHealth();
        }

        foreach ($playerIslandTroops as $playerIslandTroop){
            $totalDamage = $playerIslandTroop->getAmount() * $playerIslandTroop->getTroop()->getAttack();
            $totalHealth = $playerIslandTroop->getAmount() * $playerIslandTroop->getTroop()->getHealth();
        }

        if(!$totalDamage || !$totalHealth){
            $this->addFlash('warning', 'Train some troops first!');
            return $this->redirectToRoute("enemies");
        }

        $activity = new Activity();

        $e = $enemyTotalDamage - $totalHealth;
        $p = $totalDamage - $enemyTotalHealth;

        if($e > $p){
            $activity->setLoser($this->getUser());
            $activity->setWinner($enemyPlayer);
        }
        else if($p > $e){
            $activity->setLoser($enemyPlayer);
            $activity->setWinner($this->getUser());
        }
        else{
            if(rand(0, 1)){
                $activity->setLoser($enemyPlayer);
                $activity->setWinner($this->getUser());
            }
            else{
                $activity->setLoser($this->getUser());
                $activity->setWinner($enemyPlayer);
            }
        }

        $activity->setTime(new \DateTime('now'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($activity);

        $em->flush();

    //    $activiy = $this->getDoctrine()->getRepository(Activity::class)


//        $islandResources = $this->getDoctrine()->getRepository(IslandResource::class)->findBy(['island' => $id]);
//
//        $enemyIsland = $this->getDoctrine()->getRepository(Island::class)->find($id);
//        $enemyIsland->getResources();
//
//        $em = $this->getDoctrine()->getManager();
//        /** @var IslandResource $i */
//        foreach ($enemyIsland as $i){
////            foreach($i->getResource()->getIslandResources() as $resource){
////                $resource->;
////                $island->setAmount($island->getAmount() + $resource->getAmount() * 0.2);
////                $resource->setAmount($resource->getAmount() - $resource->getAmount() * 0.2);
////
////            }
//
//            $i->
//        }
//
////        foreach ($island->get)


        return $this->redirectToRoute("user_profile");
    }
}
