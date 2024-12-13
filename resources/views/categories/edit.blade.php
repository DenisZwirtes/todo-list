@extends('layouts.app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container">
        <h1>
            Editar Categoria
        </h1>

        <x-form method="PUT" action="{{ route('categories.update', $category) }}">
            <div class="mb-3">
                <label for="name">
                    Nome da Categoria
                </label>

                <input type="text" name="name" id="name" value="{{ $category->name }}" class="form-control" required>
            </div>
        </x-form>
    </div>
@endsection
