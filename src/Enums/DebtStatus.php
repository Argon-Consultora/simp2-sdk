<?php

namespace SIMP2\SDK\Enums;

enum DebtStatus: string
{
    const PendingPayment = 'pending_payment';
    const RollbackNotified = 'rollback_notified';
    const RollbackConfirmed = 'rollback_confirmed';
}