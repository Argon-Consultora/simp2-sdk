<?php

namespace SIMP2\SDK\Reconcile;

abstract class Registry
{
    protected array $fields;
    protected string $identifier = "D";

    public function __construct()
    {
        for ($i = 0; $i < 8; $i++) { // 8 Fields in the csv.
            $this->fields[$i] = "";
        }
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getCSV(): string
    {
        return $this->strToCsv($this->getFields());
    }

    private function strToCsv($input): string
    {
        $fp = fopen('php://temp', 'r+b');
        fputcsv($fp, $input);
        rewind($fp);
        $data = rtrim(stream_get_contents($fp), PHP_EOL);
        fclose($fp);
        return $data;
    }

}
