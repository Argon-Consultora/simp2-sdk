<?php

namespace SIMP2\SDK\Exceptions;

use Exception;

class OrphanDebtsAreNotEnabled extends Exception
{
    protected $message = "Orphan debts are not enabled.";
}
