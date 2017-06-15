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
    public function indexAction() {
        $om = $this->getDoctrine()->getManager();
        // rechercher toutes les news
        $repo = $om->getRepository('AppBundle:News');

        $news = $repo->findAll();
        foreach ($news as $key => $value) {
            $d->news[$key] = array(
                'id' => $value->getId(),
                'titre' => $value->getTitre(),
                'texte' => $value->getTexte(),
                'image' => $value->getImage()
            );
        }

        return $this->render("section/index/index.html.twig", [
                    'news' => $d->news
        ]);
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
     * @Route("/news/{id}" ,name="news_list", requirements={"id": "\d+"})
     */
    public function newsDetail($id) {
        $om = $this->getDoctrine()->getManager();
        // rechercher objet News avec Id $id
        $repo = $om->getRepository('AppBundle:News');

        $news = $repo->find($id);

        if (empty($news)) {
            return $this->render("pages/news.html.twig", [
            "id" => $id,
            "idNotFound" => true
            ]);
        }

        return $this->render("pages/news.html.twig", [
                    'id' => $id,
                    'titre' => $news->getTitre(),
                    'texte' => $news->getTexte(),
                    'image' => $news->getImage()
        ]);
    }

    /**
     * @Route("/test/{param}", defaults={"param"= "Jema"}, name="test")
     * this match http://127.0.0.1:8000/test but not http://127.0.0.1:8000/test/ ???
     * @Route("/test{param}", defaults={"param"= "Jema"}, name="test")
     * marche pas!!!
     */
    public function test(Request $request, $param) {
        var_dump($param);
        echo "\n\r\n\r<br /><br />";
        var_dump($request);
        return new \Symfony\Component\HttpFoundation\Response("test réussi!");

}}
