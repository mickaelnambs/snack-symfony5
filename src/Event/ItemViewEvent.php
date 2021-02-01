<?php

namespace App\Event;

use App\Entity\Items;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class ItemViewEvent.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class ItemViewEvent extends Event
{
    /** @var Items */
    protected Items $item;

    /**
     * ItemViewEvent constructor.
     *
     * @param Items $item
     */
    public function __construct(Items $item)
    {
        $this->item = $item;
    }

    /**
     * @return Items
     */
    public function getItem(): Items
    {
        return $this->item;
    }
}