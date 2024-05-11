<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OfferController extends  AbstractController{

    /**
     * @Route("/admin/offer", name="select_edit")
     */
    public function editAction(Request $request)
    {
        $offerId = $request->request->get('Offres');
    
        return $this->redirectToRoute('offer_edit', ['id' => $offerId]);
    }
    

    /**
     * @Route("/admin/offer/{id}/edit", name="offer_edit")
     */
    public function edit($id, OfferRepository $offerRepository, Request $request, EntityManagerInterface $em){

        $offer = $offerRepository->find($id);

        //Formulaire dans le dossier src/Form/OfferType
        $form = $this ->createForm(OfferType::class, $offer);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $em->flush();

            return $this->redirect('/shop');
        }

        $formView = $form->createView();

        return $this->render('offer/edit.html.twig', [
            'offer' => $offer,
            'formView' => $formView
        ]);
    }



    /**
     * @Route("/admin/offer/create", name="offer_create")
     */
    public function create(Request $request, EntityManagerInterface $em){
        
        //Formulaire dans le dossier src/Form/OfferType
        $form = $this->createForm(OfferType::class);

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $offer = $form->getData();

            $em->persist($offer);
            $em->flush();
            
            return $this->redirect('/shop');
        }
    
        $formView = $form->createView();

        return $this->render('offer/create.html.twig', [
            'formView' => $formView
        ]);
    }

    /**
     * @Route("/admin/offer/delete", name="offer_delete")
     */
    public function delete(OfferRepository $offerRepository, EntityManagerInterface $em, Request $request): Response
    {
        $offerId = $request->request->get('Offres');

        $offer = $offerRepository->find($offerId);

        if (!$offer) {
            throw $this->createNotFoundException("L'offre n'existe pas");
        }

        $em->remove($offer);
        $em->flush();

        $this->addFlash('success', "L'offre a bien été supprimée");

        // Rediriger vers une page appropriée après la suppression, comme la liste des offres
        return $this->redirectToRoute('admin');
    }
}