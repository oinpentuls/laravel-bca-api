<?php

namespace Oinpentuls\BcaApi;

use Carbon\Carbon;

use function PHPSTORM_META\map;

class BCA extends Api
{
    use UriTrait;

    public static function make(): self
    {
        return new static;
    }

    public static function getBalance(): array
    {
        return self::make()->sendRequest('GET', self::make()->balanceUri());
    }

    public static function getStatement(string $startDate, string $endDate): array
    {
        $startDate = $startDate ?: now()->format('Y-m-d');
        $endDate = $endDate ?: now()->format('Y-m-d');

        return self::make()->sendRequest('GET', self::make()->statementsUri($startDate, $endDate));
    }

    public static function fundTransfer(string $amount): array
    {
        $body = [
            'CorporateID' => config('bca.corporate_id'),
            'SourceAccountNumber' => self::make()->canonicalize(config('bca.account')),
            'TransactionID' => '00000001',
            'TransactionDate' => now()->format('Y-m-d'),
            'ReferenceID' => 'tess',
            'CurrencyCode' => 'IDR',
            'Amount' => $amount,
            'BeneficiaryAccountNumber' => self::make()->canonicalize('0201245681'),
            'Remark1' => self::make()->canonicalize('remark 1'),
            'Remark2' => self::make()->canonicalize('remark 2'),
        ];

        return self::make()->sendRequest('POST', self::make()->fundTransferUri(), $body);
    }

    public static function domesticTransfer($amount): array
    {
        $headers = [
            'channel-id' => '95051',
            'credential-id' => config('bca.corporate_id')
        ];

        $body = [
            'source_account_number' => self::make()->canonicalize(config('bca.account')),
            'transaction_id' => '00000002',
            'transaction_date' => Carbon::create(2018, 8, 10),
            'currency_code' => 'IDR',
            'transfer_type' => 'LLG',
            'amount' => $amount,
            'beneficiary_account_number' => self::make()->canonicalize('0201245501'),
            'beneficiary_bank_code' => self::make()->canonicalize('BRONINJA'),
            'beneficiary_name' => self::make()->canonicalize('Tessss'),
            'beneficiary_cust_type' => '1',
            'beneficiary_cust_residence' => '1',
            'remark1' => self::make()->canonicalize('remark 1'),
            'remark2' => self::make()->canonicalize('remark 2'),
            'beneficiary_email' => self::make()->canonicalize('email@email.com'),
        ];

        return self::make()->sendRequest('POST', self::make()->domesticTransferUri(), $body, $headers);
    }
}
