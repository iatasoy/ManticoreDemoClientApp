<?php

class ApplicationController
{
    private ManticoreSearchClient $apiClient;

    public function __construct()
    {
        $apiUrl = $_ENV['MANTICORE_API_URL'] ?? getenv('MANTICORE_API_URL') ?? 'http://127.0.0.1:9308';
        $this->apiClient = new ManticoreSearchClient($apiUrl);
    }

    public function showHomePage(): void
    {
        $this->sendResponse(200, "Manticore Backend demo application");
    }

    public function showSearchCustomerPage($f3): void
    {
        $searchField = $f3->get('PARAMS.param') ?? '';

        if (empty($searchField)) {
            $this->sendErrorResponse("Search parameter is required", 400);
            return;
        }

        $data = $this->apiClient->search('tbcustomer', $searchField, ['name', 'email'], 10);

        if (isset($data['error'])) {
            $this->sendErrorResponse($data['error'], 500);
            return;
        }

        $this->sendResponse(200, "Customer search results", $data);
    }

    public function showCustomerByIdPage($f3): void
    {
        $customerId = $f3->get('PARAMS.customerid') ?? '0';

        if (!ctype_digit($customerId)) {
            $this->sendErrorResponse("Customer ID must be a number", 400);
            return;
        }

        $data = $this->apiClient->searchById('tbcustomer', (int) $customerId);

        if (isset($data['error'])) {
            $this->sendErrorResponse($data['error'], 500);
            return;
        }

        $this->sendResponse(200, "Customer details", $data);
    }

    public function showNotFoundPage(): void
    {
        $this->sendErrorResponse("Action not found", 404);
    }


    private function sendResponse(int $status, string $message, array $data = []): void
    {
        http_response_code($status);
        header('Content-Type: application/json');

        echo json_encode([
            'success' => $status < 400, 
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ]);
        exit;
    }

    private function sendErrorResponse(string $message, int $status = 400): void
    {
        $this->sendResponse($status, $message);
    }
}
