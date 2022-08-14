<?php

namespace App\ApISchema;

use Symfony\Component\HttpFoundation\RequestStack;

interface SchemaInterface
{
    public function getFilter(string $filtername);
    public function init(RequestStack $filtername);
}
