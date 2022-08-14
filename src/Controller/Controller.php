<?php

declare(strict_types=1);

namespace App\Controller;

use App\ApISchema\SchemaInterface;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class Controller
{
    public function __construct(
        private RequestStack $request,
        private SchemaInterface $schema
    ) {
        $this->schema->init($request);
    }

    protected function getFilter(string $filterName)
    {
        return $this->schema->getFilter($filterName);
    }

    protected function getQueryStringPrameter(string $key, $defualt = null)
    {
        return $this->request->getCurrentRequest()->query->get($key, $defualt);
    }
}
