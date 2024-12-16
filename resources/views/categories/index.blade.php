@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('home') }}" class="btn btn-secondary d-flex align-items-center gap-2 shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>

            <h1>{{ __('messages.my_categories') }}</h1>

            <a href="{{ route('categories.create') }}" class="btn btn-success d-flex align-items-center gap-2 shadow-sm">
                <i class="fas fa-folder-plus"></i>
                <span>{{ __('messages.create_category') }}</span>
            </a>
        </div>

        <x-table>
            <x-slot:header>
                <tr>
                    <th>{{ __('messages.name') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </x-slot:header>

            @forelse ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                            {{ __('messages.edit') }}
                        </a>

                        <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger delete-button" data-id="{{ $category->id }}">
                                {{ __('messages.delete') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center">
                        {{ __('messages.no_categories_found') }}
                    </td>
                </tr>
            @endforelse
        </x-table>
    </div>
@endsection
