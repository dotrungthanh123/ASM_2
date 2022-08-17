<?php

namespace App\Controller;

use App\Entity\Manufacturer;
use App\Form\ManufacturerType;
use App\Repository\ManufacturerRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/manufacturer')]
class ManufacturerController extends AbstractController
{
    public function __construct(ManufacturerRepository $manufacturerRepository)
    {
        $this->manufacturerRepository = $manufacturerRepository;
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/index', name: 'manufacturer_index')]
    public function manufacturerIndex(){
        $manufacturers = $this->manufacturerRepository->findAll();
        return $this->render('manufacturer/index.html.twig',
        [
            'manufacturers' => $manufacturers
        ]);
    }

    #[Route('/list', name: 'manufacturer_list')]
    public function manufacturerList(ManufacturerRepository $manufacturerRepository){
        $manufacturers = $manufacturerRepository->findAll();
        return $this->render('manufacturer/list.html.twig', 
        [
            'manufacturers' => $manufacturers
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/delete/{id}', name: 'manufacturer_delete')]
    public function deleteManufacturer($id) {
        $manufacturer = $this->manufacturerRepository->find($id);
        if($manufacturer == null){
            $this->addFlash('Warning','Cannot find manufacturer !');
        }
        else{
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($manufacturer);
            $manager->flush();
            $this->addFlash('Info','Delete manufacturer successfully !');
        }
        return $this->redirectToRoute('manufacturer_index');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/edit/{id}', name: 'manufacturer_edit')]
    public function manufacturerEdit($id, Request $request) {
        $manufacturer = $this->manufacturerRepository->find($id);
        $form = $this->createForm(ManufacturerType::class, $manufacturer);
        if ($manufacturer == null) {
            $this->addFlash('Warning', 'Manufacturer not exist !');
            return $this->redirectToRoute('manufacturer_index');
        } else {
            $form = $this->createForm(ManufacturerType::class, $manufacturer);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($manufacturer);
                $manager->flush();
                $this->addFlash('Info', 'Edit manufacturer successfully !');
                return $this->redirectToRoute('manufacturer_index');
            }
            return $this->renderForm('manufacturer/edit.html.twig',
            [
                'manufacturerForm' => $form,
            ]);
        }
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/add', name: 'manufacturer_add')]
    public function manufacturerAdd(Request $request) {
        $manufacturer = new Manufacturer;
        $form = $this->createForm(ManufacturerType::class, $manufacturer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($manufacturer);
            $manager->flush();
            $this->addFlash('Info', 'Add manufacturer successfully !');
            return $this->redirectToRoute('manufacturer_index');
        }
        
        return $this->renderForm('manufacturer/add.html.twig',
        [
            'manufacturerForm' => $form,
        ]);
    }

    #[Route('/detail/{id}', name: 'manufacturer_detail')]
    public function manufacturerDetail ($id) {
      $manufacturer = $this->manufacturerRepository->find($id);
      if ($manufacturer == null) {
          $this->addFlash('Warning', 'Invalid manufacturer id !');
          return $this->redirectToRoute('manufacturer_index');
      }

      return $this->render('manufacturer/detail.html.twig',
          [
              'manufacturer' => $manufacturer
          ]);
    }
}