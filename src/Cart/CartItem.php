<?php

namespace App\Cart;

use App\Entity\Offer;

class CartItem {
    public $offer;
    public $qty;

    public function __construct(Offer $offer, int $qty)
    {
        $this->offer = $offer;
        $this->qty = $qty;
    }

    public function getTotal(): int
    {
        return $this->offer->getPrice() * $this->qty;
    }
}
