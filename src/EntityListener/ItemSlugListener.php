<?php

namespace App\EntityListener;

use App\Entity\Items;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class ItemSlugListener.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class ItemSlugListener
{
    /** @var SluggerInterface */
    private SluggerInterface $slugger;

    /**
     * ItemSlugListener constructor.
     *
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @param Items $item
     * @return void
     */
    public function prePersist(Items $item)
    {
        if (empty($item->getSlug())) {
            $item->setSlug($this->slugger->slug($item->getName())->lower());
        }
    }

}