<?php

namespace SIMP2\SDK\Enums;

use BenSampo\Enum\Enum;

final class DebtStatus extends Enum
{
    const PendingPayment = 'pending_payment';
    const RollbackNotified = 'rollback_notified';
    const RollbackConfirmed = 'rollback_confirmed';
}
