<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Form\CategoryType;
use App\Controller\StoreController;
use App\Repository\ProductRepository;
use App\Controller\CategoryController;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CategoryController extends AbstractController
{
    
    // **********************************************
    // *********************CATEGORIE****************   
    // **********************************************


    /**
    * @Route("/category/view", name="cats")
    */
    public function catView(CategoryRepository $catRepo): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->render('/erreur/accessDenied.html.twig');
		}

        $categories = $catRepo->findAll();

        return $this->render('store/cat.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
    * @Route("/category/new", name="cat-new")
    * @Route("/category/edit/{id}", name="cat-edit")
    */
    public function addOrUpdateCategorie(Category $category=null, Request $req, EntityManagerInterface $em){
        
        if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->render('/erreur/accessDenied.html.twig');
		}

        if(!$category){
            $category = new category;
        }

        $formCategory = $this->createForm(CategoryType::class, $category);

        $formCategory->handleRequest($req);

        if ($formCategory->isSubmitted() && $formCategory->isValid()) {
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('prods',['id'=>$category->getId()]);
        }
        return $this->render('store/catForm.html.twig',[
            'formCategory'=> $formCategory->createView(),
            'mode'=> $category->getId() !== null
        ]);
    }

    /**
    * @Route("/category/delete/{id}", name="cat-delete")
    */
    public function deleteCategorie(Category $category, EntityManagerInterface $em){

        try {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        // if (!$this->isGranted('ROLE_ADMIN')) {
		// 	return $this->render('/erreur/accessDenied.html.twig');
		// }

            if($category){
                $em->remove($category);
                $em->flush();
            }

            return $this->redirectToRoute("cats");
        }
        catch(AccessDeniedException $ex){
            $this->addFlash('error', "Vous n'avez pas les droits necessaires pour accèder à cette fonction");
		    return $this->render('/index.html.twig');

        }
        
    }
}
