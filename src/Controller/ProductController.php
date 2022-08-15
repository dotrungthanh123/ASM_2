<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/admin', name: 'admin_product')]
    public function adminProduct(ProductRepository $productRepository){
        $products = $productRepository;
        return $this->render('product/index.html.twig',
        [
            'products' => $products
        ]);
    }

    #[Route('/list', name: 'product_list')]
    public function bookList(ProductRepository $productRepository){
        $products = $productRepository->findAll();
        return $this->render('product/list.html.twig', 
        [
            'products' => $products
        ]);
    }

    #[Route('/detail/{id}', name: 'productdetail')]
    public function bookDetail ($id, ProductRepository $productRepository) {
      $products = $productRepository->find($id);
      if ($products == null) {
          $this->addFlash('Warning', 'Invalid product id !');
          return $this->redirectToRoute('admin_product');
      }
      return $this->render('product/detail.html.twig',
          [
              'products' => $products
          ]);
    }

    
}
