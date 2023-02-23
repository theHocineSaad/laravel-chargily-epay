<?php

namespace TheHocineSaad\LaravelChargilyEPay\Traits;

use TheHocineSaad\LaravelChargilyEPay\Models\Epay_Invoice;

trait Epayable
{
    public function invoices()
    {
        return $this->hasMany(Epay_Invoice::class);
    }

    public function charge(array $configurations, $otherInvoiceFields = [])
    {
        $configurations['payment'] = [
            'client_name' => $this->name,
            'client_email' => $this->email,
        ] + $configurations['payment'];

        $configurations = $configurations + ['user_id' => $this->id];

        $redirectUrl = Epay_Invoice::make($configurations, $otherInvoiceFields);

        return $redirectUrl;
    }
}
