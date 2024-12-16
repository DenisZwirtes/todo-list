<form method="POST" action="{{ $action }}">
    @csrf
    @if (isset($method) && in_array($method, ['PUT', 'PATCH', 'DELETE']))
        @method($method)
    @endif

    {{ $slot }}

    <div class="d-flex gap-2 mt-2">
        <button type="submit" class="btn btn-primary">
            Salvar
        </button>

        @if ($showBackButton ?? false)
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                Voltar
            </a>
        @endif
    </div>
</form>
