<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\OrderLine;
use App\Service\Cart\CartService;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{

    /**
     * @Route("/", name="order_all")
     */
    public function index(OrderRepository $orderRepo) : Response{


        return $this->render('order/index.html.twig',[
            'orders' => $orderRepo->findAll()
        ]);
    }

    /**
    * @Route("/add/{user}",name="order_add")
    */
    public function addOrder(User $user, CartService $cart, EntityManagerInterface $em){

        $order = new Order();
        $order->setRefCommande(111111112);
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setMontant($cart->getCartTotal());
        $order->setUser($user);

        $em->persist($order);

        //Création des lignes CDE_XXXXXX
        $cartDetails = $cart->index();

        foreach ($cartDetails as $line) {
            $orderLine = new OrderLine();
            $orderLine->setQuantity($line['quantite']);
            $orderLine->setProduct($line['produit']);
            $orderLine->setOrdernum($order);
            $totalLigne = $line['quantite'] * $line['produit']->getPrice();
            $orderLine->setTotal($totalLigne);
        }
        $em->persist($orderLine);
        $em->flush();
        // dd($order, $orderLine);

        return $this->redirectToRoute('order_all');    
    }



    public function showCdeDetail(Order $order){

        return $this->render('order/orderDetail.html.twig',[
            'ols'=>$order->getOrderlines()
        ]);
    }




//     public function index(SessionInterface $session, UserInterface $user, EntityManagerInterface $em, CartService $cart, OrderRepository $orderRepo): Response
//     {
//         $faker = \Faker\Factory::create();
        
//         $panier = $session->get('panier',[]);
//         $order = new Order();
//         $order->setUser($user);
//         $order->setRefCommande($faker->numberBetween($min = 1, $max = 1000000));
//         $order->setMontant($cart->getCartTotal());
//         $order->setCreatedAt(new \DateTimeImmutable('now'));
//         $em->persist($order);
//         $em->flush();

//         $session->set('panier',[]);

//         $ord = $orderRepo->findBy(['user'=>$user->getId()]);
            

//         return $this->render('order/order.html.twig', [
//             'commande' => $ord,
//         ]);
//     }




}
