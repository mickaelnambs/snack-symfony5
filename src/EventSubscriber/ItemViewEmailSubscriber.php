<?php

namespace App\EventSubscriber;

use App\Event\ItemViewEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;

class ItemViewEmailSubscriber implements EventSubscriberInterface
{
    /** @var LoggerInterface */
    protected LoggerInterface $logger;

    /** @var MailerInterface */
    protected MailerInterface $mailer;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            'item.view' => 'sendMail',
        ];
    }

    public function sendMail(ItemViewEvent $itemViewEvent)
    {
        $this->logger->info("Email envoyé à l'admin pour le produit " . $itemViewEvent->getItem()->getId());
    }
}