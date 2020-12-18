<?php

namespace App\Http\Controllers;

use App\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
// use Barryvdh\DomPDF\Facade as PDF;
use Goutte\Client;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
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
        $params_array = $request->all(); //     //limpiar datos
        if (!isset($params_array['id_doctor'])) {
            $rules = [
                'nombre' => 'required',
                'apellido' => 'required',
                // 'dni'=>'required|unique:users',
                'password' => 'required',
                // 'email' => 'required|email|unique:users', //VALIDANDO EL EMAIL QUE SEA UNICO
            ];
        } else {
            $rules = [];
        }
        // dd($params_array);
        $validate = Validator::make($request->all(), $rules);;
        if ($validate->fails()) {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'El usuario no se ha creado',
                'error' => $validate->errors()
            );
            return response()->json($data, 400);
        } else {
            if (isset($params_array['id_doctor'])) {
                $pwd = hash('sha256', $params_array['password']);
                $user = User::where('id', $params_array['id_doctor'])->first();
                if ($params_array['password'] != 'null' || $params_array['password'] != null) {
                    $password = $user->password;
                } else {
                    $password = $pwd;
                }
                $user->nombre = $params_array['nombre'];
                $user->apellido = $params_array['apellido'];
                $user->email = $params_array['email'];
                $user->celular = $params_array['celular'];
                $user->direccion = $params_array['direccion'];
                $user->password = $password;
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'El Doctor se actualizo',
                    'dato_user' => $user
                );
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
            }
            // dd($params_array);
            if (isset($params_array['imagen'])) {
                if (!empty($user->path_user)) {
                    unlink(storage_path('app/usuario/' . $user->path_user));
                }
                $nombreArchivo = $params_array['imagen'][0]->getClientOriginalName();
                $nombreArchivo = pathinfo($nombreArchivo, PATHINFO_FILENAME);
                $path = $nombreArchivo . time() . '.' . $params_array['imagen'][0]->getClientOriginalExtension();
                $params_array['imagen'][0]->move(storage_path('app/usuario'), $path);
                $user->path_user = $path;
            }

            $user->save();
            return response()->json($data);
        }
    }


    public function getImagenUsuario($filename)
    {

        $existe = \Storage::disk('mostrarimagen')->exists($filename);
        if ($existe) {
            $file = \Storage::disk('mostrarimagen')->get($filename);
            return new Response($file, 200);
        } else {
            $data = array(
                'status' => 'succes',
                'code' => 200,
                'mensaje' => 'Imagen no existe',

            );
            return response()->json($data);
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

    public function EliminarSesion(Request $request)
    {
        $json = $request->input('json', null);
        $params = json_decode($json, true);
        $usuario = User::where('id', $params)->first();
        if (Session::getHandler()->destroy($usuario->session_id)) {
            $usuario->session_id = null;
            $usuario->save();
            return json_encode('Sesion destruida con exito');
        }
    }


    public function ConsultaUsuario(Request $request)
    {
        $json = $request->input('json', null);
        $params = explode("/", $json);
        $usuario = User::where('id', $params[0])
            ->where("session_id", str_replace('"', '', $params[1]))
            ->first();
        $repuesta = false;
        if (empty($usuario)) {
            $repuesta = true;
        }
        return response()->json($repuesta);
    }

    public function ListaUsuario(Request $request)
    {

        if ($request->length < 1) {
            $longitud = 10;
        } else {
            $longitud = $request->length;
        }
        $buscar = $request->search['value'];
        $recordsFilteredTotal = DB::select("SELECT * FROM users
        where vigencia_users=1 and id!=$request->usuario_id
        and ( nombre LIKE '%$buscar%' or apellido LIKE '%$buscar%' or dni LIKE '%$buscar%'   )");

        $limit = 'LIMIT ' . $longitud . ' OFFSET ' . $request->start;
        $ListaUser = DB::select("SELECT * FROM users
        where vigencia_users=1 and id!=$request->usuario_id
        and ( nombre LIKE '%$buscar%' or apellido LIKE '%$buscar%' or dni LIKE '%$buscar%'   )
        ORDER BY id DESC $limit ");

        $datos = array(
            "draw" => $request->draw,
            "recordsTotal" => count($recordsFilteredTotal),
            "recordsFiltered" => count($recordsFilteredTotal),
            "data" => $ListaUser
        );
        // dd($datos)        ;
        return response()->json($datos);
    }
    public function ListaUsuarioDeshabilitado(Request $request)
    {

        if ($request->length < 1) {
            $longitud = 10;
        } else {
            $longitud = $request->length;
        }
        $buscar = $request->search['value'];
        $recordsFilteredTotal = DB::select("SELECT * FROM users
        where vigencia_users=0 and id!=$request->usuario_id
        and ( nombre LIKE '%$buscar%' or apellido LIKE '%$buscar%' or dni LIKE '%$buscar%'   )");
        $limit = 'LIMIT ' . $longitud . ' OFFSET ' . $request->start;
        $ListaUser = DB::select("SELECT * FROM users
        where vigencia_users=0 and id!=$request->usuario_id
        and ( nombre LIKE '%$buscar%' or apellido LIKE '%$buscar%' or dni LIKE '%$buscar%'   )
        ORDER BY id DESC $limit ");
        $datos = array(
            "draw" => $request->draw,
            "recordsTotal" => count($recordsFilteredTotal),
            "recordsFiltered" => count($recordsFilteredTotal),
            "data" => $ListaUser
        );
        // dd($datos)        ;
        return response()->json($datos);
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
    public function habilitarUsuario(Request $request)
    {
        $json = $request->input('json', null);
        $id = json_decode($json, true);
        $user = User::where('id', $id)->first();
        $user->vigencia_users = 1;
        $user->save();
        return response()->json('ok');
    }
    public function TraerDatosDoctor($id)
    {
        $user = User::where('id', $id)->first();
        return response()->json($user);
    }

    //API RENIEC DNIPERU su enlace
    public function DniGetReniec()
    {
        $client = new Client();
        $dni = 75144372;
        $crawler = $client->request('GET', 'https://eldni.com/buscar-por-dni?dni=' . $dni);
        $datosnombres = array();
        $crawler->filter('td')->each(function ($node) {
            print $node->text() . "\n";
        });
    }

    public function actualizarUsuario(Request $request)
    {

        if (is_numeric($request->id_usuario)) {
            $user = User::findOrfail($request->id_usuario);
            if (isset($request->imagen)) {
                if (!empty($user->path_user)) {
                    unlink(storage_path('app/usuario/' . $user->path_user));
                }
                $nombreArchivo = $request->imagen->getClientOriginalName();
                $nombreArchivo = pathinfo($nombreArchivo, PATHINFO_FILENAME);
                $path = $nombreArchivo . time() . '.' . $request->imagen->getClientOriginalExtension();
                $request->imagen->move(storage_path('app/usuario'), $path);
                $user->path_user = $path;
                $user->save();
            }else{
                if (is_object($user)) {
                    $user->nombre = $request->nombre_usuario;
                    $user->apellido = $request->apellido_usuario;
                    $user->save();
                } 
            }
            $data = array(
                'status' => 'success',
                'code' => 200,
                'data' => $user,
                'message' => 'El usuario actualizado',
    
            );
        } else {
            $data = array(
                'status' => 'error',
                'code' => 202,
                'message' => 'Informacion Errada',

            );
        }
      

        return response()->json($data);
    }
}
