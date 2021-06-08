<?php

namespace Oinpentuls\BcaApi;

trait UriTrait
{
    public function sanitizeAccount(): string
    {
        $account = trim(config('bca.account'));

        if (str_ends_with($account, ',')) {
            return rtrim($account, ',');
        }

        return $account;
    }

    public function tokenUri(): string
    {
        return '/api/oauth/token';
    }

    public function balanceUri(): string
    {
        return sprintf(
            '/banking/v3/corporates/%s/accounts/%s',
            config('bca.corporate_id'),
            urlencode($this->sanitizeAccount()),
        );
    }

    public function statementsUri(string $startDate, string $endDate): string
    {
        return sprintf(
            '/banking/v3/corporates/%s/accounts/%s/statements?EndDate=%s&StartDate=%s',
            config('bca.corporate_id'),
            urlencode($this->sanitizeAccount()),
            $endDate,
            $startDate
        );
    }

    public function fundTransferUri(): string
    {
        return '/banking/corporates/transfers';
    }

    public function domesticTransferUri(): string
    {
        return '/banking/corporates/transfers/v2/domestic';
    }
}
