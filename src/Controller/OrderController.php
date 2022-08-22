<?php

namespace App\Controller;

use Datetime;
use App\Entity\Order;
use DateTimeInterface;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\OrderDetail;
use App\Repository\OrderRepository;
use App\Repository\CompletedRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/order')]
class OrderController extends AbstractController
{
    public function __construct(OrderRepository $orderRepository) {
        $this->orderRepository = $orderRepository;
    }

    #[Route('/complete/{id}', name: 'order_complete')]
    public function completeOrder($id) {
        $order = $this->orderRepository->find($id);
        $order->setComplete(true);
        foreach ($order->getOrderDetails() as $od) {
            $product = $this->getDoctrine()->getRepository(Product::class)->find($od->getProduct()->getId());
            $new = $product->getQuantity() - $od->getQuantity();
            $product->setQuantity($new);
            $this->getDoctrine()->getManager()->persist($product);
        }
        $this->getDoctrine()->getManager()->persist($order);
        $this->getDoctrine()->getManager()->flush();

        return $this->render('product/list.html.twig', 
        [
            'products' => $this->getDoctrine()->getRepository(Product::class)->findAll(),
        ]);
    }

    #[Route('/history', name: 'order_history')]
    public function orderHistory() {
        $orders = $this->getDoctrine()->getRepository(Order::class)->findAll();
        $results = array();
        for ($i = 0; $i < count($orders); $i++) {
            if ($orders[$i]->getUser() != $this->getUser()) {
                array_splice($orders, $i, 1);
            }
        }
        for ($i = 0; $i < count($orders); $i++) {
            $fix = $orders[$i]->getOrderDetails();
            for ($j = 0; $j < count($fix); $j++) {
                $sub = $fix[$j]->getProduct()->getId();
                if (in_array($sub, $results) == true) {
                    $results[$sub] += $fix[$j]->getQuantity();
                } else {
                    $results[$sub] = $fix[$j]->getQuantity();
                }
            }
        }
        return $this->render('order/history.html.twig', 
        [
            'orders' => $orders,
        ]);
    }

    #[Route('/list', name: 'order_list')]
    public function customerOrder() {
        $order = null;
        $orders = $this->orderRepository->findAll();
        for ($i = count($orders) - 1; $i >= 0; $i--) {
            if ($orders[$i]->getUser() == $this->getUser() && $orders[$i]->getComplete() == false) {
                $order = $orders[$i];
                break;
            }
        }
        return $this->render('order/list.html.twig',
        [
            'order' => $order,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/index', name: 'order_index')]
    public function index(): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $this->getDoctrine()->getRepository(Order::class)->findAll(),  
        ]);
    }

    #[Route('/detail/{id}', name: 'order_detail')]
    public function detailOrder($id) {
        return $this->render('order/index.html.twig', [
            'orders' => $this->orders,
        ]);
    }

    #[Route('/make/{id}', name: 'order_make')]
    public function makeOrder($id, Request $request) {
        $orders = $this->getDoctrine()->getRepository(Order::class)->findAll();
        $con = false;
        $manager = $this->getDoctrine()->getManager();
        if ($orders != null) {
            for ($i = count($orders) - 1; $i >= 0; $i--) {
                if ($orders[$i]->getComplete() == false && $orders[$i]->getUser() == $this->getUser()) {
                    $con = true;
                    $orderDetail = new OrderDetail;
                    $orderDetail->setProduct($this->getDoctrine()->getRepository(Product::class)->find($id));
                    $orderDetail->setOrders($this->orderRepository->find($i));
                    $orderDetail->setQuantity($request->get('quantity'));
                    $orders[$i]->addOrderDetail($orderDetail);
                    $manager->persist($orders[$i]);
                    $manager->persist($orderDetail);
                    $manager->flush();
                    break;
                }
            }
        }
        
        if (!$con) {
            $order = new Order;
            $order->setComplete(false);
            $order->setUser($this->getUser());
            $orderDetail = new OrderDetail;
            $orderDetail->setProduct($this->getDoctrine()->getRepository(Product::class)->find($id));
            $orderDetail->setOrders($order);
            $orderDetail->setQuantity($request->get('quantity'));
            $order->addOrderDetail($orderDetail);
            $manager->persist($order);
            $manager->persist($orderDetail);
            $manager->flush();
        }
        return $this->render('product/list.html.twig',
        [
            'products' => $this->getDoctrine()->getRepository(Product::class)->findAll(),
        ]);
    }
}