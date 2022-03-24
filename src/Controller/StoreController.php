<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Form\CategoryType;
use App\Controller\StoreController;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StoreController extends AbstractController
{
    // **********************************************
    // *********************PRODUIT******************
    // **********************************************


    /**
    * @Route("/", name="home")
    */
    public function index(ProductRepository $prodRepo): Response
    {
        // $produits = $prodRepo->findAll();

        return $this->render('index.html.twig', [
            // 'produits' => $produits,
        ]);
    }

    /**
    * @Route("/produit/view", name="prods")
    */
    public function prodView(ProductRepository $prodRepo): Response
    {
        $produits = $prodRepo->findAll();

        return $this->render('store/prod.html.twig', [
            'produits' => $produits,
        ]);
    }

    /**
    * @Route("/produit/new", name="prod-new")
    * @Route("/produit/edit/{id}", name="prod-edit")
    */
    public function addOrUpdateProduit(Product $product=null, Request $req, EntityManagerInterface $em){
        
        if(!$product){
            $product = new Product;
        }

        $formProduct = $this->createForm(ProductType::class, $product);

        $formProduct->handleRequest($req);

        if ($formProduct->isSubmitted() && $formProduct->isValid()) {
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('prods',['id'=>$product->getId()]);
        }
        return $this->render('store/prodForm.html.twig',[
            'formProduct'=> $formProduct->createView(),
            'mode'=> $product->getId() !== null
        ]);
    }

    /**
    * @Route("/produit/delete/{id}", name="prod-delete")
    */
    public function deleteProduit(Product $product, EntityManagerInterface $em){

        if($product){
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute("prods");
    }

    // **********************************************
    // *********************CATEGORIE****************   
    // **********************************************


    /**
    * @Route("/category/view", name="cats")
    */
    public function catView(CategoryRepository $catRepo): Response
    {
        $categories = $catRepo->findAll();

        return $this->render('store/cat.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
    * @Route("/categorie/new", name="cat-new")
    * @Route("/categorie/edit/{id}", name="cat-edit")
    */
    public function addOrUpdateCategorie(Category $category=null, Request $req, EntityManagerInterface $em){
        
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
    * @Route("/categorie/delete/{id}", name="cat-delete")
    */
    public function deleteCategorie(Category $category, EntityManagerInterface $em){

        if($category){
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute("cats");
    }



}
