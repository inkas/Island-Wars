<?php

namespace MyGameBundle\Controller;

use MyGameBundle\Entity\Message;
use MyGameBundle\Entity\Player;
use MyGameBundle\Form\MessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MessagingController extends Controller
{
    /**
     * @Route("/messages")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Method("GET")
     */
    public function jsonMessages(){
        $player = $this->getUser();
        $messages = $this->getDoctrine()->getRepository(Message::class)->findAllMessages($player->getId(), 10);
        $data = [];
        $now = new \DateTime('now');
        $i = 0;

        /** @var Message $message */
        foreach($messages as $message){
            $interval = date_diff($message->getSentOn(), $now);

            if($message->getSender()->getId() === $player->getId()) {
                $data[$i]['name'] = htmlentities($message->getReceiver()->getUsername());
                $data[$i]['role'] = 'sender';
            }
            else {
                $data[$i]['name'] = htmlentities($message->getSender()->getUsername());
                $data[$i]['role'] = 'receiver';
            }

            $data[$i]['text'] = htmlentities($message->getText());
            $data[$i]['sent_on'] = $interval->format('%Hh %im %ss ago');

            $i++;
        }

        return $this->json($data);
    }
}
