<?php

namespace App\Stripe;

use App\Entity\Purchases;

/**
 * Class StripeService.
 * 
 * @author Mickael Nambinintsoa <mickael.nambinintsoa07081999@gmail.com>
 */
class StripeService
{
    /** @var string */
    protected string $secretKey;

    /** @var string */
    protected string $publicKey;

    /**
     * StripeService constructor.
     *
     * @param string $secretKey
     * @param string $publicKey
     */
    public function __construct(string $secretKey, string $publicKey)
    {
        $this->secretKey = $secretKey;
        $this->publicKey = $publicKey;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function getPaymentIntent(Purchases $purchase)
    {
        \Stripe\Stripe::setApiKey($this->secretKey);

        return \Stripe\PaymentIntent::create([
            'amount' => $purchase->getTotal(),
            'currency' => 'eur'
        ]);
    }
}