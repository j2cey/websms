<?php
/**
 * Created by PhpStorm.
 * User: jngom
 * Date: 27/04/2016
 * Time: 08:36
 */

$ucip_respcode_array = [

    /*
    |--------------------------------------------------------------------------
    | UCIP Response Code
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    '0' => "successful",
    '1' => "okButSupervisionPeriodExceeded",
    '2' => "okButServiceFeePeriodExceeded",
    '100' => "otherError",
    '101' => "not used",
    '102' => "subscriberNotFound",
    '103' => "accountBarredFromRefill",
    '104' => "temporaryBlocked",
    '105' => "dedicatedAccountNotAllowed",
    '106' => "dedicatedAccountNegative",
    '107' => "voucherStatusUsedBySame",
    '108' => "voucherStatusUsedByDifferent",
    '109' => "voucherStatusUnavailable",
    '110' => "voucherStatusExpired",
    '111' => "voucherStatusStolenOrMissing",
    '112' => "voucherStatusDamaged",
    '113' => "voucherStatusPending",
    '114' => "voucherTypeNotAccepted",
    '115' => "voucherGroupServiceClassErr",
    '116' => "serviceClassHierarchyErr",
    '117' => "serviceClassChangeNotAllowed",
    '118' => "valueVoucherNotActive",
    '119' => "invalidActivationNumber",
    '120' => "invalidPaymentProfile",
    '121' => "supervisionPeriodTooLong",
    '122' => "serviceFeePeriodTooLong",
    '123' => "maxCreditLimitExceeded",
    '124' => "belowMinimumBalance",
    '125' => "system unavailable",
    '126' => "accountNotActive",
    '127' => "accumulatorNotAvailable",
    '128' => "UpdateAccountDetailsT",
    '129' => "fafNumberDoesNotExist",
    '130' => "fafNumberNotAllowed, (see notAllowedReason for further information)",
    '133' => "serviceClassChangeListEmpty",
    '134' => "accumulatorOverflow",
    '135' => "accumulatorUnderflow",
    '136' => "dateAdjustmentError",
    '137' => "ballanceEnquiryNotAllowed",
];
