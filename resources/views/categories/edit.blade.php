@extends('layouts.app')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container">
        <h1>{{ __('messages.edit_category') }}</h1>

        <x-form method="PUT" action="{{ route('categories.update', $category) }}" :showBackButton="true">
            <div class="mb-3">
                <label for="name" class="form-label">
                    {{ __('messages.name') }}
                </label>

                <input type="text" name="name" id="name" value="{{ $category->name }}" class="form-control" placeholder="{{ __('messages.name') }}" required>
            </div>
        </x-form>
    </div>
@endsection
