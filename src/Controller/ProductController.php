<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Form\CategoryType;
use App\Controller\StoreController;
use App\Controller\ProductController;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProductController extends AbstractController
{
    // **********************************************
    // *********************PRODUIT******************
    // **********************************************


    /**
    * @Route("/", name="home")
    */
    public function home(ProductRepository $prodRepo): Response
    {
    
        return $this->render('index.html.twig');
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

        if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->render('/erreur/accessDenied.html.twig');
		}
        
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

        if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->render('/erreur/accessDenied.html.twig');
		}

        if($product){
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute("prods");
    }
}