<?php

namespace Oinpentuls\BcaApi;

class Utility
{
    //Timestamp in ISO 8601 e.g. “2016-02-03T10:00:00.00+07:00”
    public function bcaTimestamp(): string
    {
        return date('c');
    }

    public function getTime(): string
    {
        $fmt = now()->format('Y-m-d\TH:i:s');

        return sprintf("$fmt.%s%s", substr(microtime(), 2, 3), date('P'));
    }

    public function canonicalize(string $value): string
    {
        return strtolower(str_replace(' ', '', $value));
    }
}
