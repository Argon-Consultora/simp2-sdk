<?php

namespace SIMP2\SDK\Reconcile;

class Header extends Registry
{
    protected string $identifier = "H";

    public function setText(string $text) {
        $this->fields[0] = $text;
    }

    public function setCreationDate(string $date) {
        $this->fields[1] = $date;
    }
}
