<?php

namespace App\Service;

use App\Entity\Items;

/**
 * Class CartItemService.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class CartItemService
{
    /** @var Items */
    public Items $item;

    /** @var int */
    public int $quantity;

    /**
     * CartItemService constructor.
     *
     * @param Items $item
     * @param integer $quantity
     */
    public function __construct(Items $item, int $quantity)
    {
        $this->item = $item;
        $this->quantity = $quantity;
    }

    public function getTotal(): int
    {
        return $this->item->getPrice() * $this->quantity;
    }
}