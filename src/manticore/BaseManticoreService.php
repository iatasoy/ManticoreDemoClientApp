<?php

abstract class BaseManticoreService
{
    /**
     * Extracts the 'hits' from the Manticore search response.
     *
     * @param array $responseBody The response body from Manticore search.
     * @return array The extracted data.
     */
    protected function extractHits(array $responseBody): array
    {
        $data = [];
        if (isset($responseBody['hits']['hits'])) {
            foreach ($responseBody['hits']['hits'] as $item) {
                if (isset($item['_source'])) {
                    $sourceData = $item['_source'];
                    $sourceData['id'] = $item['_id'];  // Add the id to the data
                    $data[] = $sourceData;
                }
            }
        }
        return $data;
    }

    /**
     * Builds a standardized response.
     *
     * @param array $data The data to return.
     * @param string $error An error message, if any.
     * @param int $statusCode The HTTP status code.
     * @return array The response array.
     */
    protected function buildResponse(array $data, string $error = '', int $statusCode = 200): array
    {
        // Set status code to 400 if an error is present
        if (!empty($error)) {
            $statusCode = 400;
        }

        return [
            'success' => empty($error), // True if no error
            'status' => $statusCode,     // HTTP status code
            'data' => $data,
            'error' => empty($error) ? null : [
                'message' => $error,
                'code' => $statusCode,
            ],
            'rowCount' => count($data),  // The count of data items
        ];
    }
}
