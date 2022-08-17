<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
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

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/index', name: 'product_index')]
    public function productIndex(){
        $products = $this->productRepository->findAll();
        return $this->render('product/index.html.twig',
        [
            'products' => $products
        ]);
    }

    #[Route('/list', name: 'product_list')]
    public function productList(ProductRepository $productRepository) {
        $products = $productRepository->findAll();
        return $this->render('product/list.html.twig', 
        [
            'products' => $products
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/delete/{id}', name: 'product_delete')]
    public function deleteProduct($id) {
        $product = $this->productRepository->find($id);
        if ($product == null) {
            $this->addFlash('Warning', 'Product not existed !');
        } else {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($product);
            $manager->flush();
            $this->addFlash('Info', 'Delete product successfully !');
        }
        return $this->redirectToRoute('product_index');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/edit/{id}', name: 'product_edit')]
    public function productEdit($id, Request $request) {
        $product = $this->productRepository->find($id);
        $form = $this->createForm(ProductType::class, $product);
        if ($product == null) {
            $this->addFlash('Warning', 'Product not exist !');
            return $this->redirectToRoute('product_index');
        } else {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($product);
                $manager->flush();
                $this->addFlash('Info', 'Edit product successfully !');
                $this->redirectToRoute('product_index');
            }
            return $this->renderForm('product/edit.html.twig',
            [
                'productForm' => $form,
            ]);
        }
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/add', name: 'product_add')]
    public function productAdd(Request $request) {
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($product);
            $manager->flush();
            $this->addFlash('Info', 'Add product successfully !');
            return $this->redirectToRoute('product_index');
        }
        
        return $this->renderForm('product/add.html.twig',
        [
            'productForm' => $form,
        ]);
    }
    
    #[Route('/detail/{id}', name: 'product_detail')]
    public function productDetail ($id) {
      $product = $this->productRepository->find($id);
      if ($product == null) {
          $this->addFlash('Warning', 'Cannot find product !');
          return $this->redirectToRoute('product_index');
      } else {
        return $this->render('product/detail.html.twig',
          [
              'product' => $product
          ]);
      }
    }

    // ----------------------------------------------------------------------
    // #[IsGranted("ROLE_CUSTOMER")]
    #[Route('/search', name: 'product_search')]
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
