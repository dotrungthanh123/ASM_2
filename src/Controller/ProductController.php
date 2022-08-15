<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/product')]
class ProductController extends AbstractController
{
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    // #[IsGranted("ROLE_ADMIN")]
    #[Route('/admin', name: 'product_index')]
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

    #[Route('/edit/{id}', name: 'product_edit')]
    public function productEdit(){

    }

    #[Route('/detail/{id}', name: 'product_detail')]
    public function productDetail ($id, ProductRepository $productRepository) {
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



    // ----------------------------------------------------------------------
    // #[IsGranted("ROLE_CUSTOMER")]
    #[Route('/search', name:'search_product')]
    public function searchBook(ProductRepository $productRepository, Request $request){
      $products = $productRepository->searchBook($request->get('keyword'));
      if ($products == null){
          $this->addFlash("Warning", "No product found !");
      }
      $session = $request->getSession();
      $session->set('search', true);
      return $this->render('product/list.html.twig',
      [
          'products' => $products,
      ]);
    }
}
