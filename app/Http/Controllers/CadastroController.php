<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Services\ClienteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class   CadastroController extends Controller
{

    public function index(Request $request)
    {
        $data = [];

        return view("cadastro", $data);
    }
    public function cadastro(Request $form)
    {

        $values = $form->all();
        $usuario = new Usuario();

        $usuario->fill($values);
        $usuario->name = $form->input("email", "");

        $senha = $form->input("password", "");
        $usuario->password = Hash::make($senha);

        $clienteServico = new ClienteService();
        $result = $clienteServico->salvarUsuario($usuario);

        $message = $result["message"];
        $status = $result["status"];

        session()->flash($status, $message);

        return redirect()->route('index');
    }


    public function editar( Request $request)
    {
        $data = [];
        
        $usuario = Usuario::find(Auth::user()->id);

        if($request->isMethod("post")){
            $usuario->fill($request->all());
            $usuario->save();

            $data["resp"] = "Cadastro editado com sucesso";
        }

        $data["usuario"] = $usuario;
        return view('editarCadastro' , $data);

    }
}