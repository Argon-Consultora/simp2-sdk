<?php

namespace SIMP2\SDK\DTO;

class Debt
{
    private string $code;
    private string $client_name;
    private string $client_id;
    private string $client_first_name;
    private string $client_last_name;
    private mixed $extra = null;
    /**
     * @var SubDebt[]
     */
    private array $subdebts;

    /**
     * @return string
     */
    public function getClientFirstName(): string
    {
        return $this->client_first_name;
    }

    /**
     * @param string $client_first_name
     */
    public function setClientFirstName(string $client_first_name): void
    {
        $this->client_first_name = $client_first_name;
    }

    /**
     * @return string
     */
    public function getClientLastName(): string
    {
        return $this->client_last_name;
    }

    /**
     * @param string $client_last_name
     */
    public function setClientLastName(string $client_last_name): void
    {
        $this->client_last_name = $client_last_name;
    }

    public function getClientName(): string
    {
        return $this->client_name;
    }

    public function setClientName(string $client_name): void
    {
        $this->client_name = $client_name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return SubDebt[]
     */
    public function getSubdebts(): array
    {
        return $this->subdebts;
    }

    /**
     * @param SubDebt[] $subdebts
     */
    public function setSubdebts(array $subdebts): void
    {
        $this->subdebts = $subdebts;
    }

    public function getSubdebt(?string $reference = null): ?SubDebt
    {
        if (!$reference && count($this->subdebts) == 1) {
            // If not reference is provided and there's only one subdebt, return it.
            return $this->subdebts[0];
        }

        foreach ($this->subdebts as $subdebt) {
            if ($subdebt->getUniqueReference() === $reference) {
                return $subdebt;
            }
        }

        return null;
    }

    public function isFromClient(string $client_id): bool
    {
        return $this->getClientId() === $client_id;
    }

    public function getClientId(): string
    {
        return $this->client_id;
    }

    public function setClientId(string $client_id): void
    {
        $this->client_id = $client_id;
    }

    public function getExtra(): mixed
    {
        return $this->extra ?? null;
    }

    public function setExtra(mixed $extra)
    {
        $this->extra = $extra;
    }
}
