<?php

namespace SIMP2\SDK\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static PaymentConfirmation()
 * @method static static PaymentConfirmationError()
 * @method static static RollbackError()
 * @method static static RollbackConfirmation()
 * @method static static RollbackNotification()
 * @method static static RollbackNotFound()
 * @method static static SavePaymentError()
 * @method static static DirectaNotFound()
 * @method static static ClientNotFound()
 * @method static static AuditInvalidAmount()
 * @method static static AuditInvalidClient()
 * @method static static DebtError()
 */
final class TypeDescription extends Enum
{
    const PaymentConfirmation = 'reporting_payment_confirmation';
    const PaymentConfirmationError = 'payment_confirmation_error';
    const PaymentNotification = 'reporting_payment_notification';
    const PaymentNotificationError = 'payment_notification_error';
    const RollbackConfirmation = 'reporting_rollback_confirmation';
    const RollbackNotification = 'reporting_rollback_notification';
    const RollbackError = 'rollback_error';
    const RollbackNotFound = 'rollback_not_found';
    const SavePaymentError = 'save_payment_error';
    const DirectaNotFound = 'imputation_debt_not_found';
    const ClientNotFound = 'client_not_found';
    const AuditInvalidAmount = 'audit_invalid_amount';
    const AuditInvalidClient = 'audit_invalid_client';
    const DebtError = 'debt_error';
}
