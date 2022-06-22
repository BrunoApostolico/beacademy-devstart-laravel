<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = [
            'nomes' => [
                'Bruno',
                'Apostolico'
            ]
        ];

        dd($users); //dump e die
    }

    public function show($id)
    {
        dd('Id do usuário é '. $id);
    }
}
