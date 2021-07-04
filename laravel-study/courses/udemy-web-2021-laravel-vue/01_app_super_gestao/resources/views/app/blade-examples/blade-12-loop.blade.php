{{-- --}}

@isset($fornecedores)

    @forelse($fornecedores as $indice => $fornecedor)

        <br>
        Fornecedor: {{ $fornecedor['nome'] }}
        <br>
        Status: {{ $fornecedor['status'] }}
        <br>
        CNPJ: {{ $fornecedor['cnpj'] ?? '' }}
        <br>
        Telefone: ({{ $fornecedor['ddd'] ?? '' }}) {{ $fornecedor['telefone'] ?? '' }}
        <br>

        Iteração atual: {{ $loop->iteration }} {-- imprime index numerico --}

        @if($loop->first)
            Primeira iteração no loop
            <br>
            Total de registros: {{ $loop->count }}
        @endif

        @if($loop->last)
            Última iteração no loop
        @endif
        
        <hr>
    @empty
        Não existem fornecedores cadastrados!!!
    @endforelse
@endisset

