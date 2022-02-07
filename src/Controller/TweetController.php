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

    function getTweet($id)
    {
        // Obtenemos el tweet
        $entityManager = $this->getDoctrine()->getManager();
        $tweet = $entityManager->getRepository(Tweet::class)->find($id);
        // Si el tweet no existe devolvemos un error con código 404.
        if ($tweet == null) {
            return new JsonResponse([
                'error' => 'Tweet not found'
            ], 404);
        }
        // Creamos un objeto genérico y lo rellenamos con la información.
        $result = new \stdClass();
        $result->id = $tweet->getId();
        $result->date = $tweet->getDate();
        $result->text = $tweet->getText();
        // Para enlazar al usuario, añadimos el enlace API para consultar su información.
        $result->user = $this->generateUrl('api_get_user', [
            'id' => $tweet->getUser()->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);
        // Para enlazar a los usuarios que han dado like al tweet, añadimos sus enlaces API.
        $result->likes = array();
        foreach ($tweet->getLikes() as $user) {
            $result->likes[] = $this->generateUrl('api_get_user', [
                'id' => $user->getId(),
            ], UrlGeneratorInterface::ABSOLUTE_URL);
        }
        // Al utilizar JsonResponse, la conversión del objeto $result a JSON se hace de forma automática.
        return new JsonResponse($result);
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