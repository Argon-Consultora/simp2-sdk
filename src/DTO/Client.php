<?php

namespace SIMP2\SDK\DTO;

class Client
{
    private mixed $extra;
    private string $client_id;
    private string $client_name;
    private string $client_first_name;
    private string $client_last_name;

    public function getExtra(): mixed
    {
        return $this->extra;
    }

    public function setExtra(mixed $extra): Client
    {
        $this->extra = $extra;
        return $this;
    }

    public function getClientFirstName(): string
    {
        return $this->client_first_name;
    }

    public function setClientFirstName(string $client_first_name): Client
    {
        $this->client_first_name = $client_first_name;
        return $this;
    }

    public function getClientLastName(): string
    {
        return $this->client_last_name;
    }

    public function setClientLastName(string $client_last_name): Client
    {
        $this->client_last_name = $client_last_name;
        return $this;
    }

    public function getClientName(): string
    {
        return $this->client_name;
    }

    public function setClientName(string $firstname, string $lastname): Client
    {
        $this->client_name = "$firstname $lastname";
        $this->client_first_name = $firstname;
        $this->client_last_name = $lastname;
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
