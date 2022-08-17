<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')]
#[Route('/category')]
class CategoryController extends AbstractController
{
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    #[Route('/admin', name: 'category_index')]
    public function index()
    {
        $categories = $this->categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }


    #[Route('/delete/{id}', name: 'category_delete')]
    public function delete($id) 
    {
        $category = $this->categoryRepository->find($id);
        return $this->render('category/index.html.twig', [
            'category' => $category,
        ]);
    }
    #[Route('/add', name: 'category_add')]
    public function add(Request $request) 
    {
        $categories = new Category;
        $form = $this->createForm(CategoryType::class, $categories);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($categories);
            $manager->flush();
            $this->addFlash('Info', 'Add category successfully !');
            return $this->redirectToRoute('category_index');
        }
        return $this->renderForm('category/add.html.twig', [
            'categoryForm' => $form,
        ]);
    }
    #[Route('/edit/{id}', name: 'category_edit')]
    public function edit(Request $request, $id) 
    {
        $categories =$this->categoryRepository->find($id);
        $form = $this->createForm(CategoryType::class, $categories);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($categories);
            $manager->flush();
            $this->addFlash('Info', 'Add product successfully !');
            return $this->redirectToRoute('product_index');
        }
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    #[Route('/list', name: 'category_list')]
    public function categorylist()
    {
        $categories = $this->categoryRepository->findAll();
        return $this->render('category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

}
