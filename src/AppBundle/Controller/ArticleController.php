<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\ArticleType;
use AppBundle\Entity\Article;
use AppBundle\Entity\Tag;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticleController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function listAction(Request $request)
    {
        $filterCategory = $this->getDoctrine()
            ->getRepository('AppBundle:Category')
            ->findOneById($request->query->get('category'));
        
        $filterTag = $this->getDoctrine()
            ->getRepository('AppBundle:Tag')
            ->findOneByName($request->query->get('tag'));
        
        $articlesRep = $this->getDoctrine()
            ->getRepository('AppBundle:Article');
        
        if(!is_null($filterCategory)) {
            $articles = $articlesRep->findByCategory($filterCategory);
        } elseif(!is_null($filterTag)) {
            $articles = $articlesRep->findAll();
        } else {
            $articles = $articlesRep->findAll();
        }
        
        $categories = $this->getDoctrine()
            ->getRepository('AppBundle:Category')
            ->findAll();       
        
        
        return $this->render('article/list.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
            'filterCategory' => $filterCategory
        ]);
    }
    
    
    /**
     * @Route("/article/{id}", name="article_show")
     */
    public function showAction(Article $article, Request $request)
    {
        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
        
    }
    
    /**
     * @Route("/new", name="article_new")
     */
    public function newAction(Request $request) {   
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($request);
    
        if($form->isValid()) {
            $slug = $this->get('app.slugger')->slugify($article->getTitle());
            $article->setSlug($slug);

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            
            $this->addFlash('success', 'Article créé !');
            
            return $this->redirectToRoute('article_new');
        }
        
        return $this->render('article/new.html.twig', [
            'form' => $form->createView(),
            'title' => 'Création d\'un article'
        ]);
    }
}
