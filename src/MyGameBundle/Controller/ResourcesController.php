<?php

namespace MyGameBundle\Controller;

use MyGameBundle\Entity\Building;
use MyGameBundle\Entity\Island;
use MyGameBundle\Entity\IslandBuilding;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ResourcesController extends IslandAwareController
{
    const NEW_RESOURCE_INTERVAL_MIN = 2;

    const GOLD_UPDATE_RATIO = 1;

    const STONES_UPDATE_RATIO = 0.3;

    const WOODS_UPDATE_RATIO = 0.7;

    /**
     * @Route("/resources/update", name="update_resources")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateResourcesAction()
    {
        $island = $this->getDoctrine()->getRepository(Island::class)->find($this->getIsland());
        $buildingRepository = $this->getDoctrine()->getRepository(Building::class);
        $islandBuildingRepository = $this->getDoctrine()->getRepository(IslandBuilding::class);
        $now = new \DateTime('now');
        $em = $this->getDoctrine()->getManager();

        foreach($island->getResources() as $resource){
            $nextRefresh = $resource->getRefreshOn();
            $nextRefresh->modify('+' . self::NEW_RESOURCE_INTERVAL_MIN . ' min');

            if($nextRefresh < $now){
                $elapsedSecondsSinceLastRefresh = $now->getTimestamp() - $resource->getRefreshOn()->getTimestamp();
                $newAmount = $resource->getAmount();

                //  Refresh the resources and datetime in db
                switch($resource->getResource()->getName()){
                    case 'Gold':
                        $building = $buildingRepository->findOneBy(['name' => 'Gold Mine']);
                        $islandBuilding = $islandBuildingRepository->findOneBy([
                            'island' => $island,
                            'building' => $building
                        ]);

                        $newAmount += $islandBuilding->getLevel() * $elapsedSecondsSinceLastRefresh * self::GOLD_UPDATE_RATIO;
                        break;
                    case 'Stones':
                        $newAmount += $elapsedSecondsSinceLastRefresh * self::STONES_UPDATE_RATIO;
                        break;
                    case 'Woods':
                        $building = $buildingRepository->findOneBy(['name' => 'Sawmill']);
                        $islandBuilding = $islandBuildingRepository->findOneBy([
                            'island' => $island,
                            'building' => $building
                        ]);

                        $newAmount += $islandBuilding->getLevel() * $elapsedSecondsSinceLastRefresh * self::WOODS_UPDATE_RATIO;
                        break;
                    default:
                        break;
                }

                $resource->setAmount($newAmount);
                $resource->setRefreshOn($now);
                $em->persist($resource);
            }
        }

        $em->flush();

        return $this->redirectToRoute("user_profile");
    }
}
