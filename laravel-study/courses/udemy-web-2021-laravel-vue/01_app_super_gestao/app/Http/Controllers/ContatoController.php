<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteContato;

class ContatoController extends Controller
{
    public function contato(Request $request) {


        // Forma 1 de Salvar: Colocando parametro por parametro
        $contato = new SiteContato();
        $contato->nome = $request->input('nome');
        $contato->telefone = $request->input('telefone');
        $contato->email = $request->input('email');
        $contato->motivo_contato = $request->input('motivo_contato');
        $contato->mensagem = $request->input('mensagem');
        $contato->save();

		// Forma 2 de Salvar: Usando create com $fillable no model
        $contato = new SiteContato();
        $contato->create($request->all());

		// Acessar a view
        return view('site.contato', ['titulo' => 'Contato (teste)']);
    }

    // Chamado no post do web.php
    public function salvar(Request $request) {

        //realizar a validação dos dados do formulário recebidos no request
        // SE ESTÁ INVÁLIDO, VOLTA AO /contato por GET
        $request->validate([
            'nome' => 'required',
            'telefone' => 'required',
            'email' => 'required',
            'motivo_contato' => 'required',
            'mensagem' => 'required'
        ]);
        // SiteContato::create($request->all());
    }
}
