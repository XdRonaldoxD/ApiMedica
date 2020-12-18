<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EnviarMensajeController extends Controller
{
    public function EnviarWhassap(Request $request)
    {
        // dd($request->all());
        $url = 'https://wbiztool.com/api/v1/send_msg/';
        $numero_celular = $request->NumeroTelefono;
        $texto_mensaje = $request->Mensaje;
        $urlFile=$request->ArchivoURL;
        switch ($request->tipoMensaje) {
            case 1:
                $myvars = 'client_id=2259&api_key=d361b5c2243a9b7936b27e819218951713d9911e&whatsapp_client=2488&msg_type=0&phone=' . $numero_celular . '&country_code=51&msg=' . $texto_mensaje;
                $ch = curl_init($url);
                break;
            case 2:
                $myvars = 'client_id=2259&api_key=d361b5c2243a9b7936b27e819218951713d9911e&whatsapp_client=2488&msg_type=0&phone=' . $numero_celular . '&country_code=51&msg=' . $texto_mensaje.'&date=22/08/2018&time=14:00&timezone=IST&img_url='.$urlFile;
                $ch = curl_init( $url );
                break;
            case 3:
                $myvars = 'client_id=2259&api_key=d361b5c2243a9b7936b27e819218951713d9911e&whatsapp_client=2488&msg_type=0&phone=' . $numero_celular . '&country_code=51&msg=' . $texto_mensaje.'&date=22/08/2018&time=14:00&timezone=IST&file_url='.$urlFile;
                $ch = curl_init($url);
                break;
        }
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
        $response = curl_exec($ch);
        return response()->json($response);
    }
}
