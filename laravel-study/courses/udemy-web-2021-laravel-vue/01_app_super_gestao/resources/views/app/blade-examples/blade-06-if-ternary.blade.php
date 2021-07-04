<h3>Fornecedor</h3>

@php
    /* IF TERNÁRIO NO PHP

     $fornecedores = [
            0 => [ 'nome' => 'Fornecedor 1','status' => 'N','cnpj' => '00'],
            1 => ['nome' => 'Fornecedor 2', 'status' => 'S']
        ];


        //condicao ? se verdade : se falso;
        //condicao ? se verdade : (condicao ? se verdade : se falso);

        $msg = isset($fornecedores[0]['cnpj']) ? 'CNPJ informado' : 'CNPJ não informado';
        echo $msg;

    */
@endphp

@isset($fornecedores)
    Fornecedor: {{ $fornecedores[0]['nome'] }}
    <br>
    Status: {{ $fornecedores[0]['status'] }}
    <br>
    @isset($fornecedores[0]['cnpj'])
        CNPJ: {{ $fornecedores[0]['cnpj'] }}
        @empty($fornecedores[0]['cnpj'])
            - Vazio
        @endempty
    @endisset
@endisset
