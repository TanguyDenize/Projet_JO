<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class PurchaseConfirmationController extends AbstractController
{
    protected $security;
    protected $cartService;
    protected $router;
    protected $em;
    
    public function __construct(Security $security, CartService $cartService, RouterInterface $router, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->cartService = $cartService;
        $this->router = $router;
        $this->em = $em;
    }

    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     */
    public function confirm()
        {
            //sécurité
            $user = $this->security->getUser();
            if(!$user) {
                throw new AccessDeniedException("Vous devez être connecté");
            }

            //sécurité
            $cartItems = $this->cartService->getDetailedCartItems();
            if(count($cartItems) === 0) {
                $this->addFlash('warning', 'veuillez ajouter une offre à votre panier avant de le confirmer');
                return new RedirectResponse($this->router->generate('cart_show'));
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
                ->setTotal($this->cartService->getTotal());

            $this->em->persist($purchase);

            // Lien avec les produits du panier
            foreach($this->cartService->getDetailedCartItems() as $cartItem) {
                $purchaseItem = new PurchaseItem;
                $purchaseItem->setPurchase($purchase)
                    ->setOffer($cartItem->offer)
                    ->setOfferName($cartItem->offer->getName())
                    ->setQuantity($cartItem->qty)
                    ->setOfferPrice($cartItem->offer->getPrice())
                    ->setTotal($cartItem->getTotal());

                $this->em->persist($purchaseItem);
            }


            $this->em->flush();

            $this->cartService->clearCart();

            $this->addFlash('success', "la commande a bien été enregistrée");
            return new RedirectResponse($this->router->generate('purchase_index'));

        }
    
}