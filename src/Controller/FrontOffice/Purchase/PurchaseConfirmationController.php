<?php

namespace App\Controller\FrontOffice\Purchase;

use App\Entity\Purchases;
use App\Service\CartService;
use App\Constant\MessageConstant;
use App\Form\CartConfirmationType;
use App\Purchase\PurchasePersister;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class PurchaseConfirmationController.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class PurchaseConfirmationController extends AbstractController
{
    /** @var EntityManagerInterface */
    protected EntityManagerInterface $em;

    /** @var PurchasePersister */
    protected PurchasePersister $persister;

    /** @var CartService */
    protected CartService $cartService;

    /**
     * PurchaseConfirmationController constructor.
     *
     * @param EntityManagerInterface $em
     * @param PurchasePersister $persister
     * @param CartService $cartService
     */
    public function __construct(
        EntityManagerInterface $em,
        PurchasePersister $persister,
        CartService $cartService
    ) {
        $this->em = $em;
        $this->persister = $persister;
        $this->cartService = $cartService;
    }

    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour confirmer une commande")
     */
    public function confirm(Request $request): Response
    {
        $form = $this->createForm(CartConfirmationType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            $this->addFlash(
                MessageConstant::WARNING_TYPE,
                "Vous devez remplir le formulaire de confirmation"
            );
            return $this->redirectToRoute('cart_show');
        }

        $cartItems = $this->cartService->getDetailedCartItems();

        if (count($cartItems) === 0) {
            $this->addFlash(
                MessageConstant::WARNING_TYPE,
                "Vous ne pouvez confirmer une commande avec un panier vide"
            );
            return $this->redirectToRoute('cart_show');
        }

        // 5. Nous allons créer une Purchase
        /** @var Purchases */
        $purchase = $form->getData();

        $this->persister->savePurchase($purchase);

        return $this->redirectToRoute('purchase_payment_form', [
            'id' => $purchase->getId()
        ]);
    }
}