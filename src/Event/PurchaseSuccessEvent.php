<?php

namespace App\Event;

use App\Entity\Purchases;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class PurchaseSuccessEvent.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class PurchaseSuccessEvent extends Event
{
    /** @var Purchases */
    protected Purchases $purchase;

    /**
     * PurchaseSuccessEvent constructor.
     *
     * @param Purchases $purchase
     */
    public function __construct(Purchases $purchase)
    {
        $this->purchase = $purchase;
    }

    /**
     * @return Purchases
     */
    public function getPurchase(): Purchases
    {
        return $this->purchase;
    }
}