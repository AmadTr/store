<?php

namespace App\Controller;

use App\Service\Cart\CartService;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart_view")
     */
    public function index(CartService $cartService): Response
    {
       $tabPanier = $cartService->index();
       $total = 0;
        // $totalP = 0;
            
            foreach ($tabPanier as $value) {
                $totalProd = $value['produit']->getPrice() * $value['quantite'];
                $total += $totalProd;
                // $totalP += $value['quantite'];
            }
            // $this->render('/base.html.twig',['itemNav'=>$totalP]);

        return $this->render('cart/panier.html.twig', [
            'produits' => $tabPanier,
            'total'=> $total
        ]);
    }


    /**
    * @Route("/cart/add{id}", name="cart_add")
    */
    public function add(int $id, CartService $cartService){
        
        $cartService->add($id);
        return $this -> redirectToRoute("prods");
    }


    /**
    * @Route("/cart/item_less{id}", name="item_less")
    */
    public function itemLess(int $id, CartService $cartService){

        $cartService->itemLess($id);
        return $this -> redirectToRoute("cart_view");
    }


    /**
    * @Route("/cart/item_more{id}", name="item_more")
    */
    public function itemMore(int $id, SessionInterface $session){

        $panier = $session->get('panier',[]);

        if(!empty($panier[$id])){
            $panier[$id]++;
        }
        
        $session ->set('panier',$panier);

        return $this -> redirectToRoute("cart_view");
    }


    /**
    * @Route("/cart/delete{id}", name="item_del")
    */
    public function deleteItem(int $id, CartService $cartService){

        $cartService->deleteItem($id);
        return $this -> redirectToRoute("cart_view");
    }
}
