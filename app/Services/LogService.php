<?php

namespace App\Services;

use App\Contracts\Services\LogServiceInterface;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogService implements LogServiceInterface
{
    public function error(string $message, array $context = [], ?\Throwable $exception = null): void
    {
        $this->log('error', $message, $context, $exception);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->log('warning', $message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->log('info', $message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->log('debug', $message, $context);
    }

    protected function log(string $level, string $message, array $context = [], ?\Throwable $exception = null): void
    {
        $data = [
            'level' => $level,
            'message' => $message,
            'context' => $context,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'user_id' => Auth::id(),
        ];

        if ($exception) {
            $data['file'] = $exception->getFile();
            $data['line'] = $exception->getLine();
            $data['error_message'] = $exception->getMessage();
        }

        Log::create($data);
    }
}
