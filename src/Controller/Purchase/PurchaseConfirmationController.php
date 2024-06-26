<?php

namespace App\Controller\Purchase;

use App\Entity\Ticket;
use App\Entity\Purchase;
use App\Cart\CartService;
use App\Entity\PurchaseItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseConfirmationController extends AbstractController
{
    protected $cartService;
    protected $router;
    protected $em;
    
    public function __construct(CartService $cartService, RouterInterface $router, EntityManagerInterface $em)
    {
        $this->cartService = $cartService;
        $this->router = $router;
        $this->em = $em;
    }

    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     */
    public function confirm()
        {
            $user = $this->getUser();

            //sécurité
            $cartItems = $this->cartService->getDetailedCartItems();
            if(count($cartItems) === 0) {
                $this->addFlash('warning', 'veuillez ajouter une offre à votre panier avant de le confirmer');
                return $this->redirectToRoute('cart_show');
            }

            //Création d'une commande
            /** @var Purchase */
            $purchase = new Purchase();

            //Lien avec l'utilisateur connecté
            /** @var \App\Entity\User $user */
            $purchase->setUser($user)
                ->setFullName($user->getFullName())
                ->setEmail($user->getEmail())
                ->setPurchasedAt(new \DateTimeImmutable())
                ->setTotal($this->cartService->getTotal())
                ->setStatus(Purchase::STATUS_PAID);

            $this->em->persist($purchase);

            // Lien avec les produits du panier
            foreach($this->cartService->getDetailedCartItems() as $cartItem) {
                for ($i = 0; $i < $cartItem->qty; $i++) {
                    $purchaseItem = new PurchaseItem;
                    $purchaseItem->setPurchase($purchase)
                        ->setOffer($cartItem->offer)
                        ->setOfferName($cartItem->offer->getName())
                        ->setQuantity($cartItem->qty)
                        ->setOfferPrice($cartItem->offer->getPrice())
                        ->setTotal($cartItem->getTotal());
                    
                    /** @var Ticket */
                    $ticket = new Ticket($user);
                    $ticket->setOffer($cartItem->offer) // Associer le ticket à l'offre du panier
                        ->setPurchase($purchase)
                        ->setOfferName($cartItem->offer->getName())
                        ->setFullName($user->getFullName());
                    
                    $this->em->persist($purchaseItem);
                    $this->em->persist($ticket); // Enregistrer le ticket dans la base de données
                }
        
            }

            $this->em->flush();

            $this->cartService->clearCart();

            $this->addFlash('success', "la commande a bien été payée et enregistrée");

            return $this->redirectToRoute("purchase_index");
        }
    
}