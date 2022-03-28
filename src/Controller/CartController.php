<?php

namespace App\Controller;

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
    public function index(SessionInterface $session, ProductRepository $produit): Response
    {
        $panier = $session->get('panier',[]);
        $tabPanier=[];

        foreach ($panier as $id => $quantity) {
           
            $tabPanier[] = [
                'produit' => $produit->find($id),
                'quantite' => $quantity
            ];
        }
        $total = 0;

        foreach ($tabPanier as $value) {
            $totalProd = $value['produit']->getPrice() * $value['quantite'];
            $total += $totalProd;
        }

        return $this->render('cart/index.html.twig', [
            'produits' => $tabPanier,
            'total'=> $total
        ]);
    }

    
    /**
    * @Route("/cart/add{id}", name="cart_add")
    */
    public function add(int $id, SessionInterface $session){

        $panier = $session->get('panier',[]);

        if(!empty($panier[$id])){
            $panier[$id]++;
        }
        else{
            $panier[$id] = 1;
        }
        $session ->set('panier',$panier);

        return $this -> redirectToRoute("prods");

    }


    /**
    * @Route("/cart/item_less{id}", name="item_less")
    */
    public function itemLess(int $id, SessionInterface $session){

        $panier = $session->get('panier',[]);

        if(!empty($panier[$id]) && $panier[$id] > 1){
            $panier[$id]--;
        }
        else{
            // $this->redirectToRoute("del_item",$id,$session);
            // deleteItem($id,$session);
            $this->deleteItem($id,$session);
        }
        
        $session ->set('panier',$panier);

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
    public function deleteItem(int $id, SessionInterface $session){

        $panier = $session->get('panier',[]);

        if(isset($panier[$id])){
            unset($panier[$id]);
        }
        
        $session ->set('panier',$panier);

        return $this -> redirectToRoute("cart_view");
    }

}
