<?php

namespace App\Controller;

use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\PurchaseItem;

class AdminController extends  AbstractController
{

    /**
     * @Route("/admin", name="admin")
     */
    public function admin(OfferRepository $offerRepository, EntityManagerInterface $entityManager)
    {
        $offers = $offerRepository->findBy([], []);

        // Récupérer les ventes par offre avec la quantité vendue
    $salesByOffer = $entityManager->createQueryBuilder()
        ->select('pi.offerName, SUM(pi.quantity) as totalQuantity')
        ->from(PurchaseItem::class, 'pi')
        ->groupBy('pi.offerName')
        ->getQuery()
        ->getResult();

        
        return $this->render('admin.html.twig', [
            'offers' => $offers,
            'salesByOffer' => $salesByOffer
        ]);
    }
}