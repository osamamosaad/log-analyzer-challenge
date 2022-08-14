<?php

declare(strict_types=1);

namespace App\Services\LogAnalyzer\Libraries;

class LogExtractor
{
    private const PATTERN = "/(?<serviceName>[\w\\-\d]*)\s-([^\\[]*)\[(?<date>[^\]]*)]\s\"(?<method>[A-Z]*)\s(?<endpoint>\/[^\s]*)\s(?<http>[A-Z\/0-9\.]*)\"\s(?<status>\d{3})/";

    public function extract(string $content): ?array
    {
        preg_match(self::PATTERN, $content, $matches);
        if (empty($matches)) {
            return null;
        }

        return $this->sanitizing($matches);
    }

    protected function sanitizing(array $data): array
    {
        # we can make a spicific sanitizing for each value in the $data here
        return array_map('trim', $data);
    }
}
