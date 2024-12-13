@extends('layouts.app')

@section('content')
<div class="container">
    <h1>
        Minhas Tarefas
    </h1>

    <x-table>
        <x-slot:header>
            <tr>
                <th>
                    Título
                </th>

                <th>
                    Categoria
                </th>

                <th>
                    Concluída
                </th>

                <th>
                    Ações
                </th>
            </tr>
        </x-slot:header>

        @foreach ($tasks as $task)
        <tr>
            <td>
                {{ $task->title }}
            </td>

            <td>
                {{ $task->category->name ?? 'Sem Categoria' }}
            </td>

            <td>
                {{ $task->is_completed ? 'Sim' : 'Não' }}
            </td>

            <td>
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning">
                    Editar
                </a>

                <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <x-button class="btn-danger">
                        Excluir
                    </x-button>
                </form>
            </td>
        </tr>
        @endforeach
    </x-table>
</div>
@endsection
