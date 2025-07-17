<?php

namespace App\Http\Controllers;

use App\Support\Logging\FluentLogger;
use App\Support\Logging\LoggerHelper;
use App\Enums\LogOperation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExampleController extends Controller
{
    /**
     * Exemplo de uso direto do FluentLogger
     */
    public function exampleDirectUsage(Request $request): JsonResponse
    {
        try {
            $data = $request->all();

            (new FluentLogger())
                ->crud(LogOperation::CREATE, 'Example', 123)
                ->request($request)
                ->context([
                    'custom_data' => $data,
                    'operation_type' => 'direct_usage'
                ])
                ->log();

            return response()->json(['message' => 'Operação realizada com sucesso']);
        } catch (Exception $e) {
            (new FluentLogger())
                ->crud(LogOperation::CREATE, 'Example', 123)
                ->request($request)
                ->exception($e)
                ->context(['error_context' => 'direct_usage_example'])
                ->log();

            throw $e;
        }
    }

    /**
     * Exemplo usando LoggerHelper
     */
    public function exampleWithHelper(Request $request): JsonResponse
    {
        try {
            LoggerHelper::createModel('Example', 456)
                ->request($request)
                ->context(['helper_usage' => true])
                ->log();

            return response()->json(['message' => 'Operação com helper realizada']);
        } catch (Exception $e) {
            LoggerHelper::crud(LogOperation::CREATE, 'Example', 456)
                ->request($request)
                ->exception($e)
                ->context(['error_context' => 'helper_example'])
                ->log();

            throw $e;
        }
    }

    /**
     * Exemplo de validação
     */
    public function exampleValidation(Request $request): JsonResponse
    {
        try {
            $errors = ['name' => ['O nome é obrigatório']];

            (new FluentLogger())
                ->validation('Example', $errors)
                ->request($request)
                ->context(['validation_context' => 'example'])
                ->log();

            return response()->json(['message' => 'Validação registrada']);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Exemplo de listagem
     */
    public function exampleListing(Request $request): JsonResponse
    {
        try {
            (new FluentLogger())
                ->listing('Example')
                ->request($request)
                ->context(['listing_context' => 'example'])
                ->log();

            return response()->json(['message' => 'Listagem registrada']);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Exemplo de operações encadeadas
     */
    public function exampleChained(Request $request): JsonResponse
    {
        try {
            $logger = new FluentLogger();

            $logger->model('Example', 789)
                   ->operation(LogOperation::UPDATE)
                   ->request($request)
                   ->context([
                       'chained_operation' => true,
                       'step' => 'update'
                   ]);

            $logger->context(['additional_data' => 'chained_example']);
            $logger->log();

            return response()->json(['message' => 'Operação encadeada realizada']);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
