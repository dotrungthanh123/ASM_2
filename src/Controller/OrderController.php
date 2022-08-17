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
        $this->getDoctrine()->getManager()->persist($order);
        $this->getDoctrine()->getManager()->flush();
        return $this->render('product/list.html.twig', 
        [
            'products' => $this->getDoctrine()->getRepository(Product::class)->findAll(),
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
        $this->getDoctrine()->getRepository(Product::class)->find($id)->setQuantity($this->getDoctrine()->getRepository(Product::class)->find($id)->getQuantity() - $request->get('quantity'));
        return $this->render('product/list.html.twig',
        [
            'products' => $this->getDoctrine()->getRepository(Product::class)->findAll(),
        ]);
    }
}