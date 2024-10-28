<?php

declare(strict_types=1);

namespace Hennest\Money\Tests;

use Hennest\Money\Providers\MoneyServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param Application $app
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        $app['config']->set('config.currency', 'usd');

        return [
            MoneyServiceProvider::class,
        ];
    }
}
