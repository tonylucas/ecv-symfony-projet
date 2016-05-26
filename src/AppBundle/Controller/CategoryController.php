<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\CategoryType;
use AppBundle\Entity\Category;
use AppBundle\Entity\Tag;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryController extends Controller
{
    /**
     * @Route("/category", name="category_list")
     */
    public function listAction(Request $request)
    {
        $categories = $this->getDoctrine()
            ->getRepository('AppBundle:Category')
            ->findAll();
        
        return $this->render('category/list.html.twig', [
            'categories' => $categories,
        ]);
    }
    
    
    /**
     * @Route("/category/{id}", name="category_show", requirements={"id": "\d+"})
     */
    public function showAction(Category $category, Request $request)
    {
        return $this->render('category/show.html.twig', [
            'category' => $category
        ]);
        
    }
    
    /**
     * @Route("/category/new", name="category_new")
     */
    public function newAction(Request $request) {   
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($request);
    
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            
            $this->addFlash('success', 'Categorie créé !');
            
            return $this->redirectToRoute('category_new');
        }
        
        return $this->render('category/new.html.twig', [
            'form' => $form->createView(),
            'title' => 'Création d\'une categorie'
        ]);
    }
    
    /**
     * @Route("/category/edit/{id}", name="category_edit")
     */
    public function editAction(Request $request, Category $category) {
        
        $form = $this->createForm(CategoryType::class, $category);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($request);
        
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            
            $this->addFlash('success', 'Catégorie mise à jour !');
            
            return $this->redirectToRoute('category_edit', array('id' => $category->getId()));
        }
        
        return $this->render('category/new.html.twig', [
            'form' => $form->createView(),
            'title' => 'Édition d\'une catégorie'
        ]);
    }
    
    /**
     * @Route("/category/delete/{id}", name="category_delete")
     */
    public function deleteAction(Request $request, Category $category) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        
        $this->addFlash('success', 'Catégorie supprimée !');
        
        return $this->redirectToRoute('category_list');
    }
}
