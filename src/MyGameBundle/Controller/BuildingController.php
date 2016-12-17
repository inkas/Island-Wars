<?php

namespace MyGameBundle\Controller;

use MyGameBundle\Entity\Building;
use MyGameBundle\Entity\BuildingProcess;
use MyGameBundle\Entity\GameResource;
use MyGameBundle\Entity\Island;
use MyGameBundle\Entity\IslandBuilding;
use MyGameBundle\Entity\IslandResource;
use MyGameBundle\Helper\Time;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class BuildingsController
 * @package MyGameBundle\Controller
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class BuildingController extends IslandAwareController
{
    const BUILD_TIME = 3;

    const MAX_BUILDING_LEVEL = 10;

    /**
     * @Route("/buildings", name="buildings_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $island = $this->getDoctrine()->getRepository(Island::class)->find($this->getIsland());
        $resources = $this->getDoctrine()->getRepository(GameResource::class)->findBy([], ['id' => 'ASC']);
        $islandBuildings = $island->getBuildings();
        $islandsBuildingsCostTime = $finishesOn = [];
        $now = new \DateTime('now');

        /** @var IslandBuilding $islandBuilding */
        foreach($islandBuildings as $islandBuilding) {
            $islandsBuildingsCostTime[] = Time::getFormattedTime($this->getIslandBuildingCostTime($islandBuilding));

            //  Check if current island building is building at this moment
            if($process = $islandBuilding->getProcess()){
                //  Get interval between finish and now in seconds
                $finishTimestamp = $process->getFinishesOn()->getTimestamp();
                $nowTimestamp = $now->getTimestamp();

                if($finishTimestamp > $nowTimestamp){
                    $finishesOn[] = $finishTimestamp - $nowTimestamp;
                }
                else{
                    //  Upgrade finished
                    $buildingProcess = $this->getDoctrine()->getRepository(BuildingProcess::class)->findOneBy([
                        'islandBuilding' => $islandBuilding->getId()
                    ]);
                    $em = $this->getDoctrine()->getManager();
                    $islandBuilding->setLevel($islandBuilding->getLevel() + 1);
                    $em->persist($islandBuilding);
                    $em->remove($buildingProcess);
                    $em->flush();

                    $finishesOn[] = null;
                }
            }
            else{
                $finishesOn[] = null;
            }
        }

        $viewData = [
            'buildings' => $islandBuildings,
            'resources' => $resources,
            'timeAmount' => $islandsBuildingsCostTime,
            'finishesOn' => $finishesOn,
            'upgradesDisabled' => false
        ];

        foreach ($finishesOn as $f){
            if(!is_null($f)) $viewData['upgradesDisabled'] = true;
        }

        return $this->render('buildings/buildings.html.twig', $viewData);
    }

    /**
     * @Route("/buildings/upgrade/{id}", name="upgrade_building")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function upgrade($id)
    {
        $island = $this->getDoctrine()->getRepository(Island::class)->find($this->getIsland());
        $building = $this->getDoctrine()->getRepository(Building::class)->find($id);
        $islandBuilding = $this->getDoctrine()->getRepository(IslandBuilding::class)
            ->findOneBy(['island'=>$island,'building'=>$building]);

        if(!$islandBuilding->getProcess() && $islandBuilding->getLevel() < self::MAX_BUILDING_LEVEL){
            $finishesOn = new \DateTime('now');
            $finishesOn->add(new \DateInterval('PT' . $this->getIslandBuildingCostTime($islandBuilding) . 'S'));

            $process = new BuildingProcess();
            $process->setIslandBuilding($islandBuilding);
            $process->setFinishesOn($finishesOn);

            $em = $this->getDoctrine()->getManager();
            $em->persist($process);

            $costs = $building->getCosts();
            $allResources = [];
            foreach ($costs as $cost) {
                $resourcesInIsland = $this->getDoctrine()->getRepository(IslandResource::class)
                    ->findOneBy(['resource'=>$cost->getResource(),'island'=>$island]);
                if ($resourcesInIsland->getAmount() >= ($cost->getAmount() * ($islandBuilding->getLevel() + 1))) {
                    $allResources[$cost->getResource()->getName()] = ($cost->getAmount() * ($islandBuilding->getLevel() + 1));
                } else {
                    return $this->redirectToRoute("buildings_list");
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
                $em->flush();
            }
        }

        return $this->redirectToRoute("buildings_list");
    }

    private function getIslandBuildingCostTime(IslandBuilding $islandBuilding){
        $costTime = $islandBuilding->getBuilding()->getTimeCost()->getAmount();
        $currentLevel = $islandBuilding->getLevel();

        return $costTime * $currentLevel * self::BUILD_TIME;
    }
}
