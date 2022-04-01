<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
// use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;


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


        /**
        * @Route("/product/new", name="api_newProd", methods={"POST"})
        */
        public function addProduct(Request $req, SerializerInterface $serializer, CategoryRepository $catRepo, EntityManagerInterface $em,
         ValidatorInterface $validator){

            try {
                
                $jsonData = $req->getContent();
                $prod = $serializer->deserialize($jsonData, Product::class,'json');

                // on a que le nom de la catégorie
                // il nous faut l'id de la catégorie pou initialier
                // l'attribut categoriId du produit
                // ***********************************************
                $cat = $prod->getCategory();
                $getCat = $catRepo->findOneBy(['name'=>$cat->getName()]);
                // dd($cat, $getCat);

                // mettre a jour la category dans le produit
                // *****************************************
                $prod->setCategory($getCat);

                // Avant de persist il faut valider les données
                $errors = $validator->validate($prod);
                if(count($errors)){
                    return $this->json($errors, 400);
                }


                // envoyer a la base de données
                // ****************************
                $em->persist($prod);
                $em->flush();

                // envoyer une reponse au client
                // ******************************
                return $this->json($prod, 201, [], ['groups'=> 'prods:read']);

            } catch (NotEncodableValueException $ex) {

                return $this->json(['staut_Error'=>400, 'message'=>$ex->getMessage()], 400);
                //throw $th;
            }
        }
}
