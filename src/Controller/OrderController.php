<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\OrderDetail;
use App\Repository\OrderRepository;
use App\Repository\CompletedRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/order')]
class OrderController extends AbstractController
{
    public function __construct(OrderRepository $orderRepository, CompletedRepository $completedRepository) {
        $this->orderRepository = $orderRepository;
        $this->$completedRepository = $completedRepository;
    }

    #[Route('/list', name: 'order_list')]
    public function customerOrder() {

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
    public function makeOrder($id) {
        $con = false;
        for ($i = 0; $i < count($this->orders); $i++) {
            if ($this->completedRepository->find($this->orders[$i]->getId()) == null && $this->orders[$i]->getUser()->getUsername() == $this->getUser()->getUsername()) {
                $con = true;
                $orderDetail = new OrderDetail;
                // $orderDetail->setProduct();
            }
        }
        if (!$con) {

        }

        
    }
}
