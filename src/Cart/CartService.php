<?php

namespace App\Cart;

use App\Repository\OfferRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService {

    protected $requestStack;  // Modifié : Injection de RequestStack
    protected $offerRepository;

    public function __construct(RequestStack $requestStack, OfferRepository $offerRepository)
    {
        $this->requestStack = $requestStack;  // Modifié : Assignation de RequestStack
        $this->offerRepository = $offerRepository;
    }

    private function getSession()  // Ajouté : Méthode pour obtenir la session
    {
        return $this->requestStack->getSession();
    }

    public function add(int $id)
    {
        $session = $this->getSession();  // Modifié : Utilisation de getSession
        $cart = $session->get('cart', []);

        if(array_key_exists($id, $cart)){
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);
    }

    public function remove(int $id)
    {
        $session = $this->getSession();  // Modifié : Utilisation de getSession
        $cart = $session->get('cart', []);

        unset($cart[$id]);

        $session->set('cart', $cart);
    }

    public function decrement(int $id)
    {
        $session = $this->getSession();  // Modifié : Utilisation de getSession
        $cart = $session->get('cart', []);

        if(!array_key_exists($id, $cart)){
            return;
        }

        if($cart[$id] === 1) {
            $this->remove($id);
            return;
        }

        $cart[$id]--;

        $session->set('cart', $cart);
    }

    public function getTotal()
    {
        $session = $this->getSession();  // Modifié : Utilisation de getSession
        $total = 0;

        foreach($session->get('cart', []) as $id => $qty) 
        {
            $offer = $this->offerRepository->find($id);

            if(!$offer) {
                continue;
            }

            $total += $offer->getPrice() * $qty;
        }

        return $total;
    }

    public function getTotalItems()
    {
        $session = $this->getSession();  // Modifié : Utilisation de getSession
        $totalItems = 0;

        foreach($session->get('cart', []) as $id => $qty) 
        {
            $offer = $this->offerRepository->find($id);

            if(!$offer) {
                continue;
            }

            $totalItems += $qty;
        }

        return $totalItems;
    }

    /**
     *  @return CartItem[]
     */
    public function getDetailedCartItems(): array 
    {
        $session = $this->getSession();  // Modifié : Utilisation de getSession
        $detailedCart = [];

        foreach($session->get('cart', []) as $id => $qty) 
        {
            $offer = $this->offerRepository->find($id);

            if(!$offer) {
                continue;
            }

            $detailedCart[] = new CartItem($offer, $qty);
        }

        return $detailedCart;
    }

    public function clearCart(): void
    {
        $session = $this->getSession();  // Modifié : Utilisation de getSession
        $session->remove('cart');
    }
}
