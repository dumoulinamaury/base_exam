<?php

namespace AppBundle\Controller;

use AppBundle\Entity\News;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class DefaultController extends Controller {

    /**
     * @Route("/", name="homepage")
     */
    function indexAction() {
        $om = $this->getDoctrine()->getManager();
        // rechercher tous les évènements
        $repo = $om->getRepository('AppBundle:Events');

        $answer = $repo->findAll();
        $events = [];
        foreach ($answer as $key => $val) {
            $events[$key] = array(
                'id' => $val->getId(),
                'nom' => $val->getNom(),
                'description' => $val->getDescription(),
                'debut' => $val->getDebut(),
                'fin' => $val->getFin(),
                'category_id' => $val->getCategoryId(),
                'image_id' => $val->getImageId()
            );
        }

        return $this->render("section/index/index.html.twig", [
                    'events' => $events
        ]);
    }

    /**
     * @Route("/event/{id}" ,name="event", requirements={"id": "\d+"})
     */
    public function eventDetail($id) {
        $om = $this->getDoctrine()->getManager();
        // rechercher tous les évènements
        $repo = $om->getRepository('AppBundle:Events');

        $answer = $repo->findById($id);
        $event = [];
        if ($answer != null) {
            $event = array(
                'id' => $answer[0]->getId(),
                'nom' => $answer[0]->getNom(),
                'description' => $answer[0]->getDescription(),
                'debut' => $answer[0]->getDebut(),
                'fin' => $answer[0]->getFin(),
                'category_id' => $answer[0]->getCategoryId(),
                'image_id' => $answer[0]->getImageId()
            );
        }

        return $this->render("pages/event.html.twig", [
                    'id' => $id,
                    'event' => $event
        ]);
    }

    /**
     * @Route("/add/", name="add")
     */
    public function addAction(EntityManagerInterface $em) {
        $news = new News();
        $news->setTitre("Titre 1");
        $news->setTexte("Je suis le contenu de cette news");
        $news->setImage("img/chaton_from_hell.jpg");
        $em->persist($news);
        $em->flush();
        return $this->render('default/news.html.twig', [
                    'id' => 0
        ]);
    }

    /**
     * @Route("/test/{param}", name="test")
     */
    public function test(Request $request, $param) {
        var_dump($param);
        echo "\n\r\n\r<br /><br />";
        var_dump($request);
        return new \Symfony\Component\HttpFoundation\Response("test réussi!");
    }

    /**
     * @Route("/request/", name="request")
     */
    public function request(Request $request) {
        dump($request);
        echo $request->query->get('key');
        return $this->render('admin/debug.html.twig', [
                    'request' => $request
        ]);
    }

}
