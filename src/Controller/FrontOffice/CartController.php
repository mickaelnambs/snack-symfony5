<?php

namespace App\Controller\FrontOffice;

use App\Constant\MessageConstant;
use App\Form\CartConfirmationType;
use App\Repository\ItemsRepository;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CartController.
 * 
 * @Route("/cart")
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class CartController extends AbstractController
{
    /** @var ItemsRepository */
    protected ItemsRepository $itemRepository;

    /** @var CartService */
    protected CartService $cartService;

    /**
     * CartController constructor.
     *
     * @param ItemsRepository $itemRepository
     * @param CartService $cartService
     */
    public function __construct(ItemsRepository $itemRepository, CartService $cartService)
    {
        $this->itemRepository = $itemRepository;
        $this->cartService = $cartService;
    }

    /**
     * @Route("/add/{id}", name="cart_add", requirements={"id": "\d+"})
     * 
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function add(int $id, Request $request): Response
    {
        $item = $this->itemRepository->find($id);

        if (!$item) {
            throw $this->createNotFoundException("L'item $id n'existe pas !");
        }

        if ($id !== null) {
            $this->cartService->add($id);
            $this->addFlash(
                MessageConstant::SUCCESS_TYPE,
                "Le produit a bien été ajouté au panier"
            );
            return new JsonResponse(['success' => 1]);
        }
    }

    /**
     * @Route("/increment/{id}", name="cart_increment", requirements={"id": "\d+"})
     * 
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function increment(int $id, Request $request): Response
    {
        $item = $this->itemRepository->find($id);

        if (!$item) {
            throw $this->createNotFoundException("L'item $id n'existe pas !");
        }

        $this->cartService->add($id);
        $this->addFlash(
            MessageConstant::SUCCESS_TYPE,
            "Le produit a bien été ajouté au panier"
        );

        if ($request->query->get('returnToCart')) {
            return $this->redirectToRoute('cart_show');
        }

        return $this->redirectToRoute("item_show", [
            'slug' => $item->getSlug(),
            'id' => $item->getId()
        ]);
    }

    /**
     * @Route("/", name="cart_show", methods={"POST","GET"})
     *
     * @return void
     */
    public function show(): Response
    {
        $form = $this->createForm(CartConfirmationType::class);
        $detailedCard = $this->cartService->getDetailedCartItems();
        $total = $this->cartService->getTotal();

        return $this->render('front_office/cart/index.html.twig', [
            'products_cart' => $detailedCard,
            'total' => $total,
            'confirmationForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="cart_delete", requirements={"id": "\d+"})
     *
     * @param integer $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        $item = $this->itemRepository->find($id);

        if (!$item) {
            throw $this->createNotFoundException("L'item $id n'existe pas et ne peut pas être supprimé !");
        }

        $this->cartService->remove($id);
        $this->addFlash(
            MessageConstant::SUCCESS_TYPE,
            "L'item a bien été supprimé du panier"
        );

        return $this->redirectToRoute("cart_show");
    }

    /**
     * @Route("/decrement/{id}", name="cart_decrement", requirements={"id": "\d+"})
     *
     * @param integer $id
     * @return Response
     */
    public function decrement(int $id): Response
    {
        $item = $this->itemRepository->find($id);

        if (!$item) {
            throw $this->createNotFoundException("L'item $id n'existe pas et ne peut pas être décrémenté !");
        }

        $this->cartService->decrement($id);
        $this->addFlash(
            MessageConstant::SUCCESS_TYPE,
            "L'item a bien été décrémenté"
        );

        return $this->redirectToRoute("cart_show");
    }
}