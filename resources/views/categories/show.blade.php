@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ __('messages.category_details') }}</h1>

        <x-card :header="$category->name">
            <p>
                <strong>{{ __('messages.name') }}:</strong>
                {{ $category->name }}
            </p>

            <p>
                <strong>{{ __('messages.created_at') }}:</strong>
                {{ $category->created_at->format('d/m/Y H:i') }}
            </p>

            <p>
                <strong>{{ __('messages.updated_at') }}:</strong>
                {{ $category->updated_at->format('d/m/Y H:i') }}
            </p>
        </x-card>

        <div class="mt-3">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                {{ __('messages.back') }}
            </a>

            <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">
                {{ __('messages.edit') }}
            </a>

            <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-danger delete-button" data-id="{{ $category->id }}">
                    {{ __('messages.delete') }}
                </button>
            </form>
        </div>
    </div>
@endsection
