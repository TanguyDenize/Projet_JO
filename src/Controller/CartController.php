<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id":"\d+"})
     */
    public function add($id, OfferRepository $offerRepository, CartService $cartService, Request $request)
    {
        //Verifie si le produit existe
        $offer = $offerRepository->find($id);
        if(!$offer) {
            throw $this->createNotFoundException("Le produit n'existe pas");
        }

        $cartService->add($id);

        $this->addFlash('success', "L'offre a bien été ajouté au panier");

        if($request->query->get('returnToCart')) {
            return $this->redirectToRoute("cart_show");
        }

        return $this->redirectToRoute('shop');
    }

    /**
     * @Route("/cart", name="cart_show")
     */
    public function show(CartService $cartService){
        
        $detailedCart = $cartService->getDetailedCartItems();

        $total = $cartService->getTotal();

        return $this->render('cart/index.html.twig', [
            'items' => $detailedCart,
            'total' => $total
        ]); 
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete", requirements={"id": "\d+"})
     */
    public function delete($id, OfferRepository $offerRepository, CartService $cartService)
    {

        $cartService->remove($id);

        $this->addFlash("success", "L'offre a bien été supprimé du panier");

        return $this->redirectToRoute("cart_show");
    }

    /**
     * @Route("/cart/decrement/{id}", name="cart_decrement", requirements={"id": "\d+"})
     */
    public function decrement($id, CartService $cartService)
    {
        $cartService->decrement($id);

        return $this->redirectToRoute("cart_show");
    }
}
