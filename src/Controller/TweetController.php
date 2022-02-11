<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Tweet;

class TweetController extends AbstractController
{
    function index() {
        $entityManager = $this->getDoctrine()->getManager();
        $tweets = $entityManager->getRepository(Tweet::class)->findAll();
        return $this->render('index.html.twig', array(
            'tweets' => $tweets,
        ));
    }

    function verTweet($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $tweet = $entityManager->getRepository(Tweet::class)->find($id);
        if (!$tweet){
            throw $this->createNotFoundException(
                'No existe ninguna tweet con id '.$id
            );
        }
        return $this->render('verTweet.html.twig', array(
            'tweet' => $tweet,
        ));
    }
}