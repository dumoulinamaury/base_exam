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

            $repo = $om->getRepository('AppBundle:Image');
            $answer = $repo->findById($event["image_id"]);
            $image = [];
            if ($answer != null) {
                $image = array(
                    "alt" => $answer[0]->getAlt(),
                    "url" => $answer[0]->getUrl()
                );
            }

            $repo = $om->getRepository('AppBundle:Categories');
            $answer = $repo->findById($event["category_id"]);
            $categorie = [];
            if ($answer != null) {
                $categorie = array(
                    "nom" => $answer[0]->getNom()
                );
            }
        }

        return $this->render("pages/event.html.twig", [
                    'id' => $id,
                    'event' => $event,
                    'image' => $image,
                    'categorie' => $categorie
        ]);
    }

    /**
     * @Route("/categories/", name="categories")
     */
    public function categories() {
        $om = $this->getDoctrine()->getManager();
        $repo = $om->getRepository('AppBundle:Categories');
        $answer = $repo->findAll();
        $categories = [];
        foreach ($answer as $key => $val) {
            $categories[$key] = array(
                "id" => $val->getId(),
                "nom" => $val->getNom(),
                "description" => $val->getDescription()
            );
            $repo = $om->getRepository('AppBundle:Events');
            $answer = $repo->findByCategory_id($categories[$key]["id"]);
            $categories[$key]['events'] = [];
            foreach ($answer as $key2 => $val2) {
                $categories[$key]['events'][$key2] = array(
                    "nom" => $val2->getNom(),
                    "debut" => $val2->getDebut(),
                    "fin" => $val2->getFin(),
                    "id" => $val2->getId()
                );
            }

//            $query = $repository->createQueryBuilder('p')
//    ->where('p.price > :price')
//    ->setParameter('price', '19.99')
//    ->orderBy('p.price', 'ASC')
//    ->getQuery();
//
//            $q = Doctrine_Query::create()
//            ->from('Events e')
//            ->leftJoin('e.Categories c')
//            ->where('e.category_id = ?', 1);
//          $article = $q->fetchOne();
        }
        return $this->render("pages/categories.html.twig", [
                    'categories' => $categories
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
