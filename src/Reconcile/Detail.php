<?php

namespace SIMP2\SDK\Reconcile;

class Detail extends Registry
{
    protected string $identifier = "D";

    public function setUigkjg(string $text) {
        $this->fields[0] = $text;
    }
}
