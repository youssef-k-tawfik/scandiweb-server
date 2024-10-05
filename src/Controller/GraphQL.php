<?php

/**
 * This file contains the GraphQL class.
 * It is responsible for handling the incoming GraphQL requests.
 *  
 * @package App\Controller
 */

namespace App\Controller;

require_once __DIR__ . '/../Model/ProductType.php';

use GraphQL\GraphQL as GraphQLBase;
use RuntimeException;
use Throwable;

class GraphQL
{
    static public function handle()
    {
        try {

            // * Build the schema
            $schema = \App\View\GraphQLSchema::buildSchema();

            // * Get the raw input
            $rawInput = file_get_contents('php://input');
            if ($rawInput === false) {
                throw new RuntimeException('Failed to get php://input');
            }

            // * Decode the raw input
            $input = json_decode($rawInput, true);
            $query = $input['query'];
            $variableValues = $input['variables'] ?? null;

            // error_log("Query: " . $query);
            // error_log("Variables: " . json_encode($variableValues));

            // * Execute the query
            $rootValue = ['prefix' => 'You said: '];

            // * Get the result
            $result = GraphQLBase::executeQuery($schema, $query, $rootValue, null, $variableValues);

            // * Convert the result to an array
            $output = $result->toArray();
        } catch (Throwable $e) {
            $output = [
                'error' => [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ],
            ];
        }

        // * Return the output as JSON
        header('Content-Type: application/json; charset=UTF-8');
        error_log("GraphQL responding!");
        return json_encode($output);
    }
}
