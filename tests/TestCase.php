<?php

namespace Linksderisar\Clay\Tests;


use Illuminate\Foundation\Testing\TestResponse;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * @param mixed $content
     * @param int $status
     * @param array $headers
     * @return TestResponse
     */
    protected function makeTestResponse($content = '', $status = 200, array $headers = []): TestResponse
    {
        return $this->createTestResponse(response($content, $status, $headers));
    }
}
