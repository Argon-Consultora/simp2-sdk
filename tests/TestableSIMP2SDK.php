<?php


namespace SIMP2\Tests;


use SIMP2\SDK\SIMP2SDK;
use SIMP2\SDK\Enums\LogLevel;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;

class TestableSIMP2SDK extends SIMP2SDK
{
    /**
     * @param string $endpoint
     * @param string $method
     * @param array|null $data
     * @return Response
     * @throws RequestException
     */
    public static function testMakeRequest(string $endpoint, string $method, ?array $data = null): Response
    {
        return self::makeRequest($endpoint, $method, $data);
    }

    public static function testShouldLog(LogLevel $logLevel, LogLevel $overwriteLogLevel): bool
    {
        return self::shouldLog($logLevel, $overwriteLogLevel);
    }

    public static function testFallbackShouldLog(LogLevel $logLevel, int $overwriteLogLevel): bool
    {
        return self::shouldLog($logLevel, $overwriteLogLevel);
    }


}
