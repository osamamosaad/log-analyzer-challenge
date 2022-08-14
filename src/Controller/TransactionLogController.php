<?php

declare(strict_types=1);

namespace App\Controller;

use App\ApISchema\CountItemSchema;
use Symfony\Component\HttpFoundation\{RequestStack,Response};
use Symfony\Component\Routing\Annotation\Route;
use App\Services\LogAnalyzer\Application\Query\GetCountTransactionLogs;

class TransactionLogController extends Controller
{
    public function __construct(
        private RequestStack $request,
        private CountItemSchema $schema,
    ) {
        parent::__construct($request, $schema);
    }

    #[Route('/count', methods: ['GET'])]
    public function index(GetCountTransactionLogs $transactionCount): Response
    {

        $results = $transactionCount->get([
            "serviceNames" => $this->getFilter("serviceNames"),
            "statusCode" => $this->getFilter("statusCode"),
            "startDate" => $this->getFilter("startDate"),
            "endDate" => $this->getFilter("endDate"),
        ]);

        return $this->schema->response($results);
    }
}
