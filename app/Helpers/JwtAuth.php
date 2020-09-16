<?php

use App\User;
use Firebase\JWT\JWT;


class JwtAuth{

    public $key;
    function __construct()
    {
        $this->key="ESTE-ES-MI-LLAVE-3354335467547";
    }

    public function signup($email,$contra,$getToken=null){
        $user=User::where(array(
            'email' => $email,
            'password' =>$contra
        ))->first();
       
        $signup=false;
        if ($user) {
            $signup=true;
        }
        if ($signup) {
            //Generar un toke y devolver
            $token=array(
                'sub'=> $user->id,
                'email'=>$user->email,
                'nombre'=>$user->nombre,
                'apellido'=>$user->apellido,
                'tipo_usuario'=>$user->role,
                //creacion del dato es el iat create_at
                'iat'=>time(),
                //despues de una semana
                'expiracion'=>time()+(1*24*60*60)
            );

           
            //el HS256 es para cifrar la llave
            $jwt=JWT::encode($token,$this->key,'HS256');
            //decodificando el mismo token
            $decode=JWT::decode($jwt,$this->key,array('HS256'));

            if (is_null($getToken)) {
                return $jwt;
            }else{
                return $decode;
            }

        }else{
            //Generar UN error
            return array('status' =>'error', 'message'=>'Login a Fallado');
        }
    }

    //metodo para decodoficar el toke e usar en los controladores
    //recoger el toker y ver si es correcto o no
    public function checktoken($jwt,$getIdentity=false)
    {
        $auth=false;

        try {
            //Remplaza las comillas y los quitas
            $jwt=str_replace('"','',$jwt);
            $decode=JWT::decode($jwt,$this->key,array('HS256'));
        } catch (\UnexpectedValueException $e) {
            $auth=false;
        }catch(\DomainException $e){
            $auth=false;
        }
        if (isset($decode) &&  is_object($decode) && isset($decode->sub) ) {
            $auth=true;
        }
        if ($getIdentity) {
            return $decode;
        }

        return $auth;
    }

}