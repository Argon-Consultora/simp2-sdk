<?php

namespace SIMP2\SDK\DTO;

use SIMP2\SDK\Enums\DebtStatus;

class SubDebt
{
    private string $unique_reference;
    private float $amount;
    private string $currency;
    private string $due_date;
    private array $texts;
    private string $barcode;
    private string $status;
    private bool $expired;

    public function setExpired(bool $expired): void
    {
        $this->expired = $expired;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getUniqueReference(): string
    {
        return $this->unique_reference;
    }

    public function setUniqueReference(string $unique_reference): void
    {
        $this->unique_reference = $unique_reference;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getDueDate(): string
    {
        return $this->due_date;
    }

    public function setDueDate(string $due_date): void
    {
        $this->due_date = $due_date;
    }

    public function getTexts(): array
    {
        return $this->texts;
    }

    public function setTexts(array $texts): void
    {
        $this->texts = $texts;
    }

    public function getTextoTicket(): string
    {
        return $this->getTexts()[0];
    }

    public function setBarCode(string $barcode): void
    {
        $this->barcode = $barcode;
    }

    public function getBarCode(): string
    {
        return $this->barcode;
    }

    public function isExpired(): bool
    {
        return $this->expired;
    }

    public function isNotPaid(): bool
    {
        return $this->status == DebtStatus::PendingPayment || $this->hasBeenRolledBack();
    }

    public function hasBeenRolledBack(): bool
    {
        return $this->status == DebtStatus::RollbackNotified
            || $this->status == DebtStatus::RollbackConfirmed;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
