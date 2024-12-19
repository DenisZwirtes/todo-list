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

        @if ($noCategories)
            <div class="alert alert-warning">
                {!! __('messages.no_categories_warning', ['link' => url('/categories/create')]) !!}
            </div>
        @endif


        <h1>{{ __('messages.create_task') }}</h1>

        <x-form method="POST" action="{{ route('tasks.store') }}" :showBackButton="true">
            <div class="mb-3">
                <label for="title">{{ __('messages.title') }}</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="description">{{ __('messages.description') }}</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label for="category_id">{{ __('messages.category') }}</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">{{ __('messages.select_category') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="users">{{ __('messages.assign_users') }}</label>
                <select name="users[]" id="users" class="form-control" multiple>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="is_completed" id="is_completed" class="form-check-input" style="cursor: pointer;">
                <label for="is_completed" class="form-check-label" style="cursor: pointer;">
                    {{ __('messages.is_completed') }}
                </label>
            </div>
        </x-form>
    </div>

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="{{ asset('js/custom.js') }}"></script>
@endsection
