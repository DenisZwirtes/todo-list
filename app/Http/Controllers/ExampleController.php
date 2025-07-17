<?php

namespace App\Http\Controllers;

use App\Support\Logging\FluentLogger;
use App\Support\Logging\LoggerHelper;
use App\Enums\LogOperation;
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
            // Simular uma operação
            $data = $request->all();

            // Uso direto do FluentLogger
            (new FluentLogger())
                ->crud(LogOperation::CREATE, 'Example', 123)
                ->request($request)
                ->context([
                    'custom_data' => $data,
                    'operation_type' => 'direct_usage'
                ])
                ->log();

            return response()->json(['message' => 'Operação realizada com sucesso']);
        } catch (\Exception $e) {
            // Log de erro com exceção
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
            // Usando LoggerHelper para operações CRUD
            LoggerHelper::createModel('Example', 456)
                ->request($request)
                ->context(['helper_usage' => true])
                ->log();

            return response()->json(['message' => 'Operação com helper realizada']);
        } catch (\Exception $e) {
            // Log de erro usando helper
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
            // Simular validação
            $errors = ['name' => ['O nome é obrigatório']];

            // Log de erro de validação
            (new FluentLogger())
                ->validation('Example', $errors)
                ->request($request)
                ->context(['validation_context' => 'example'])
                ->log();

            return response()->json(['message' => 'Validação registrada']);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Exemplo de listagem
     */
    public function exampleListing(Request $request): JsonResponse
    {
        try {
            // Log de listagem
            (new FluentLogger())
                ->listing('Example')
                ->request($request)
                ->context(['listing_context' => 'example'])
                ->log();

            return response()->json(['message' => 'Listagem registrada']);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Exemplo de operações encadeadas
     */
    public function exampleChained(Request $request): JsonResponse
    {
        try {
            // Exemplo de operações encadeadas
            $logger = new FluentLogger();

            $logger->model('Example', 789)
                   ->operation(LogOperation::UPDATE)
                   ->request($request)
                   ->context([
                       'chained_operation' => true,
                       'step' => 'update'
                   ]);

            // Adicionar mais contexto
            $logger->context(['additional_data' => 'chained_example']);

            // Executar o log
            $logger->log();

            return response()->json(['message' => 'Operação encadeada realizada']);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
