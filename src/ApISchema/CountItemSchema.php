<?php

namespace App\ApISchema;

class CountItemSchema extends BaseSchema
{
    protected function defineFilters(): array
    {
        return [
            "serviceNames"  => self::TYPE_ARR,
            "statusCode"    => self::TYPE_INT,
            "startDate"     => self::TYPE_DATETIME,
            "endDate"       => self::TYPE_DATETIME,
        ];
    }

    public function build($data): array
    {
        return [
            "counter" => $data["counter"]
        ];
    }
}
