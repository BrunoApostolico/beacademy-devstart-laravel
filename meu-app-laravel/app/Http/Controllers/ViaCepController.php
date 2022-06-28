<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\Return_;

class ViaCepController extends Controller
{
    public function index(Request $request)
    {
        if($request->cep)
            return redirect('/viacep/'. $request->cep);

            return view('viacep.index');

    }

    public function show($cep)
    {
        $response = Http::get('http://viacep.com.br/ws/'.$cep.'/json/')->json();

        Return $response;
    }
}
