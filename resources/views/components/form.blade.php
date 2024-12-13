<form method="{{ $method }}" action="{{ $action }}">
    @csrf
    @if(isset($method) && $method === 'PUT')
        @method('PUT')
    @endif

    {{ $slot }}

    <div class="mt-2">
        <button type="submit" class="btn btn-primary">
            Salvar
        </button>
    </div>
</form>
