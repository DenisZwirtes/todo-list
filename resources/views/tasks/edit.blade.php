@extends('layouts.app')

@section('content')
<div class="container">
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


    <h1>
        Editar Tarefa
    </h1>

    <x-form method="PUT" action="{{ route('tasks.update', $task) }}">
        <div class="mb-3">
            <label for="title">
                Título
            </label>

            <input type="text" name="title" id="title" value="{{ $task->title }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description">
                Descrição
            </label>

            <textarea name="description" id="description" class="form-control">
                {{ $task->description }}
            </textarea>
        </div>

        <div class="mb-3">
            <label for="category_id">
                Categoria
            </label>

            <select name="category_id" id="category_id" class="form-control">
                <option value="">
                    Selecione
                </option>

                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $task->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </x-form>
</div>
@endsection
