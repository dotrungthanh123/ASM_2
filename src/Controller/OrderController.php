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
    public function __construct(OrderRepository $orderRepository, CompletedRepository $completedRepository) {
        $this->orderRepository = $orderRepository;
        $this->completedRepository = $completedRepository;
    }

    #[Route('/list', name: 'order_list')]
    public function customerOrder() {
        $orders = $this->orderRepository->findAll();
        for ($i = 0; $i < count($orders); $i++) {
            if ($orders->getUser() != $this->getUser()) {
                array_splice($orders, $i, 1);
            }
        }
        return $this->render('order/list.html.twig',
        [
            'orders' => $orders,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/index', name: 'order_index')]
    public function index(): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $this->orders,
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
        $orders = $this->orderRepository->findAll();
        $con = false;
        $manager = $this->getDoctrine()->getManager();
        for ($i = 0; $i < count($orders); $i++) {
            if ($this->completedRepository->find($orders[$i]->getId()) == null && $orders[$i]->getUser() == $this->getUser()) {
                $con = true;
                $orderDetail = new OrderDetail;
                $orderDetail->setProduct($this->getDoctrine()->getRepository(Product::class)->find($id));
                $orderDetail->setOrderId($this->orderRepository->find($i));
                $orderDetail->setQuantity($request->get('quantity'));
                $orders[$i]->addOrderDetail($orderDetail);
                break;
            }
        }
        if (!$con) {
            $order = new Order;
            $order->setUser($this->getUser());
            $orderDetail = new OrderDetail;
            $orderDetail->setProduct($this->getDoctrine()->getRepository(Product::class)->find($id));
            $orderDetail->setOrderId($order);
            $orderDetail->setQuantity($request->get('quantity'));
            $order->addOrderDetail($orderDetail);
            $order->setCreatedTs(new \DateTime());
        }
        $manager->persist($order);
        $manager->persist($orderDetail);
        $manager->flush();
        return $this->render('product/list.html.twig',
        [
            'products' => $this->getDoctrine()->getRepository(Product::class)->findAll(),
        ]);
    }
}