<?php

namespace App\EntityListener;

use App\Entity\Categories;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class CategorySlugListner.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class CategorySlugListener
{
    /** @var SluggerInterface */
    private SluggerInterface $slugger;

    /**
     * CategorySlugListener constructor.
     *
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @param Categories $categorie
     * @return void
     */
    public function prePersist(Categories $categorie)
    {
        if (empty($categorie->getSlug())) {
            $categorie->setSlug($this->slugger->slug($categorie->getTitle())->lower());
        }
    }
}