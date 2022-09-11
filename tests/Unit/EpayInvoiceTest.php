<?php

namespace TheHocineSaad\LaravelChargilyEPay\Tests\Unit;

use Illuminate\Support\Facades\Schema;
use TheHocineSaad\LaravelChargilyEPay\Tests\TestCase;

class EpayInvoiceTest extends TestCase
{
    public function test_if_epay_invoices_table_is_created()
    {
        $this->assertTrue(Schema::hasTable('epay_invoices'));
    }

    public function test_if_epay_invoices_table_has_all_columns()
    {
        $this->assertTrue(Schema::hasColumns('epay_invoices', [
            'id',
            'client_name',
            'client_email',
            'amount',
            'discount',
            'mode',
            'description',
            'back_url',
            'checkout_url',
            'paid',
            'user_id',
        ]));
    }
}
