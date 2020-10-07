<?php

use App\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Session;

class JwtAuth
{

    public $key;
    function __construct()
    {
        $this->key = "ESTE-ES-MI-LLAVE-3354335467547";
    }

    public function signup($email, $contra, $getToken = null)
    {
        $user = User::where(array(
            'email' => $email,
            'password' => $contra,
            'vigencia_users' => 1
        ))->first();


        if (!empty($user['session_id'])) {
            return array(
                'status' => 'error',
                'message'  => "Se Inicio Session con la cuenta",
                'id_usuario' => $user['id'],
            );
        }
        $signup = false;
        if ($user) {
            $signup = true;
        }
        if ($signup) {
            //Generar un toke y devolver
            $token = array(
                'sub' => $user->id,
                'email' => $user->email,
                'nombre' => $user->nombre,
                'apellido' => $user->apellido,
                'tipo_usuario' => $user->role,
                //creacion del dato es el iat create_at
                'iat' => time(),
                //despues de una semana
                'expiracion' => time() + (1 * 24 * 60 * 60)
            );

            //el HS256 es para cifrar la llave
            $jwt = JWT::encode($token, $this->key, 'HS256');
            //decodificando el mismo token
            $decode = JWT::decode($jwt, $this->key, array('HS256'));

            if (is_null($getToken)) {
            
                return $jwt;
            } else {
                    //INICIO VERIFICACION DATOS EN SESION
                    $new_sessid   = JwtAuth::generarCodigo(12);
                    $user->session_id = $new_sessid;
                    $user->save();

                    $user2 = User::where(array(
                        'email' => $email,
                        'password' => $contra,
                        'vigencia_users' => 1
                    ))->first();

                    $decode = array(
                        'sub' => $user2->id,
                        'email' => $user2->email,
                        'nombre' => $user2->nombre,
                        'apellido' => $user2->apellido,
                        'tipo_usuario' => $user2->role,
                        'session_id'=>$user2->session_id,
                        //creacion del dato es el iat create_at
                        'iat' => time(),
                        //despues de una semana
                        'expiracion' => time() + (1 * 24 * 60 * 60)
                    );

                return $decode;
            }
        } else {
            //Generar UN error
            return array('status' => 'error', 'message' => 'Login a Fallado');
        }
    }

   static function generarCodigo($longitud) {
        $key = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
        $max = strlen($pattern)-1;
        for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
        return $key;
       }

    //metodo para decodoficar el toke e usar en los controladores
    //recoger el toker y ver si es correcto o no
    public function checktoken($jwt, $getIdentity = false)
    {
        $auth = false;

        try {
            //Remplaza las comillas y los quitas
            $jwt = str_replace('"', '', $jwt);
            $decode = JWT::decode($jwt, $this->key, array('HS256'));
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e) {
            $auth = false;
        }
        if (isset($decode) &&  is_object($decode) && isset($decode->sub)) {
            $auth = true;
        }
        if ($getIdentity) {
            return $decode;
        }

        return $auth;
    }
}
