<?php

namespace App\Controller\FrontOffice\Purchase;

use App\Entity\Purchases;
use App\Service\CartService;
use App\Event\PurchaseSuccessEvent;
use App\Repository\PurchasesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class PurchasePaymentSuccessController.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class PurchasePaymentSuccessController extends AbstractController
{

    /**
     * @Route("/purchase/terminate/{id}", name="purchase_payment_success")
     * @IsGranted("ROLE_USER")
     */
    public function success($id, PurchasesRepository $purchaseRepository, EntityManagerInterface $em, CartService $cartService, EventDispatcherInterface $dispatcher)
    {
        $purchase = $purchaseRepository->find($id);

        if (
            !$purchase ||
            ($purchase && $purchase->getUser() !== $this->getUser()) ||
            ($purchase && $purchase->getStatus() === Purchases::STATUS_PAID)
        ) {
            $this->addFlash('warning', "La commande n'existe pas");
            return $this->redirectToRoute("purchase_index");
        }

        $purchase->setStatus(Purchases::STATUS_PAID);
        $em->flush();

        $cartService->empty();

        $purchaseEvent = new PurchaseSuccessEvent($purchase);
        $dispatcher->dispatch($purchaseEvent, 'purchase.success');

        $this->addFlash('success', "La commande a été payée et confirmée !");
        return $this->redirectToRoute("purchase_index");
    }
}