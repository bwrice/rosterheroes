<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function assertArrayElementsEqual(array $arrayOne, array $arrayTwo)
    {
        sort($arrayOne);
        sort($arrayTwo);
        $this->assertEquals($arrayOne, $arrayTwo);
    }
}
