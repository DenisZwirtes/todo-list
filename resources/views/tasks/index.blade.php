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

        <x-table>
            <x-slot:header>
                <tr>
                    <th>{{ __('messages.title') }}</th>
                    <th>{{ __('messages.description') }}</th>
                    <th>{{ __('messages.category') }}</th>
                    <th>{{ __('messages.completed') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </x-slot:header>

            @forelse ($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->description }}</td>
                    <td>{{ $task->category->name ?? __('messages.no_category') }}</td>
                    <td>{{ $task->is_completed ? __('messages.yes') : __('messages.no') }}</td>
                    <td>
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning">
                            {{ __('messages.edit') }}
                        </a>

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
                    <td colspan="5" class="text-center">{{ __('messages.no_tasks_found') }}</td>
                </tr>
            @endforelse
        </x-table>
    </div>
@endsection
