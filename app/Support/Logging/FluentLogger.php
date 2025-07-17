<?php

namespace App\Support\Logging;

use App\Contracts\Services\LogServiceInterface;
use App\Enums\LogOperation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FluentLogger
{
    protected ?LogOperation $operation = null;
    protected string $model = '';
    protected $modelId = null;
    protected array $context = [];
    protected ?\Throwable $exception = null;

    public function model(string $model, $id = null): self
    {
        $this->model = $model;
        $this->modelId = $id;
        return $this;
    }

    public function operation(LogOperation $operation): self
    {
        $this->operation = $operation;
        return $this;
    }

    /**
     * Método conciso para operações CRUD
     */
    public function crud(LogOperation $operation, string $model, $id = null): self
    {
        $this->operation = $operation;
        $this->model = $model;
        $this->modelId = $id;
        return $this;
    }

    /**
     * Método para erros de validação
     */
    public function validation(string $model, array $errors = []): self
    {
        $this->operation = LogOperation::VALIDATE;
        $this->model = $model;
        $this->context['validation_errors'] = $errors;
        return $this;
    }

    /**
     * Método para erros de listagem
     */
    public function listing(string $model): self
    {
        $this->operation = LogOperation::READ;
        $this->model = $model;
        return $this;
    }

    public function request(Request $request): self
    {
        $this->context['request_data'] = $request->except(['password']);
        return $this;
    }

    public function context(array $context): self
    {
        $this->context = array_merge($this->context, $context);
        return $this;
    }

    public function exception(\Throwable $exception): self
    {
        $this->exception = $exception;
        return $this;
    }

    public function log(): void
    {
        $message = "Erro ao {$this->operation->value} {$this->model}";
        $context = array_merge([
            'operation' => $this->operation->value,
            'operation_label' => $this->operation->label(),
            'model' => $this->model,
            'model_id' => $this->modelId,
            'user_id' => Auth::id(),
        ], $this->context);

        app(LogServiceInterface::class)->error($message, $context, $this->exception);
    }
}
