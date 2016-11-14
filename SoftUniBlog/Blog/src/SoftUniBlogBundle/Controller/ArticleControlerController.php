<?php

namespace SoftUniBlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SoftUniBlogBundle\Entity\Article;
use SoftUniBlogBundle\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArticleControlerController extends Controller
{
    /**
     * @Route("/article/delete/{id}", name="article_delete")
     * @param $id
     */
    public function deleteAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Article::class);
        $article= $repository->find($id);
        $currentUser = $this->getUser();
        if (!$article->isAuthor($currentUser))
        {
            return $this->redirectToRoute("blog_index");
        }
        $em=$this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute("blog_index");
    }


    /**
     * @Route("/article/edit/{id}", name = "article_edit")
     */
    public function editAction($id, Request $request)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        $currentUser = $this->getUser();
        if (!$article->isAuthor($currentUser))
        {
            return $this->redirectToRoute("blog_index");
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute("article_show", ['id'=> $id]);
        }

       return $this-> render ("article/edit.html.twig", [
           'form' => $form->createView(),
           'article' => $article
       ]);
    }

    /**
     * @Route("/article/create", name="create_article")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           $article -> setCreatedOn(new\DateTime());

            $user = $this->getUser();
            $article->setAuthor($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('blog_index');
        }

        return $this->render("article/create.html.twig",
            array('form' => $form->createView()));
    }

    /**
     * @Route ("/article/{id}", name ="article_show")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $articleRepository = $this-> getDoctrine()->getRepository(Article::class);
       $article = $articleRepository->find($id);
        return $this -> render("article/show.html.twig", ["article"=>$article]);
    }

    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

}
