<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

//    /**
//     * Setup the test environment.
//     *
//     * @return void
//     */
//    protected function setUp(): void
//    {
//        parent::setUp();
//
//        $this->artisan('migrate');
//        $this->artisan('db:seed');
//    }
//
//    /**
//     * Tear down the test environment.
//     *
//     * @return void
//     */
//    protected function tearDown(): void
//    {
//        $this->artisan('migrate:reset');
//
//        parent::tearDown();
//    }
}
