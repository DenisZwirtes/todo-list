@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('home') }}" class="btn btn-secondary d-flex align-items-center gap-2 shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>

            <h1 class="mb-0">{{ __('messages.my_tasks') }}</h1>

            <a href="{{ route('tasks.create') }}" class="btn btn-success d-flex align-items-center gap-2 shadow-sm">
                <i class="fas fa-plus-circle"></i>
                <span>{{ __('messages.create_task') }}</span>
            </a>
        </div>

        {{-- Filtros --}}
        <form method="GET" action="{{ route('tasks.index') }}" class="mb-4">
            <div class="row g-3 align-items-end">

                {{-- Filtro por Categoria --}}
                <div class="col-md-4">
                    <label for="category" class="form-label">{{ __('messages.category') }}</label>
                    <select name="category_id" id="category" class="form-control">
                        <option value="">{{ __('messages.all_categories') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtro por Tarefas Concluídas --}}
                <div class="col-md-4">
                    <div class="form-check">
                        <input type="checkbox" name="completed" id="completed" value="1" class="form-check-input"
                            {{ request('completed') ? 'checked' : '' }}>
                        <label for="completed" class="form-check-label">{{ __('messages.show_completed') }}</label>
                    </div>
                </div>

                {{-- Botões de Ação --}}
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        {{ __('messages.filter') }}
                    </button>
                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                        {{ __('messages.reset') }}
                    </a>
                </div>
            </div>
        </form>

        {{-- Tabela com as Tarefas --}}
        <x-table>
            <x-slot:header>
                <tr>
                    <th>{{ __('messages.title') }}</th>
                    <th>{{ __('messages.description') }}</th>
                    <th>{{ __('messages.category') }}</th>
                    <th>{{ __('messages.completed') }}</th>
                    <th>{{ __('messages.assigned_users') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </x-slot:header>

            @forelse ($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->description }}</td>
                    <td>{{ $task->category->name ?? __('messages.no_category') }}</td>
                    <td>{{ $task->is_completed ? __('messages.yes') : __('messages.no') }}</td>
                    {{-- Lista dos Usuários Atribuídos à Tarefa --}}
                    <td>
                        @forelse ($task->users as $user)
                            <span class="badge bg-primary">{{ $user->name }}</span>
                        @empty
                            <span>{{ __('messages.no_users_assigned') }}</span>
                        @endforelse
                    </td>
                    <td>
                        {{-- Editar Tarefa --}}
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning">
                            {{ __('messages.edit') }}
                        </a>

                        {{-- Deletar Tarefa --}}
                        <form id="delete-form-{{ $task->id }}" action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger delete-button" data-id="{{ $task->id }}">
                                {{ __('messages.delete') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">{{ __('messages.no_tasks_found') }}</td>
                </tr>
            @endforelse
        </x-table>
    </div>
@endsection
