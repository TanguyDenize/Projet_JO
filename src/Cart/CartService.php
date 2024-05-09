<?php

namespace App\Cart;

use App\Repository\OfferRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService {

    protected $session;
    protected $offerRepository;


    public function __construct(RequestStack $requestStack, OfferRepository $offerRepository)
    {
        $this->session = $requestStack->getSession();
        $this->offerRepository = $offerRepository;
    }

// Fonction ajouter une offre au panier
    public function add(int $id)
    {
        // Trouve le panier sous forme de tableau dans la session ou creer un tableau vide si il nexiste pas
        $cart = $this->session->get('cart', []);

        //Si le produit existe dans le tableau en rajouter 1 sinon augmenter la quantité de 1
        if(array_key_exists($id, $cart)){
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        //Enregistrer le panier pour qu'il persiste dans la sesssion
        $this->session->set('cart', $cart);

    }

    //Fonction supprimer une offre du panier
    public function remove(int $id)
    {
        $cart = $this->session->get('cart', []);

        unset($cart[$id]);

        $this->session->set('cart', $cart);
    }

    //Fonction décrémenté une offre dans le panier
    public function decrement(int $id)
    {
        $cart = $this->session->get('cart',[]);

        if(!array_key_exists($id, $cart)){
            return;
        }

        // Si la quantité est à 1 on supprime l'offre
        if($cart[$id] === 1) {
            $this->remove($id);
            return;
        }

        // Si la quantité est superieure à 1 on décrémente
        $cart[$id]--;

        $this->session->set('cart', $cart);
    }

    // Calcul du prix total pour chaque offre du panier
    public function getTotal()
    {
        $total = 0;

        foreach($this->session->get('cart', []) as $id => $qty) 
        {
            $offer = $this->offerRepository->find($id);

            if(!$offer) {
                continue;
            }

            $total += $offer->getPrice() * $qty;
        }

        return $total;
    }

    // Calcul du nombre total d'offres dans le panier
    public function getTotalItems()
    {
        $totalItems = 0;

        foreach($this->session->get('cart', []) as $id => $qty) 
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
        //ordonne le panier en plusieurs tableaux contenant les infos et la quantité de chaque offre
        $detailedCart = [];

        foreach($this->session->get('cart', []) as $id => $qty) 
        {
            $offer = $this->offerRepository->find($id);

            if(!$offer) {
                continue;
            }

            $detailedCart[] = new CartItem($offer, $qty);
        }

        return $detailedCart;
    }

    public function clearCart() : void
    {
        $this->session->remove('cart');
    }
}