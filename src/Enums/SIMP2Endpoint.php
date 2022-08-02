<?php

namespace SIMP2\SDK\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static debtEndpoint()
 * @method static static debtUniqueEndpoint()
 * @method static static debtBarcodeEndpoint()
 * @method static static notifyRollbackEndpoint()
 * @method static static confirmRollbackEndpoint()
 * @method static static notifyPaymentEndpoint()
 * @method static static confirmPaymentEndpoint()
 * @method static static logInfoEndpoint()
 * @method static static logErrorEndpoint()
 * @method static static metadataEndpoint()
 */
final class SIMP2Endpoint extends Enum
{
    // SIMP2 Endpoints
    const debtEndpoint = '/debt';
    const debtOrphanEndpoint = '/debt/orphan';
    const debtUniqueEndpoint = '/debt/unique/';
    const debtBarcodeEndpoint = '/debt/barcode/';
    const debtGeneralEndpoint = '/debt/general/';
    const notifyRollbackEndpoint = '/reverse/notify';
    const confirmRollbackEndpoint = '/reverse/confirm';
    const notifyPaymentEndpoint = '/payments/notify';
    const confirmPaymentEndpoint = '/payments/confirm';
    const lastDayPaymentsEndpoint = '/payments/last_24_hours';
    const logInfoEndpoint = '/events/info';
    const logErrorEndpoint = '/events/error';
    const metadataEndpoint = '/integrations/metadata';
    const secretEndpoint = '/integrations/secret';
    const clientDataEndpoint = '/client/{ccf_client_id}/debts';

    public static function clientDataEndpoint($ccf_client_id): string
    {
        return str_replace('{ccf_client_id}', $ccf_client_id, self::clientDataEndpoint);
    }
}
