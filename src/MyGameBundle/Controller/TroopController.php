<?php

namespace MyGameBundle\Controller;

use MyGameBundle\Entity\Building;
use MyGameBundle\Entity\GameResource;
use MyGameBundle\Entity\Island;
use MyGameBundle\Entity\IslandBuilding;
use MyGameBundle\Entity\IslandResource;
use MyGameBundle\Entity\IslandTroop;
use MyGameBundle\Entity\Player;
use MyGameBundle\Entity\TroopProcess;
use MyGameBundle\Form\MessageType;
use MyGameBundle\Form\TroopType;
use MyGameBundle\Helper\Time;
use phpDocumentor\Reflection\Types\Resource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TroopController
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 * @package MyGameBundle\Controller
 */
class TroopController extends IslandAwareController
{
    const EXTRA_DAMAGE = 5;
    const EXTRA_HEALTH = 4;

    const TRAIN_TIME = 2;

    const MAX_RIFLEMAN = 10;

    const MAX_TANKS = 5;

    const MAX_BATTLESHIPS = 2;

    /**
     * @Route("/troops", name="troops_list")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $island = $this->getDoctrine()->getRepository(Island::class)->find($this->getIsland());
        $resources = $this->getDoctrine()->getRepository(GameResource::class)->findBy([], ['id' => 'ASC']);
        $artillery = $this->getDoctrine()->getRepository(Building::class)->findOneBy(['name' => 'Artillery']);

        $islandArtillery = $this->getDoctrine()->getRepository(IslandBuilding::class)->findOneBy([
            'island' => $island,
            'building' => $artillery
        ]);

        $troopExtraLife = [
            'damage'    => $islandArtillery->getLevel() * self::EXTRA_DAMAGE,
            'health'    => $islandArtillery->getLevel() * self::EXTRA_HEALTH
        ];

        $islandTroops = $island->getTroops();
        $forms = $nextLevelResourcesCost = $nextLevelTrainingTimeCost = $maxTroopCount = $finishTime = [];

        foreach ($islandTroops as $islandTroop) {
            $troop = $islandTroop->getTroop();

            //  Resource costs
            $nextLevelResourcesCost[$troop->getId()] = [];

            /** @var GameResource $cost */
            foreach ($islandTroop->getTroop()->getCosts() as $cost => $value){
                $nextLevelResourcesCost[$troop->getId()][] = $value->getAmount() * $islandArtillery->getLevel();
            }

            //  Training time costs
            $trainTime = round($troop->getTimeCost()->getAmount() * self::TRAIN_TIME * $islandArtillery->getLevel());
            $nextLevelTrainingTimeCost[$troop->getId()] = Time::getFormattedTime($trainTime);

            //  Max troop count of each type
            if($troop->getName() === 'Rifleman')    $maxTroopCount[$troop->getId()] = self::MAX_RIFLEMAN;
            if($troop->getName() === 'Tank')        $maxTroopCount[$troop->getId()] = self::MAX_TANKS;
            if($troop->getName() === 'Battleship')  $maxTroopCount[$troop->getId()] = self::MAX_BATTLESHIPS;

            $troopProcess = $this->getDoctrine()->getRepository(TroopProcess::class)->findOneBy([
                'troop' => $troop,
                'island' => $island
            ]);

            $em = $this->getDoctrine()->getManager();

            if($troopProcess){
                $finishTimestamp = $troopProcess->getFinishesOn()->getTimestamp();
                $nowTimestamp = new \DateTime('now');
                $nowTimestamp = $nowTimestamp->getTimestamp();

                if($finishTimestamp > $nowTimestamp){
                    $finishTime[] = $finishTimestamp - $nowTimestamp;
                }
                else{
                    //  Upgrade finished
                    $em->remove($troopProcess);
                    $em->flush();
                    $finishTime[] = null;
                }
            }
            else{
                $finishTime[] = null;
            }

            $newIslandTroop = new IslandTroop();

            // creates the forms with different names
            $form = $this->get('form.factory')->createNamedBuilder(
                    'amount_'.$islandTroop->getTroop()->getName(),
                    TroopType::class, $newIslandTroop
            )->getForm();

            $forms[$islandTroop->getTroop()->getName()] = $form->createView();

            if ($request->getMethod() == 'POST') {
                $form->handleRequest($request);

                if ($form->isValid()) {
                    $totalIslandTroopAmount = $newIslandTroop->getAmount() + $islandTroop->getAmount();

                    if(($request->request->get('amount_Rifleman') && $totalIslandTroopAmount <= self::MAX_RIFLEMAN) ||
                        ($request->request->get('amount_Tank') && $totalIslandTroopAmount <= self::MAX_TANKS) ||
                        ($request->request->get('amount_Battleship') && $totalIslandTroopAmount <= self::MAX_BATTLESHIPS)){

                        //  Check if there's already a process of this troop type
                        if(!$troopProcess){
                            $finishesOn = new \DateTime('now');
                            $finishesOn->add(new \DateInterval('PT' . $trainTime * $newIslandTroop->getAmount() . 'S'));

                            $troopProcess = new TroopProcess();
                            $troopProcess->setFinishesOn($finishesOn);
                            $troopProcess->setTroop($islandTroop->getTroop());
                            $troopProcess->setIsland($island);
                            $islandTroop->setAmount($totalIslandTroopAmount);

                            $em->persist($troopProcess);
                            $em->persist($islandTroop);

                            $costs = $troop->getCosts();
                            $allResources = [];
                            foreach ($costs as $c) {
                                $resourcesInIsland = $this->getDoctrine()->getRepository(IslandResource::class)
                                    ->findOneBy(['resource' => $c->getResource(), 'island' => $island]);

                                if ($resourcesInIsland->getAmount() >= ($c->getAmount() * $newIslandTroop->getAmount())) {
                                    $allResources[$c->getResource()->getName()] = ($c->getAmount() * $newIslandTroop->getAmount() * $islandArtillery->getLevel());
                                }
                                else{
                                    return $this->redirectToRoute("troops_list");
                                }
                            }

                            $islandResources = $this->getDoctrine()->getRepository(IslandResource::class)
                                ->findBy(['island'=>$island]);

                            //  Renew the island resources
                            foreach ($islandResources as $islandResource) {
                                $name = $islandResource->getResource()->getName();
                                $cost = $allResources[$name];
                                $islandResource->setAmount($islandResource->getAmount() - $cost);
                                $em->persist($islandResource);
                            }
                            $em->flush();
                        }
                    }
                    else{
                        $this->addFlash('error', 'Entered amount of troops exceeds the limit of this troop type!');
                    }

                    return $this->redirectToRoute('troops_list');
                }
            }
        }

        return $this->render('/troops/troops.html.twig', [
            'extraLife'                 => $troopExtraLife,
            'nextLevelTrainingTimeCost' => $nextLevelTrainingTimeCost,
            'nextLevelResourcesCost'    => $nextLevelResourcesCost,
            'maxTroopCount'             => $maxTroopCount,
            'forms'                     => $forms,
            'troops'                    => $islandTroops,
            'finishesOn'                => $finishTime,
            'resources'                 => $resources
        ]);
    }
}
