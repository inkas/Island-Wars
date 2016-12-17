<?php

namespace MyGameBundle\Controller;

use MyGameBundle\Entity\Building;
use MyGameBundle\Entity\GameResource;
use MyGameBundle\Entity\Island;
use MyGameBundle\Entity\IslandBuilding;
use MyGameBundle\Entity\IslandResource;
use MyGameBundle\Entity\IslandTroop;
use MyGameBundle\Entity\Message;
use MyGameBundle\Entity\Troop;
use MyGameBundle\Helper\Coordinates;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use MyGameBundle\Entity\Player;
use MyGameBundle\Form\UserType;
use MyGameBundle\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class UserController
 * @package MyGameBundle\Controller
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class UserController extends IslandAwareController
{
    const MIN_X = 0;
    const MAX_X = 100;

    const MIN_Y = 0;
    const MAX_Y = 100;

    const INIT_ISLANDS = 3;

    const INIT_RESOURCES = 1000;

    const INIT_BUILDINGS_LEVEL = 1;

    const INIT_TROOPS_COUNT = 0;

    /**
     * @Route("/register", name="user_register")
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        // 1) build the form
        $player = new Player();
        $form = $this->createForm(UserType::class, $player);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($player, $player->getPlainPassword());
            $player->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($player);

            //  Initial Data
            $islandRepository = $this->getDoctrine()->getRepository(Island::class);
            for ($i = 0; $i < self::INIT_ISLANDS; $i++) {
                do {
                    $x = rand(self::MIN_X, self::MAX_X);
                    $y = rand(self::MIN_Y, self::MAX_Y);
                    $usedIsland = $islandRepository->findOneBy(
                        ['x' => $x, 'y' => $y]
                    );
                } while ($usedIsland !== null);

                $island = new Island();
                $island->setX($x);
                $island->setY($y);
                $island->setPlayer($player);
                $em->persist($island);

                $resourceRepository = $this->getDoctrine()->getRepository(GameResource::class);
                $resourceTypes = $resourceRepository->findAll();
                $now = new \DateTime('now');

                foreach ($resourceTypes as $resourceType) {
                    $islandResource = new IslandResource();
                    $islandResource->setIsland($island);
                    $islandResource->setResource($resourceType);
                    $islandResource->setAmount(self::INIT_RESOURCES);
                    $islandResource->setRefreshOn($now);
                    $em->persist($islandResource);
                }

                $buildingRepository = $this->getDoctrine()->getRepository(Building::class);
                $buildingTypes = $buildingRepository->findAll();

                foreach ($buildingTypes as $buildingType) {
                    $islandBuilding = new IslandBuilding();
                    $islandBuilding->setIsland($island);
                    $islandBuilding->setBuilding($buildingType);
                    $islandBuilding->setLevel(self::INIT_BUILDINGS_LEVEL);
                    $em->persist($islandBuilding);
                }

                $troopRepository = $this->getDoctrine()->getRepository(Troop::class);
                $troopTypes = $troopRepository->findAll();

                foreach ($troopTypes as $troopType){
                    $islandTroop = new IslandTroop();
                    $islandTroop->setIsland($island);
                    $islandTroop->setTroop($troopType);
                    $islandTroop->setAmount(self::INIT_TROOPS_COUNT);
                    $em->persist($islandTroop);
                }

                $em->flush();
            }

            // Login the user on successful registration
            $token = new UsernamePasswordToken($player, null, 'main', $player->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main', serialize($token));

            return $this->redirectToRoute('user_profile');
        }

        return $this->render(
            'user/register.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     *
     * @Route("/profile", name="user_profile")
     */
    public function profileAction()
    {
        /** @var Player $player */
        $player = $this->getUser();
        return $this->render("user/profile.html.twig", [
            'player'=>$player,
            'islandId' => $this->getIsland()
        ]);
    }

    /**
     * @Route("/change_island/{id}", name="change_island")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeIsland($id)
    {
        /** @var Player $player */
        $player = $this->getUser();
        $islandRepository = $this->getDoctrine()->getRepository(Island::class);
        $island = $islandRepository->findOneBy(
            [
                'id'=>$id,
                'player'=>$player
            ]
        );

        if ($island === null) {
            return $this->redirectToRoute("security_logout");
        }

        $this->get('session')->set('island_id', $id);

        return $this->redirectToRoute("user_profile");
    }
}
