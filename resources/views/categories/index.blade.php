@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>
            Minhas Categorias
        </h1>

        <x-table>
            <x-slot:header>
                <tr>
                    <th>
                        Nome
                    </th>

                    <th>
                        Ações
                    </th>
                </tr>
            </x-slot:header>

            @foreach ($categories as $category)
                <tr>
                    <td>
                        {{ $category->name }}
                    </td>

                    <td>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                            Editar
                        </a>

                        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
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

        <a href="{{ route('categories.create') }}" class="btn btn-primary mt-3">
            Adicionar Nova Categoria
        </a>
    </div>
@endsection
