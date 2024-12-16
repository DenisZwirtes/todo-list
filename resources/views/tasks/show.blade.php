@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('messages.task_details') }}</h1>

        <x-card :header="$task->title">
            <p>
                <strong>{{ __('messages.description') }}:</strong>
                {{ $task->description }}
            </p>

            <p>
                <strong>{{ __('messages.category') }}:</strong>
                {{ $task->category->name ?? __('messages.no_category') }}
            </p>

            <p>
                <strong>{{ __('messages.created_at') }}:</strong>
                {{ $task->created_at->format('d/m/Y H:i') }}
            </p>

            <p>
                <strong>{{ __('messages.updated_at') }}:</strong>
                {{ $task->updated_at->format('d/m/Y H:i') }}
            </p>
        </x-card>

        <div class="mt-3">
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                {{ __('messages.back') }}
            </a>

            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary">
                {{ __('messages.edit') }}
            </a>

            <form id="delete-form-{{ $task->id }}" action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-danger delete-button" data-id="{{ $task->id }}">
                    {{ __('messages.delete') }}
                </button>
            </form>
        </div>
    </div>
@endsection
