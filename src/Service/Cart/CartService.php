<?php

namespace App\Service\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

    class CartService{

        protected $session;
        protected $produit;

        public function __construct(SessionInterface $session, ProductRepository $produit){
            $this->session = $session;
            $this->produit = $produit;
        }


        public function add(int $id){

            $panier = $this->session->get('panier',[]);
    
            if(!empty($panier[$id])){
                $panier[$id]++;
            }
            else{
                $panier[$id] = 1;
            }
            $this->session->set('panier',$panier);
        
        }

        public function index(){

            $panier = $this->session->get('panier',[]);
            $tabPanier=[];
    
            foreach ($panier as $id => $quantity) {
                     
                $tabPanier[] = [
                    'produit' => $this->produit->find($id),
                    'quantite' => $quantity
                ];
            }
            
            return $tabPanier;
        }

        public function itemLess(int $id){

            $panier = $this->session->get('panier',[]);
    
            if(!empty($panier[$id]) && $panier[$id] > 1){
                $panier[$id]--;
            }
            // else{
                // $this->redirectToRoute("del_item",$id,$session);
                // deleteItem($id,$session);
                // $this->deleteItem($id,$session);
            // }
            
            $this->session->set('panier',$panier);    
        }






        public function deleteItem(int $id){

            $panier = $this->session->get('panier',[]);
    
            if(isset($panier[$id])){
                unset($panier[$id]);
            }
            
            $this->session ->set('panier',$panier);
    
        }


    }