<?php

namespace App\ApISchema;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class BaseSchema implements SchemaInterface
{
    protected const TYPE_INT = 1;
    protected const TYPE_STRING = 2;
    protected const TYPE_BOOL = 3;
    protected const TYPE_ARR = 4;
    protected const TYPE_DATETIME = 5;

    private array $_filters;
    private RequestStack $request;

    public function init(RequestStack $request)
    {
        $this->request = $request;
        $this->_filters = $this->defineFilters();
    }

    /**
     * To build schema
     *
     * @param mixed $data
     * @return array
     */
    abstract public function build($data): array;

    abstract protected function defineFilters(): array;

    public function getFilter(string $filterName)
    {
        $queryParam = $this->request->getCurrentRequest()->query;
        $value = $queryParam->get($filterName, null);

        switch ($this->_filters[$filterName]) {
            case self::TYPE_INT:
                return is_null($value) ? null : intval($value);
            case self::TYPE_ARR:
                return is_null($value) ? null : explode(",", $value);
            case self::TYPE_STRING:
                return is_null($value) ? null : $value;
            case self::TYPE_BOOL:
                return is_null($value) ? null : $value != 0;
            case self::TYPE_DATETIME:
                if (!is_null($value)) {
                    if (!strtotime($value)) {
                        throw new Exception("This filter [{$filterName}] is a datetime type, please write datetime in the right format");
                    }
                    return date_create($value);
                }
                return null;
            default:
                return $value;
        }
    }

    public function response($data)
    {
        return new JsonResponse($this->build($data));
    }
}
