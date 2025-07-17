@extends('app')

@section('title', 'Logs do Sistema')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Logs do Sistema</h1>
        <div class="flex space-x-2">
            <a href="{{ route('logs.export') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                Exportar CSV
            </a>
            <form action="{{ route('logs.clear') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded"
                        onclick="return confirm('Tem certeza que deseja limpar logs antigos?')">
                    Limpar Antigos
                </button>
            </form>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
            <div class="text-sm text-gray-600">Total</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-red-600">{{ $stats['errors'] }}</div>
            <div class="text-sm text-gray-600">Erros</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['warnings'] }}</div>
            <div class="text-sm text-gray-600">Avisos</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['info'] }}</div>
            <div class="text-sm text-gray-600">Informações</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-gray-600">{{ $stats['debug'] }}</div>
            <div class="text-sm text-gray-600">Debug</div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nível</label>
                <select name="level" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    <option value="">Todos</option>
                    <option value="error" {{ request('level') == 'error' ? 'selected' : '' }}>Erro</option>
                    <option value="warning" {{ request('level') == 'warning' ? 'selected' : '' }}>Aviso</option>
                    <option value="info" {{ request('level') == 'info' ? 'selected' : '' }}>Informação</option>
                    <option value="debug" {{ request('level') == 'debug' ? 'selected' : '' }}>Debug</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Mensagem, IP, etc..."
                       class="w-full border border-gray-300 rounded-md px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Usuário</label>
                <input type="number" name="user_id" value="{{ request('user_id') }}"
                       placeholder="ID do usuário"
                       class="w-full border border-gray-300 rounded-md px-3 py-2">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de Logs -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nível
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mensagem
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            IP
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Usuário
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Data
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($log->level == 'error') bg-red-100 text-red-800
                                @elseif($log->level == 'warning') bg-yellow-100 text-yellow-800
                                @elseif($log->level == 'info') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($log->level) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ Str::limit($log->message, 50) }}</div>
                            @if($log->error_message)
                                <div class="text-sm text-red-600">{{ Str::limit($log->error_message, 30) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $log->ip_address }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($log->user)
                                {{ $log->user->name }}
                            @else
                                <span class="text-gray-400">Anônimo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('logs.show', $log) }}"
                               class="text-blue-600 hover:text-blue-900">Ver Detalhes</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Nenhum log encontrado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginação -->
    <div class="mt-6">
        {{ $logs->links() }}
    </div>
</div>
@endsection
