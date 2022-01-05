<?php

namespace SIMP2\Tests\Unit;

use Illuminate\Support\Facades\Http;
use SIMP2\SDK\Enums\SIMP2Endpoint;
use SIMP2\SDK\Exceptions\SecretKeyAlreadyExistsException;
use SIMP2\SDK\Exceptions\SecretNotFoundException;
use SIMP2\SDK\Exceptions\SIMP2Exception;
use SIMP2\SDK\SIMP2SDK;
use SIMP2\Tests\SDKTestCase;

class SecretTest extends SDKTestCase
{

    /** @test */
    public function ShouldCreateSecret()
    {
        try {
            Http::fake([self::getApiUrl(SIMP2Endpoint::secretEndpoint) => Http::response(["message" => "Secret created."], 201, self::headers())]);
            (new SIMP2SDK)->createSecret('test', 'test');
            $this->assertTrue(true);
        } catch (SIMP2Exception|SecretKeyAlreadyExistsException) {
            $this->fail('Should not throw.');
        }
    }

    /** @test */
    public function ShouldRetrieveSecret()
    {
        Http::fake([self::getApiUrl(SIMP2Endpoint::secretEndpoint . "/*") => Http::response(['secret' => 'test'], 200, self::headers())]);
        try {
            $value = (new SIMP2SDK)->getSecret('test');
            $this->assertEquals('test', $value);
        } catch (SIMP2Exception|SecretNotFoundException) {
            $this->fail('Should not throw.');
        }
    }

    /** @test */
    public function ShouldThrowWhenNotFoundSecret()
    {
        Http::fake([self::getApiUrl(SIMP2Endpoint::secretEndpoint . "/*") => Http::response([], 404, self::headers())]);
        $this->expectException(SecretNotFoundException::class);
        try {
            (new SIMP2SDK)->getSecret('test');
        } catch (SIMP2Exception) {
            $this->fail('Should not throw.');
        }
    }

    /** @test */
    public function ShouldThrowIfConnectionError()
    {
        Http::fake([self::getApiUrl(SIMP2Endpoint::secretEndpoint) => Http::response([], 500, self::headers())]);
        $this->expectException(SIMP2Exception::class);

        try {
            (new SIMP2SDK)->createSecret('test', 'test');
        } catch (SecretKeyAlreadyExistsException) {
            $this->fail('Should not throw.');
        }
    }

    /** @test */
    public function ShouldThrowIfSecretKeyExists()
    {
        Http::fake([self::getApiUrl(SIMP2Endpoint::secretEndpoint) => Http::response([], 409, self::headers())]);
        $this->expectException(SecretKeyAlreadyExistsException::class);

        try {
            (new SIMP2SDK)->createSecret('test', 'test');
        } catch (SIMP2Exception) {
            $this->fail('Should not throw.');
        }
    }
}
