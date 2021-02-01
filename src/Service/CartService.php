<?php

namespace App\Service;

use App\Service\CartItemService;
use App\Repository\ItemsRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class CartService.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class CartService
{
    /** @var SessionInterface */
    protected SessionInterface $session;

    /** @var ItemsRepository */
    protected ItemsRepository $itemRepository;

    public function __construct(SessionInterface $session, ItemsRepository $itemRepository)
    {
        $this->session = $session;
        $this->itemRepository = $itemRepository;
    }

    public function getCart(): array
    {
        return $this->session->get('cart', []);
    }

    public function saveCart(array $cart)
    {
        $this->session->set('cart', $cart);
    }

    public function empty()
    {
        $this->saveCart([]);
    }

    /**
     * Permet d'ajouter un item dans le panier
     *
     * @param integer $id
     * @return void
     */
    public function add(int $id)
    {
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        }

        $cart[$id]++;

        $this->saveCart($cart);
    }

    /**
     * Permet d'enlever un produit dans le panier.
     *
     * @param integer $id
     * @return void
     */
    public function remove(int $id)
    {
        $cart = $this->getCart();
        unset($cart[$id]);

        $this->saveCart($cart);
    }

    /**
     * Permet d'enlever un a un le produit dans le panier.
     *
     * @param integer $id
     * @return void
     */
    public function decrement(int $id)
    {
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            return;
        }

        if ($cart[$id] === 1) {
            $this->remove($id);
            return;
        }

        $cart[$id]--;

        $this->saveCart($cart);
    }

    /**
     * Permet d'avoir le prix total des items dans le panier.
     *
     * @return integer
     */
    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->getCart() as $id => $quantity) {
            $item = $this->itemRepository->find($id);

            if (!$item) {
                continue;
            }

            $total += $item->getPrice() * $quantity;
        }

        return $total;
    }

    /**
     * 
     * @return CartItemService[]
     */
    public function getDetailedCartItems(): array
    {
        $detailedCart = [];

        foreach ($this->getCart() as $id => $qty) {
            $item = $this->itemRepository->find($id);

            if (!$item) {
                continue;
            }

            $detailedCart[] = new CartItemService($item, $qty);
        }

        return $detailedCart;
    }
}