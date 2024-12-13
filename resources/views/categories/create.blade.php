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
            Criar Nova Categoria
        </h1>

        <x-form method="POST" action="{{ route('categories.store') }}">
            <div class="mb-3">
                <label for="name">
                    Nome da Categoria
                </label>

                <input type="text" name="name" id="name" class="form-control" required>
            </div>
        </x-form>
    </div>
@endsection
