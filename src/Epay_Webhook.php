<?php

namespace TheHocineSaad\LaravelChargilyEPay;

use Chargily\ePay\Chargily;

class Epay_Webhook
{
    public $invoice;

    public $invoiceIsPaied;

    public function __construct()
    {
        $this->handleWebhook();
    }

    public function handleWebhook()
    {
        $chargily = new Chargily([
            'api_key' => config('laravel-chargily-epay.key'),
            'api_secret' => config('laravel-chargily-epay.secret'),
        ]);

        if ($chargily->checkResponse()) {
            $response = $chargily->getResponseDetails();

            $this->invoice = $response['invoice'];

            if ($response['invoice']['status'] === 'paid') {
                $this->invoiceIsPaied = true;
            } elseif ($response['invoice']['status'] === 'failed') {
                $this->invoiceIsPaied = false;
            }
        }
    }
}
