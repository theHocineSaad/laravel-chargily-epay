<?php

namespace TheHocineSaad\LaravelChargilyEPay\Tests\Unit;

use TheHocineSaad\LaravelChargilyEPay\Tests\TestCase;

class RoutesTest extends TestCase
{
    public function test_if_webhook_tester_get_route_exists_and_works()
    {
        $response = $this->call('GET', route('epay_webhook_tester.get'));

        $this->assertEquals(200, $response->status());
    }
}
