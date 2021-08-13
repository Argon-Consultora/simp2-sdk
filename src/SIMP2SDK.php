<?php

namespace SIMP2\SDK;

use BenSampo\Enum\Exceptions\InvalidEnumMemberException;
use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use SIMP2\SDK\DTO\Client;
use SIMP2\SDK\DTO\Debt;
use SIMP2\SDK\DTO\SubDebt;
use SIMP2\SDK\Enums\HttpStatusCode;
use SIMP2\SDK\Enums\HttpVerb;
use SIMP2\SDK\Enums\LogLevel;
use SIMP2\SDK\Enums\SIMP2Endpoint;
use SIMP2\SDK\Enums\TypeDescription;
use SIMP2\SDK\Exceptions\CreateMetadataException;
use SIMP2\SDK\Exceptions\PaymentAlreadyNotifiedException;
use SIMP2\SDK\Exceptions\PaymentNotFoundException;
use SIMP2\SDK\Exceptions\ReversePaymentException;
use SIMP2\SDK\Exceptions\SavePaymentException;
use SIMP2\SDK\Exceptions\SIMP2Exception;

class SIMP2SDK
{
    private ?string $companyTransactionToken = null;

    /**
     * @throws RequestException
     */
    protected function makeRequest(
        string $endpoint,
        string $method,
        ?array $data = null
    ): Response {
        $headers = [
            'X-API-KEY' => config('simp2.api_key'),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        if ($this->companyTransactionToken) {
            $headers['company-transaction-token'] = $this->companyTransactionToken;
        }

        $base = Http::withHeaders($headers);

        $endpoint = config('simp2.api_url') . $endpoint;

        $response = match ($method) {
            HttpVerb::GET => $base->get($endpoint, $data),
            HttpVerb::POST => $base->post($endpoint, $data ?? []),
            default => throw new InvalidArgumentException('The http verb in makeRequest is invalid.'),
        };

        $response->throw();

        return $response;
    }

    public function setCompanyTransactionToken(string $cct)
    {
        $this->companyTransactionToken = $cct;
    }

    protected static function shouldLog(LogLevel $logLevel, $overwriteLogLevel = null): bool
    {
        try {
            if (env('APP_ENV') === 'testing' && !$overwriteLogLevel) return false;
            if (is_int($overwriteLogLevel)) $overwriteLogLevel = LogLevel::fromValue($overwriteLogLevel);
            $configuredLogLevel = $overwriteLogLevel ?? new LogLevel(env('SIMP2_LOG_LEVEL', LogLevel::Debug));
            return $logLevel->value >= $configuredLogLevel->value;
        } catch (InvalidEnumMemberException) {
            // Defaults to debug in case of misconfiguration.
            return $logLevel->value >= LogLevel::Debug;
        }
    }

    private function fireEvent(
        string          $unique_reference,
        string          $observations,
        ?string         $category,
        TypeDescription $type_description,
        SIMP2Endpoint   $endpoint
    ) {
        $body = [
            "unique_reference" => $unique_reference,
            "integration" => "conector_pagofacil",
            "type_description" => $type_description,
            "event_date" => Carbon::now()->toDateTimeString(),
            "category" => $category,
            "observations" => $observations
        ];

        try {
            $this->makeRequest((string)$endpoint, 'POST', $body);
        } catch (RequestException $e) {
            Log::critical('No se pudo procesar un evento.', ['error' => $e->getMessage()]);
        }
    }

    public static function infoEvent(
        string          $unique_reference,
        string          $observations,
        ?string         $category,
        TypeDescription $type_description,
        LogLevel        $logLevel,
        int             $overwriteLogLevel = null
    ) {
        if (!self::shouldLog($logLevel, $overwriteLogLevel)) return;
        (new SIMP2SDK)->fireEvent($unique_reference, $observations, $category, $type_description, SIMP2Endpoint::logInfoEndpoint());
    }

    public static function errorEvent(
        string          $unique_reference,
        string          $observations,
        ?string         $category,
        TypeDescription $type_description,
        LogLevel        $logLevel,
        int             $overwriteLogLevel = null
    ) {
        if (!self::shouldLog($logLevel, $overwriteLogLevel)) return;
        (new SIMP2SDK)->fireEvent($unique_reference, $observations, $category, $type_description, SIMP2Endpoint::logErrorEndpoint());
    }

    /**
     * @throws PaymentNotFoundException
     * @throws SavePaymentException
     * @throws PaymentAlreadyNotifiedException
     */
    public function notifyPayment(string $unique_reference, string $date = null): void
    {
        try {
            self::infoEvent($unique_reference, 'Se notificó un pago', null, TypeDescription::PaymentConfirmation(), LogLevel::Info());

            $body = [
                'unique_reference' => $unique_reference,
                'date' => $date ?? Carbon::now()->toDateTimeString()
            ];
            $this->makeRequest(SIMP2Endpoint::notifyPaymentEndpoint, 'POST', $body);
        } catch (RequestException $e) {
            $this->errorEvent($unique_reference, 'No se pudo notificar el pago al SIMP2 - ' . $e->response->status(), null, TypeDescription::PaymentConfirmationError(), LogLevel::Error());
            if ($e->response->status() == HttpStatusCode::NotFound) {
                throw new PaymentNotFoundException();
            }
            if ($e->response->status() == HttpStatusCode::Conflict) {
                throw new PaymentAlreadyNotifiedException();
            }
            if ($e->response->status() == HttpStatusCode::UnprocessableEntity) {
                throw new SavePaymentException('Invalid request body');
            }

            throw new SavePaymentException($e->getMessage());
        }
    }

    /**
     * @throws PaymentNotFoundException
     * @throws SavePaymentException
     */
    public function confirmPayment(string $unique_reference, string $date = null): Response
    {
        try {
            self::infoEvent($unique_reference, 'Se confirmó un pago', null, TypeDescription::PaymentConfirmation(), LogLevel::Info());

            $body = [
                'unique_reference' => $unique_reference,
                'date' => $date ?? Carbon::now()->toDateTimeString()
            ];
            return $this->makeRequest(SIMP2Endpoint::confirmPaymentEndpoint, 'POST', $body);
        } catch (RequestException $e) {
            $this->errorEvent($unique_reference, 'No se pudo confirmar el pago al SIMP2', TypeDescription::SavePaymentError, TypeDescription::SavePaymentError(), LogLevel::Error());

            if ($e->response->status() == HttpStatusCode::NotFound) {
                throw new PaymentNotFoundException();
            }
            if ($e->response->status() == HttpStatusCode::UnprocessableEntity) {

                throw new SavePaymentException('Invalid request body');
            }
            throw new SavePaymentException($e->getMessage());
        }
    }

    /**
     * @throws PaymentNotFoundException
     * @throws ReversePaymentException
     */
    public function notifyRollbackPayment(string $unique_reference, string $date = null): Response
    {
        try {
            $body = [
                'unique_reference' => $unique_reference,
                'date' => $date ?? Carbon::now()->toDateTimeString()
            ];

            self::infoEvent($unique_reference, 'Se notificó la reversa', null, TypeDescription::RollbackNotification(), LogLevel::Info());
            return $this->makeRequest(SIMP2Endpoint::notifyRollbackEndpoint, 'POST', $body);
        } catch (RequestException $e) {
            $this->errorEvent($unique_reference, 'No se pudo notificar la reversa al SIMP2', null, TypeDescription::RollbackError(), LogLevel::Critical());

            if ($e->response->status() == HttpStatusCode::NotFound) {
                throw new PaymentNotFoundException();
            }
            if ($e->response->status() == HttpStatusCode::UnprocessableEntity) {
                throw new ReversePaymentException('Invalid request body');
            }
            throw new ReversePaymentException('Internal SIMP2 Error ' . $e->getMessage());
        }
    }

    /**
     * @param string      $unique_reference
     * @param string|null $date
     * @return Response // Just for testing
     * @throws PaymentNotFoundException
     * @throws ReversePaymentException
     */
    public function confirmRollbackPayment(string $unique_reference, string $date = null): Response
    {
        try {
            self::infoEvent($unique_reference, 'Se confirmo una reversa', null, TypeDescription::RollbackConfirmation(), LogLevel::Info());
            $body = [
                'unique_reference' => $unique_reference,
                'date' => $date ?? Carbon::now()->toDateTimeString()
            ];

            return $this->makeRequest(SIMP2Endpoint::confirmRollbackEndpoint, 'POST', $body);
        } catch (RequestException $e) {
            $this->errorEvent($unique_reference, 'No se pudo confirmar la reversa al SIMP2', "confirm_rollback_error", TypeDescription::RollbackError(), LogLevel::Critical());
            if ($e->response->status() == HttpStatusCode::NotFound) {
                throw new PaymentNotFoundException();
            }
            if ($e->response->status() == HttpStatusCode::UnprocessableEntity) {
                throw new ReversePaymentException('Invalid request body');
            }

            throw new ReversePaymentException('Internal SIMP2 Error ' . $e->getMessage());
        }
    }

    /**
     * @throws CreateMetadataException
     */
    public function createMetadata(string $key, mixed $value): void
    {
        try {
            $this->makeRequest(SIMP2Endpoint::metadataEndpoint, 'POST', ['key' => $key, 'value' => $value]);
        } catch (RequestException $e) {
            Log::alert('No se pudo guardar la metadata', ['message' => $e->getMessage(), 'key' => $key, 'value' => $value]);
            throw new CreateMetadataException($e->getMessage());
        }
    }

    public function getMetadata(string $key): mixed
    {
        try {
            $res = $this->makeRequest(SIMP2Endpoint::metadataEndpoint . "/" . $key, 'GET');
            return $res->object()[0]->value;
        } catch (RequestException) {
            return null;
        }
    }

    /**
     * @param $wildcard
     * @return Debt[]
     * @throws PaymentNotFoundException
     * @throws SIMP2Exception
     */
    public function getDebts($wildcard): array
    {
        try {
            $res = $this->makeRequest(SIMP2Endpoint::debtGeneralEndpoint . $wildcard, 'GET');
            return array_map(function ($rawDebt) {
                return $this->buildDebtFromResponse($rawDebt);
            }, $res->json());
        } catch (RequestException $e) {
            if ($e->response->status() == HttpStatusCode::NotFound) {
                self::errorEvent($wildcard, 'Se busco una deuda inexistente (wildcard mode)', null, TypeDescription::DebtError(), LogLevel::Debug());
                throw new PaymentNotFoundException();
            }

            self::errorEvent($wildcard, 'No se pudo obtener la deuda del simp2 via wildcard', null, TypeDescription::DebtError(), LogLevel::Info());
            throw new SIMP2Exception($e->getMessage());
        }
    }

    public function getDebtInfo($code): ?Debt
    {
        try {
            $res = $this->makeRequest(SIMP2Endpoint::debtEndpoint . "/" . $code, 'GET');
            $debtRaw = $res->json()[0];
            return self::buildDebtFromResponse($debtRaw);
        } catch (RequestException) {
            return null;
        }
    }

    /**
     * @param string $unique_reference
     * @return Debt
     * @throws PaymentNotFoundException
     * @throws SIMP2Exception
     */
    public function getSubdebt(string $unique_reference): Debt
    {
        try {
            $res = $this->makeRequest(SIMP2Endpoint::debtUniqueEndpoint . $unique_reference, 'GET');
            return self::buildDebtFromResponse($res->json()[0]);
        } catch (RequestException $e) {
            if ($e->response->status() == HttpStatusCode::NotFound) {
                self::errorEvent($unique_reference, 'Se busco una deuda inexistente', null, TypeDescription::DebtError(), LogLevel::Debug());
                throw new PaymentNotFoundException();
            }

            self::errorEvent($unique_reference, 'No se pudo obtener la sub deuda del simp2', null, TypeDescription::DebtError(), LogLevel::Info());
            throw new SIMP2Exception($e->getMessage());
        }
    }

    /**
     * @param string $barcode
     * @return Debt
     * @throws PaymentNotFoundException
     * @throws SIMP2Exception
     */
    public function getSubdebtByBarcode(string $barcode): Debt
    {
        try {
            $res = $this->makeRequest(SIMP2Endpoint::debtBarcodeEndpoint . $barcode, 'GET');
            return $this->buildDebtFromResponse($res->json()[0]);
        } catch (RequestException $e) {
            if (
                $e->response->status() == HttpStatusCode::NotFound ||
                $e->response->status() == HttpStatusCode::Conflict) {
                throw new PaymentNotFoundException();
            }
            throw new SIMP2Exception($e->getMessage());
        }
    }

    private static function buildDebtFromResponse(array $response): Debt
    {
        $debt = new Debt();
        $debt->setCode($response['code']);
        $debt->setClientId($response['ccf_client_id']);
        $debt->setClientName($response['ccf_client_data']['first_name'] . " " . $response['ccf_client_data']['last_name']);
        $debt->setClientFirstName($response['ccf_client_data']['first_name']);
        $debt->setClientLastName($response['ccf_client_data']['last_name']);
        $debt->setExtra($response['ccf_client_data']['extra'] ?? null);
        $subdebts = array_map(function ($rawSubDebt) {
            $subdebt = new SubDebt();
            $subdebt->setAmount($rawSubDebt['amount']);
            $subdebt->setUniqueReference($rawSubDebt['unique_reference']);
            $subdebt->setDueDate($rawSubDebt['due_date']);
            $subdebt->setTexts($rawSubDebt['texts'][0] ?? ["Debt #{$rawSubDebt['unique_reference']}"]);
            $subdebt->setBarCode($rawSubDebt['barcode']);
            $subdebt->setExpired($rawSubDebt['expired']);
            $subdebt->setStatus($rawSubDebt['status']);
            return $subdebt;
        }, $response['subdebts']);
        $debt->setSubdebts($subdebts);

        return $debt;
    }

    /**
     * @param Debt[] $debts
     * @return Client
     */
    public function getClientData(array $debts): Client
    {
        $debt = $debts[0];
        return (new Client())
            ->setClientName($debt->getClientFirstName(), $debt->getClientLastName())
            ->setClientId($debt->getClientId())
            ->setExtra($debt->getExtra());
    }
}
