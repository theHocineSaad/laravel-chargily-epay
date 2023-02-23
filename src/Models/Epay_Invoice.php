<?php

namespace TheHocineSaad\LaravelChargilyEPay\Models;

use Chargily\ePay\Chargily;
use Illuminate\Database\Eloquent\Model;

class Epay_Invoice extends Model
{
    public $incrementing = false;

    protected $table = 'epay_invoices';

    protected $guarded = [];

    public static function make(array $configurations, $otherInvoiceFields = [])
    {
        $configurations = [
            'api_key' => config('laravel-chargily-epay.key'),
            'api_secret' => config('laravel-chargily-epay.secret'),

            'urls' => [
                'back_url' => config('laravel-chargily-epay.back_url'),
                'webhook_url' => config('laravel-chargily-epay.webhook_url'),
            ],
        ] + $configurations;

        $configurations['payment'] = [
            'number' => now()->format('ymdHis'),
        ] + $configurations['payment'];

        $invoice = new Chargily($configurations);

        $checkout_url = $invoice->getRedirectUrl();

        if ($checkout_url) {
            Epay_Invoice::create([
                'id' => $configurations['payment']['number'],
                'client_name' => $configurations['payment']['client_name'],
                'client_email' => $configurations['payment']['client_email'],
                'amount' => $configurations['payment']['amount'],
                'discount' => $configurations['payment']['discount'],
                'mode' => $configurations['mode'],
                'description' => $configurations['payment']['description'],
                'back_url' => $configurations['urls']['back_url'],
                'checkout_url' => $checkout_url,
                'user_id' => $configurations['user_id'] ?? null,
                ...$otherInvoiceFields
            ]);

            return $checkout_url;
        } else {
            return env('APP_URL');
        }
    }

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
