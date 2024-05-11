<?php

namespace App\Controller;

use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends  AbstractController{

    /**
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/shop", name="shop")
     */
    public function shop(OfferRepository $offerRepository)
    { 
        $offers = $offerRepository->findBy([], []);

        return $this->render('shop.html.twig', [
            'offers' => $offers
        ]);
    }
}