<?php


namespace SIMP2\Tests;

use SIMP2\SDK\DTO\Debt;
use SIMP2\SDK\DTO\SubDebt;
use SIMP2\SDK\Enums\DebtStatus;

trait MakesRequestToSIMP2
{
    protected static function headers(): array
    {
        return ['Accept' => 'application/json', 'Content-Type' => 'application/json'];
    }

    protected static function getApiUrl(string $endpoint = ''): string
    {
        return config('simp2.api_url', 'localhost') . $endpoint;
    }

    protected function debtInfoBody(): array
    {
        return [
            (object)[
                "code" => "123",
                "ccf_code" => "2198732198",
                "ccf_client_id" => "11223344",
                "ccf_extra" => [],
                "ccf_client_data" => (object)[
                    "first_name" => "Elon",
                    "last_name" => "Musk"
                ],
                "subdebts" => [
                    0 => (object)[
                        "unique_reference" => "1",
                        "amount" => 11,
                        "due_date" => "2021-02-01 18:00:00",
                        "texts" => [['test']],
                        "barcode" => '7777000000000000000000000000000000000000',
                        "expired" => false,
                        "status" => DebtStatus::PendingPayment,
                    ]
                ]
            ]
        ];
    }

    protected function clientBody(): array
    {
        return [
            (object)[
                "code" => "123",
                "ccf_code" => "123",
                "ccf_client_id" => "112233",
                "ccf_extra" => [],
                "ccf_client_data" => (object)[
                    "first_name" => "Elon",
                    "last_name" => "Musk",
                    "extra" => []
                ],
                "subdebts" => [
                    (object)[
                        "unique_reference" => "1",
                        "amount" => 11,
                        "due_date" => "2021-02-01 18:00:00",
                        "texts" => [["test"]],
                        "barcode" => '7777000000000000000000000000000000000000',
                        "expired" => false,
                        "status" => DebtStatus::PendingPayment
                    ]
                ]
            ]
        ];
    }


    protected function expectedDebtInfoResponse(): Debt
    {
        $debt = new Debt();
        $debt->setCode('123');
        $debt->setClientId('11223344');
        $debt->setClientName('Elon Musk');
        $debt->setClientFirstName('Elon');
        $debt->setClientLastName('Musk');
        $debt->setExtra(null);
        $subdebt = new SubDebt();
        $subdebt->setAmount(11);
        $subdebt->setUniqueReference('1');
        $subdebt->setDueDate("2021-02-01 18:00:00");
        $subdebt->setTexts(['test']);
        $subdebt->setBarCode('7777000000000000000000000000000000000000');
        $subdebt->setExpired(false);
        $subdebt->setCurrency("ARS");
        $subdebt->setStatus(DebtStatus::PendingPayment);
        $debt->setSubdebts([$subdebt]);

        return $debt;
    }

    protected function expectedDebt(): Debt
    {
        $debt = new Debt();
        $debt->setClientId('112233');
        $debt->setClientName('Elon Musk');
        $debt->setSubdebts($this->expectedSubdebts());

        return $debt;
    }

    /**
     * @return SubDebt[]
     */
    protected function expectedSubdebts(): array
    {
        $subdebt = new SubDebt();
        $subdebt->setAmount(11);
        $subdebt->setUniqueReference('1');
        $subdebt->setDueDate("2021-02-01 18:00:00");
        $subdebt->setTexts(['test']);
        $subdebt->setBarCode('7777000012000000000000000000230002057353');
        $subdebt->setExpired(false);
        $subdebt->setCurrency("ARS");
        $subdebt->setStatus(DebtStatus::PendingPayment);

        return [$subdebt];
    }

    protected function uniqueDebtResponse(): array
    {
        $json = '
          [{
            "code":"00112233",
            "api_client":"nmigueles.dev",
            "ccf_client_data":{
                  "first_name":"Elon",
                  "last_name":"Musk",
                  "extra":[]
            },
            "ccf_client_id":"11223344",
            "ccf_code":"123",
            "ccf_extra":[],
            "client_origin_id":"nmigueles.dev",
            "payment_methods":[
              {
                "name":"pagofacil",
                "submethods":["cash"]
              }
            ],
            "status":"pending_payment",
            "subdebts":[
              {
                "unique_reference":"1",
                "amount": 11,
                "due_date":"2021-02-01 18:00:00",
                "texts":[["test"]],
                "status":"pending_payment",
                "barcode":"7777000012000000000000000000230002057353",
                "expired":false
              }
            ]
          }]';
        return json_decode($json, true);
    }

    protected function expectedDebtUnique(): Debt
    {
        $debt = new Debt();
        $debt->setCode('00112233');
        $debt->setClientId('11223344');
        $debt->setClientName('Elon Musk');
        $debt->setClientFirstName('Elon');
        $debt->setClientLastName('Musk');
        $debt->setExtra([]);
        $debt->setSubdebts($this->expectedSubdebts());

        return $debt;
    }
}
