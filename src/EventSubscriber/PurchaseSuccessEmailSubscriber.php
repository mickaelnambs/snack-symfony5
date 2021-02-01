<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use App\Event\PurchaseSuccessEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Security;

/**
 * Class PurchaseSuccessEmailSubscriber.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{
    /** @var LoggerInterface */
    protected LoggerInterface $logger;

    /** @var MailerInterface */
    protected MailerInterface $mailer;

    /** @var Security */
    protected Security $security;

    /**
     * PurchaseSuccessEmailSubscriber constructor.
     *
     * @param LoggerInterface $logger
     * @param MailerInterface $mailer
     * @param Security $security
     */
    public function __construct(LoggerInterface $logger, MailerInterface $mailer, Security $security)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            'purchase.success' => 'sendSuccessEmail',
        ];
    }

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent)
    {
        $currentUser = $this->security->getUser();
        $purchase = $purchaseSuccessEvent->getPurchase();

        $email = new TemplatedEmail();
        $email->to(new Address($currentUser->getEmail(), $currentUser->getFullName()))
            ->from("admin@email.com")
            ->subject("Bravo, votre commande ({$purchase->getId()}) a bien été confirmée")
            ->htmlTemplate('front_office/email/purchase_success.html.twig')
            ->context([
                'purchase' => $purchase,
                'user' => $currentUser
            ]);

        $this->mailer->send($email);
        $this->logger->info("Email envoyé pour la commande n° " . $purchaseSuccessEvent->getPurchase()->getId());
    }
}