<?php

namespace Tests;

use App\Http\Controllers\ProductController;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function getProductController()
    {
        return new ProductController();
    }

    public function getRequest($data)
    {
        return new \Illuminate\Http\Request($data);
    }
}
