<?php

namespace SIMP2\SDK;

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
use SIMP2\SDK\Enums\SIMP2Endpoint;
use SIMP2\SDK\Exceptions\CreateMetadataException;
use SIMP2\SDK\Exceptions\OrphanDebtsAreNotEnabled;
use SIMP2\SDK\Exceptions\PaymentAlreadyNotifiedException;
use SIMP2\SDK\Exceptions\PaymentNotFoundException;
use SIMP2\SDK\Exceptions\ReversePaymentException;
use SIMP2\SDK\Exceptions\SavePaymentException;
use SIMP2\SDK\Exceptions\SecretKeyAlreadyExistsException;
use SIMP2\SDK\Exceptions\SecretNotFoundException;
use SIMP2\SDK\Exceptions\SIMP2Exception;

class SIMP2SDK
{
    private ?string $companyTransactionToken = null;
    private ?string $forcePaymentMethodToken = null;

    /**
     * @throws RequestException
     */
    protected function makeRequest(
        string $endpoint,
        string $method,
        ?array $data = null
    ): Response
    {
        $headers = [
            'X-API-KEY' => config('simp2.api_key'),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        if ($this->companyTransactionToken) {
            $headers['company-transaction-token'] = $this->companyTransactionToken;
        }

        if ($this->forcePaymentMethodToken) {
            $headers['X-FORCE-PAYMENT-METHOD'] = $this->forcePaymentMethodToken;
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

    public function setCompanyTransactionToken(string $cct): void
    {
        $this->companyTransactionToken = $cct;
    }

    public function setForcePaymentToken(string $token): void
    {
        $this->forcePaymentMethodToken = $token;
    }

    /**
     * @throws PaymentNotFoundException
     * @throws SavePaymentException
     * @throws PaymentAlreadyNotifiedException
     */
    public function notifyPayment(
        string  $unique_reference,
        ?string $date = null,
        ?string $submethod = null,
        ?string $terminal = null,
        ?string $trx_code = null,
        ?string $utility = null,
        ?string $last_four = null,
        ?string $card_brand = null,
        ?float  $amount = null,
    ): void
    {
        try {
            $body = [
                'unique_reference' => $unique_reference,
                'date' => $date ?? Carbon::now()->toDateTimeString()
            ];

            if ($amount) {
                $body['amount'] = $amount;
            }

            if ($submethod) {
                $body['submethod'] = $submethod;
            }

            if ($terminal) {
                $body['terminal'] = $terminal;
            }

            if ($trx_code) {
                $body['trx_code'] = $trx_code;
            }

            if ($utility) {
                $body['utility'] = $utility;
            }

            if ($last_four) {
                $body['last_four'] = $last_four;
            }

            if ($card_brand) {
                $body['card_brand'] = $card_brand;
            }

            $this->makeRequest(SIMP2Endpoint::notifyPaymentEndpoint, 'POST', $body);
        } catch (RequestException $e) {
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
    public function confirmPayment(
        string  $unique_reference,
        ?string $date = null,
        ?string $submethod = null,
        ?string $terminal = null,
        ?string $trx_code = null,
        ?string $utility = null,
        ?string $amount = null,
        ?string $last_four = null,
        ?string $card_brand = null,
    ): Response
    {
        try {
            $body = [
                'unique_reference' => $unique_reference,
                'date' => $date ?? Carbon::now()->toDateTimeString()
            ];

            if ($amount) {
                $body['amount'] = $amount;
            }

            if ($submethod) {
                $body['submethod'] = $submethod;
            }

            if ($terminal) {
                $body['terminal'] = $terminal;
            }

            if ($trx_code) {
                $body['trx_code'] = $trx_code;
            }

            if ($utility) {
                $body['utility'] = $utility;
            }

            if ($last_four) {
                $body['last_four'] = $last_four;
            }

            if ($card_brand) {
                $body['card_brand'] = $card_brand;
            }

            return $this->makeRequest(SIMP2Endpoint::confirmPaymentEndpoint, 'POST', $body);
        } catch (RequestException $e) {
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
    public function notifyRollbackPayment(
        string  $unique_reference,
        ?string $date = null,
        ?string $submethod = null,
        ?string $terminal = null,
        ?string $trx_code = null,
        ?string $utility = null,
        ?string $last_four = null,
        ?string $card_brand = null,
    ): Response
    {
        try {
            $body = [
                'unique_reference' => $unique_reference,
                'date' => $date ?? Carbon::now()->toDateTimeString()
            ];

            if ($submethod) {
                $body['submethod'] = $submethod;
            }

            if ($terminal) {
                $body['terminal'] = $terminal;
            }

            if ($trx_code) {
                $body['trx_code'] = $trx_code;
            }

            if ($utility) {
                $body['utility'] = $utility;
            }

            if ($last_four) {
                $body['last_four'] = $last_four;
            }

            if ($card_brand) {
                $body['card_brand'] = $card_brand;
            }

            return $this->makeRequest(SIMP2Endpoint::notifyRollbackEndpoint, 'POST', $body);
        } catch (RequestException $e) {
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
     * @throws PaymentNotFoundException
     * @throws ReversePaymentException
     */
    public function confirmRollbackPayment(
        string  $unique_reference,
        ?string $date = null,
        ?string $submethod = null,
        ?string $terminal = null,
        ?string $trx_code = null,
        ?string $utility = null,
        ?string $amount = null,
        ?string $last_four = null,
        ?string $card_brand = null,
    ): Response
    {
        try {
            $body = [
                'unique_reference' => $unique_reference,
                'date' => $date ?? Carbon::now()->toDateTimeString()
            ];

            if ($amount) {
                $body['amount'] = $amount;
            }

            if ($submethod) {
                $body['submethod'] = $submethod;
            }

            if ($terminal) {
                $body['terminal'] = $terminal;
            }

            if ($trx_code) {
                $body['trx_code'] = $trx_code;
            }

            if ($utility) {
                $body['utility'] = $utility;
            }

            if ($last_four) {
                $body['last_four'] = $last_four;
            }

            if ($card_brand) {
                $body['card_brand'] = $card_brand;
            }

            return $this->makeRequest(SIMP2Endpoint::confirmRollbackEndpoint, 'POST', $body);
        } catch (RequestException $e) {
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
     * @throws SIMP2Exception
     * @throws SecretKeyAlreadyExistsException
     */
    public function createSecret(string $key, string $value): void
    {
        try {
            $this->makeRequest(SIMP2Endpoint::secretEndpoint, 'POST', [
                'key' => $key,
                'value' => $value
            ]);
        } catch (RequestException $e) {
            if ($e->response->status() === 409) {
                throw new SecretKeyAlreadyExistsException();
            }
            Log::alert('No se pudo crear el secret', ['message' => $e->getMessage(), 'key' => $key]);
            throw new SIMP2Exception($e->getMessage());
        }
    }

    /**
     * @throws SIMP2Exception
     * @throws SecretNotFoundException
     */
    public function getSecret(string $key): string
    {
        try {
            $res = $this->makeRequest(SIMP2Endpoint::secretEndpoint . "/" . $key, 'GET');
            return $res->json('secret');
        } catch (RequestException $e) {
            if ($e->response->status() === 404) {
                throw new SecretNotFoundException();
            }
            throw new SIMP2Exception($e->getMessage());
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
                throw new PaymentNotFoundException();
            }
            throw new SIMP2Exception($e->getMessage());
        }
    }

    /**
     * @throws OrphanDebtsAreNotEnabled
     * @throws SIMP2Exception
     */
    public function createOrphanDebt(array $data): void
    {
        try {
            $res = $this->makeRequest(SIMP2Endpoint::debtOrphanEndpoint, 'POST', $data);
            if ($res->status() === 204) throw new OrphanDebtsAreNotEnabled();
        } catch (RequestException $e) {
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
                throw new PaymentNotFoundException();
            }
            throw new SIMP2Exception($e->getMessage());
        }
    }

    /**
     * @param string $date
     * @param int $page
     * @return array
     * @throws SIMP2Exception
     */
    public function getPaymentsCreatedInTheLast24Hours(string $date, int $page = 1): array
    {
        try {
            $res = $this->makeRequest(SIMP2Endpoint::lastDayPaymentsEndpoint . "?page=$page", 'GET', ['date' => $date]);
            return $res->json();
        } catch (RequestException $e) {
            throw new SIMP2Exception($e->getMessage());
        }
    }

    /**
     * @param string $barcode
     * @param bool $internal
     * @return Debt
     * @throws PaymentNotFoundException
     * @throws SIMP2Exception
     */
    public function getSubdebtByBarcode(string $barcode, bool $internal = false): Debt
    {
        try {
            $res = $this->makeRequest(SIMP2Endpoint::debtBarcodeEndpoint . $barcode, 'GET', ['internal' => $internal]);
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
            $subdebt->setCurrency($rawSubDebt['currency'] ?? "ARS");
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
