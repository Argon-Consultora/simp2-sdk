<?php

namespace SIMP2\Tests\Unit;

use SIMP2\Tests\SDKTestCase;
use InvalidArgumentException;
use SIMP2\Tests\TestableSIMP2SDK;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;


class SIMP2ServiceTest extends SDKTestCase
{

    /** @test
     * @throws RequestException
     */
    public function ShouldThrowForUnknownHttpVerb()
    {
        Http::fake([self::getApiUrl('test') => Http::response([], 500, self::headers())]);
        $this->expectException(InvalidArgumentException::class);
        TestableSIMP2SDK::testMakeRequest('test', 'UnknownHttpVerb');
    }
}
