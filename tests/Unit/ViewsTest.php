<?php

namespace TheHocineSaad\LaravelChargilyEPay\Tests\Unit;

use TheHocineSaad\LaravelChargilyEPay\Tests\TestCase;

class ViewsTest extends TestCase
{
    public function test_if_webhook_tester_view_exists()
    {
        $view = $this->view('laravel-chargily-epay::epay_webhook.test');

        $this->assertNotNull($view);
    }
}
