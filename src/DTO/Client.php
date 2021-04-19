<?php

namespace SIMP2\SDK\DTO;

class Client
{
    private string $client_name;
    private string $client_id;

    public function getClientName(): string
    {
        return $this->client_name;
    }

    public function setClientName(string $client_name): Client
    {
        $this->client_name = $client_name;
        return $this;
    }

    public function getClientId(): string
    {
        return $this->client_id;
    }


    public function setClientId(string $client_id): Client
    {
        $this->client_id = $client_id;
        return $this;
    }
}
