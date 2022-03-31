<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
* @Route("/api")
*/

class ApiProductController extends AbstractController
{
    /**
     * @Route("/product", name="app_api_product", methods={"GET"})
     */
    public function index(ProductRepository $prodRepo)
    {   
        // json_encode ne sait traiter autre chose qu'un tableau
        // json_encode ne sait pas utiliser les getters
        // $products[0]['name'] ne marche pas car attribut en privé
        // solution normaliser les données => on utilise un normaliwzr
        
        // NormalizerInterface + json_encode()
        //************************************
        // $prodsNormalized = $normalizer->normalize($prods, null, ['groups'=> 'prods:read']);
        // $jsonData = json_encode($prodsNormalized);
        
        
        // Serializer remplace normalizer + json_encode()
        //*********************************************** 
        // $jsonData = $serializer->serialize($prods,'json', ['groups'=>'prods:read']);
        // $response = new Response($jsonData, 200, ["content-Type"=>"application/json"]);
        
        
        // simplication de la construction de la réponse (1)
        //**********************************************
        // $response = new JsonResponse($jsonData, 200, [], true);
        
        // simplication de la construction de la réponse (2)
        //**********************************************
        // $prods = $prodRepo->findAll();
        // $response = $this->json($prods, 200, [], ['groups'=> 'prods:read']);
        // return $response;

        // simplication de la construction de la réponse (3)
        //**********************************************
        return $this->json($prodRepo->findAll(), 200, [], ['groups'=> 'prods:read']);
    }

        /**
        * @Route("/order", name="app_api_product", methods={"GET"})
        */
        public function order(OrderRepository $orderRepo){

        return $this->json($orderRepo->findAll(), 200, [], ['groups'=> 'orders:read']);
        }
}
