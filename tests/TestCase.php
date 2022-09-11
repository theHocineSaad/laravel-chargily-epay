<?php

namespace TheHocineSaad\LaravelChargilyEPay\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Support\Facades\Schema;
use TheHocineSaad\LaravelChargilyEPay\LaravelChargilyEPayServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use InteractsWithViews;

    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelChargilyEPayServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        Schema::hasTable('wilayas') ?? Schema::drop('epay_invoices');

        $migration = include __DIR__.'/../database/migrations/2999_03_11_000000_create_invoices_table.php';
        $migration->up();
    }
}
