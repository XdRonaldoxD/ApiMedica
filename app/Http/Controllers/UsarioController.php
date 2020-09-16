<?php

namespace App\Http\Controllers;

use App\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use Barryvdh\DomPDF\Facade as PDF;
use Goutte\Client;
class UsarioController extends Controller
{
    public function register(Request $request)
    {
        $json = $request->input('json', null);

        $params = json_decode($json, true); // convierte en array

        $params_array = array_map('trim', $params); //     //limpiar datos
        $rules = [
            'nombre' => 'required|alpha',
            'apellido' => 'required|alpha',
            'password' => 'required',
            'email' => 'required|email|unique:users', //VALIDANDO EL EMAIL QUE SEA UNICO
        ];
        $validate = Validator::make($params_array, $rules);
        if ($validate->fails()) {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'El usuario no se ha creado',
                'error' => $validate->errors()
            );
            return response()->json($data, 400);
        } else {
            //Cifrar las contraseña - Cifrando 4 veces
            $pwd = hash('sha256', $params_array['password']);

            $user = new User();
            $user->nombre = $params_array['nombre'];
            $user->apellido = $params_array['apellido'];
            $user->email = $params_array['email'];
            $user->password = $pwd;
            $user->role = "ROLE_USER";
            $user->save();
            $data = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'El usuario creado',
                'dato_user' => $user
            );
            return response()->json($data);
        }
    }

    public function EmailExistente(Request $request)
    {
        $json = $request->input('json', null);
        $params = json_decode($json, true);
        $emailExistente = User::Where('email', $params)->first();
        if ($emailExistente != null) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function DNIExistente(Request $request)
    {
        $json = $request->input('json', null);
        $params = json_decode($json, true);
        $emailExistente = User::Where('dni', $params)->first();
        if ($emailExistente != null) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function registerEditDoctor(Request $request)
    {
        $json = $request->input('json', null);
        $params = json_decode($json, true); // convierte en array
        $params_array = array_map('trim', $params); //     //limpiar datos
     
        $rules = [
            'nombre' => 'required',
            'apellido' => 'required',
            // 'dni'=>'required|unique:users',
            'password' => 'required',
            // 'email' => 'required|email|unique:users', //VALIDANDO EL EMAIL QUE SEA UNICO
        ];
        $validate = Validator::make($params_array, $rules);
        if ($validate->fails()) {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'El usuario no se ha creado',
                'error' => $validate->errors()
            );
            return response()->json($data, 400);
        } else {
            if ($params_array['id_doctor']) {
                $pwd = hash('sha256', $params_array['password']);
                $user = User::where('id', $params_array['id_doctor'])->first();
                $user->nombre = $params_array['nombre'];
                $user->apellido = $params_array['apellido'];
                $user->email = $params_array['email'];
                $user->celular = $params_array['celular'];
                $user->direccion = $params_array['direccion'];
                $user->password = $pwd;
                $user->save();
                return response()->json("Actualizado");
            } else {
                //Cifrar las contraseña - Cifrando 4 veces
                $pwd = hash('sha256', $params_array['password']);
                $user = new User();
                $user->nombre = $params_array['nombre'];
                $user->apellido = $params_array['apellido'];
                $user->email = $params_array['email'];
                $user->password = $pwd;
                $user->dni = $params_array['dni'];
                $user->role = $params_array['role'];
                $user->celular = $params_array['celular'];
                $user->direccion = $params_array['direccion'];
                $user->vigencia_users = 1;
                $user->save();
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'El Doctor se a creado',
                    'dato_user' => $user
                );
                return response()->json($data);
            }
        }
    }

    public function login(Request $request)
    {
        $jwtAuth = new \JwtAuth;
        $json = $request->input('json', null);
        $params = json_decode($json, true);
        $params_array = array_map('trim', $params);
        $rules = [
            'password' => 'required',
            'email' => 'required|email'
        ];
        $validate = Validator::make($params_array, $rules);

        if ($validate->fails()) {
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'El usuario no se ha creado',
                'error' => $validate->errors()
            );
        } else {
            $pwd = hash('sha256', $params_array['password']);
            if (isset($params_array['getToken'])) {
                $signup = $jwtAuth->signup($params_array['email'], $pwd, $params_array['getToken']);
            } else {
                $signup = $jwtAuth->signup($params_array['email'], $pwd);
            }
        }
        return response()->json($signup);
    }

    // public function RenuevaToken(Request $request){
    //     $jwtAuth=new \JwtAuth;
    //     $jwtAuth->checktoken();
    // }

    public function ListaUsuario()
    {

        $ListaUser = User::where('vigencia_users', 1)
            ->orderBy('id', 'DESC')
            ->get();
        return response()->json($ListaUser);
    }

    public function DatosUsuario($id_usuario)
    {
        $user = User::find($id_usuario);
        if (is_object($user)) {
            $data = array(
                'status' => 'success',
                'code' => 200,
                'dato_user' => $user
            );
        } else {
            $data = array(
                'status' => 'success',
                'code' => 404,
                'message' => 'El usuario no existe',

            );
        }
        return response()->json($data);
    }



    public function PruebapDF()
    {
        $date = new DateTime();
        // dd($date);
        $pdf = \PDF::loadView('pdf.example', compact($date));
        return $pdf->stream();
    }

    public function DeshabilitarUsuario(Request $request)
    {
        $json = $request->input('json', null);
        $id = json_decode($json, true);
        $user = User::where('id', $id)->first();
        $user->vigencia_users = 0;
        $user->save();
        return response()->json('ok');
    }
    public function TraerDatosDoctor($id)
    {
        $user = User::where('id', $id)->first();
        return response()->json($user);
    }

    //API RENIEC DNIPERU su enlace
    public function DniGetReniec(){
        $client = new Client();
        $dni=75144372;
        $crawler = $client->request('GET', 'https://eldni.com/buscar-por-dni?dni='.$dni);
        $datosnombres = array();
        $crawler->filter('td')->each(function ($node) {
            print $node->text()."\n";
        });
    }
}
