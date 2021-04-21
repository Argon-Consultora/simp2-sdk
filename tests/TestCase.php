<?php


namespace SIMP2\Tests;


class TestCase extends  \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            'SIMP2\SDK\SIMP2ServiceProvider',
        ];
    }
}
