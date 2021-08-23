<?php

namespace SIMP2\SDK\Reconcile;

class Footer extends Registry
{
    protected string $identifier = "F";

    public function setRegistryCount(int $count)
    {
        $this->fields[0] = $count;
    }

    public function setTotal(float $total)
    {
        $this->fields[1] = $total;
    }
}
