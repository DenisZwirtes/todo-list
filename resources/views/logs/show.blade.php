@extends('app')

@section('title', 'Detalhes do Log')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('logs.index') }}" class="text-blue-600 hover:text-blue-900">
            ← Voltar para Logs
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Detalhes do Log #{{ $log->id }}</h1>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informações Básicas -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informações Básicas</h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nível</dt>
                            <dd class="mt-1">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($log->level == 'error') bg-red-100 text-red-800
                                    @elseif($log->level == 'warning') bg-yellow-100 text-yellow-800
                                    @elseif($log->level == 'info') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($log->level) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Mensagem</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $log->message }}</dd>
                        </div>
                        @if($log->error_message)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Mensagem de Erro</dt>
                            <dd class="mt-1 text-sm text-red-600">{{ $log->error_message }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Data/Hora</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $log->created_at->format('d/m/Y H:i:s') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Informações Técnicas -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informações Técnicas</h2>
                    <dl class="space-y-3">
                        @if($log->file)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Arquivo</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $log->file }}</dd>
                        </div>
                        @endif
                        @if($log->line)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Linha</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $log->line }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">IP Address</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $log->ip_address ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">User Agent</dt>
                            <dd class="mt-1 text-sm text-gray-900 break-all">{{ $log->user_agent ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Usuário</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($log->user)
                                    {{ $log->user->name }} (ID: {{ $log->user_id }})
                                @else
                                    <span class="text-gray-400">Anônimo</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Contexto -->
            @if($log->context && !empty($log->context))
            <div class="mt-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Contexto</h2>
                <div class="bg-gray-50 rounded-lg p-4">
                    <pre class="text-sm text-gray-900 overflow-x-auto">{{ json_encode($log->context, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
