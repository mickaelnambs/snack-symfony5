<?php

namespace App\Controller\FrontOffice\Purchase;

use App\Entity\Purchases;
use App\Stripe\StripeService;
use App\Repository\PurchasesRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class PurchasePayementController.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class PurchasePaymentController extends AbstractController
{
    /**
     * @Route("/purchase/pay/{id}", name="purchase_payment_form")
     * @IsGranted("ROLE_USER")
     */
    public function showCardForm($id, PurchasesRepository $purchasesRepository, StripeService $stripeService)
    {
        $purchase = $purchasesRepository->find($id);

        if (
            !$purchase ||
            ($purchase && $purchase->getUser() !== $this->getUser()) ||
            ($purchase && $purchase->getStatus() === Purchases::STATUS_PAID)
        ) {
            return $this->redirectToRoute('cart_show');
        }

        $intent = $stripeService->getPaymentIntent($purchase);

        return $this->render('front_office/purchase/payment.html.twig', [
            'clientSecret' => $intent->client_secret,
            'purchase' => $purchase,
            'stripePublicKey' => $stripeService->getPublicKey()
        ]);
    }
}