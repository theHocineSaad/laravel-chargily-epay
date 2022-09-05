<?php

namespace TheHocineSaad\LaravelChargilyEPay;

use Illuminate\Support\Facades\Facade;

/**
 * @see \TheHocineSaad\LaravelChargilyEPay\Skeleton\SkeletonClass
 */
class LaravelChargilyEPayFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-chargily-epay';
    }
}
