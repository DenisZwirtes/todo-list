@extends('layouts.app')

@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1>{{ __('messages.edit_task') }}</h1>

        <x-form method="PUT" action="{{ route('tasks.update', $task) }}" :showBackButton="true">
            <div class="mb-3">
                <label for="title">{{ __('messages.title') }}</label>
                <input type="text" name="title" id="title" value="{{ $task->title }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="description">{{ __('messages.description') }}</label>
                <textarea name="description" id="description" class="form-control">{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="category_id">{{ __('messages.category') }}</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">{{ __('messages.select_category') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $task->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="is_completed" id="is_completed" class="form-check-input" style="cursor: pointer;"
                       {{ $task->is_completed ? 'checked' : '' }}>
                <label for="is_completed" class="form-check-label" style="cursor: pointer;">
                    {{ __('messages.is_completed') }}
                </label>
            </div>
        </x-form>
    </div>
@endsection
