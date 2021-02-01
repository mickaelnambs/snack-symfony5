<?php

namespace App\Purchase;

use App\Entity\PurchaseItems;
use App\Entity\Purchases;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class PurchasePersister.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class PurchasePersister
{
    /** @var Security */
    protected Security $security;

    /** @var CartService */
    protected CartService $cartService;

    /** @var EntityManagerInterface */
    protected EntityManagerInterface $em;

    /**
     * PurchasePersister constructor.
     *
     * @param Security $security
     * @param CartService $cartService
     * @param EntityManagerInterface $em
     */
    public function __construct(Security $security, CartService $cartService, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->cartService = $cartService;
        $this->em = $em;
    }

    /**
     * @param Purchases $purchase
     * @return void
     */
    public function savePurchase(Purchases $purchase)
    {
        $purchase->setUser($this->security->getUser());

        $this->em->persist($purchase);

        // Nous allons la lier avec les produits qui sont dans le panier (CartService).
        foreach ($this->cartService->getDetailedCartItems() as $cartItem) {
            $purchaseItem = new PurchaseItems();
            $purchaseItem->setPurchase($purchase)
                ->setItem($cartItem->item)
                ->setProductName($cartItem->item->getName())
                ->setQuantity($cartItem->quantity)
                ->setTotal($cartItem->getTotal())
                ->setProductPrice($cartItem->item->getPrice());

            $this->em->persist($purchaseItem);
        }

        $this->em->flush();
    }
}