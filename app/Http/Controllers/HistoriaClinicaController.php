<?php

namespace App\Http\Controllers;

use App\AlmacenarHistoriaClinica;
use App\DocumentoLaboratorio;
use App\HistoriaClinica;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;



class HistoriaClinicaController extends Controller
{

    public function UploadPerfil(Request $request)
    {

        $image = $request->file('file0');
        //Guardar Imange

        //Validacion de imagen
        $validator = \Validator::make($request->all(), [
            'file0' => 'required|image|mimes:jpg,jpeg,png,gif'
        ]);
        if (!$image || $validator->fails()) {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'El imagen no se ha subido',

            );
        } else {
            $imange_name = time() . $image->getClientOriginalName();
            \Storage::disk('historiaclinica')->put($imange_name, \File::get($image));

            $data = array(
                'status' => 'succes',
                'code' => 200,
                'imagen' => $imange_name,

            );
        }

        return response()->json($data);
    }

    public function DocumentoLaboratorio(Request $request)
    {
        $pdf = $request->file('file0');
        // dd($pdf);
        $validator = \Validator::make($request->all(), [
            'file0' => 'required|mimes:pdf'
        ]);
        if (!$pdf || $validator->fails()) {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'El imagen no se ha subido',

            );
        } else {
            $pdf_name = $pdf->getClientOriginalName();
            \Storage::disk('documentolaboratorio')->put($pdf_name, \File::get($pdf));
            $data = array(
                'status' => 'succes',
                'code' => 200,
                'pdf' => $pdf_name,

            );
        }

        return response()->json($data);
    }

    public function getImagen($filename)
    {
        $existe = \Storage::disk('historiaclinica')->exists($filename);
        if ($existe) {
            $file = \Storage::disk('historiaclinica')->get($filename);
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


    public function ListaHistoriaPaciente()
    {

        $ListaPaciente = HistoriaClinica::where('vigencia_paciente', 1)
            ->orderBy('id', 'DESC')
            ->get();
        return response()->json($ListaPaciente);
    }

    public function DNIExistentePaciente(Request $request)
    {
        $json = $request->input('json', null);
        $request = json_decode($json, true);
        $emailExistente = HistoriaClinica::Where('DNI', $request)->first();
        if ($emailExistente != null) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function InsertHistoriaPaciente(Request $request)
    {

        if (isset($request)) {
            $diagnosticado = explode(',', $request->diagnostico);
            //Usando la funcion serialize genera una representacion almacenable
            $diagnosticoPaciente = serialize($diagnosticado);
            //Para destraformar el serialize se usa unserialize
            //  $desconvertido=unserialize($diagnostico);
            $fechaActual = date("Y-m-d");
            $datos = array(
                'nCitamed' => $request->NumeroCitaMedica,
                'user_id' => $request->id_user,
                'img_perfil' => $request->Imagen,
                'nombre' => $request->NombrePaciente,
                'apellido' => $request->ApellidoPaciente,
                'sexo' => $request->SexoPaciente,
                'edad' => $request->Edad,
                'dni' => $request->Dni,
                'fecha_nacimiento' => $request->FechaNaciemto,
                'direccion' => $request->Direccion,
                'celular' => $request->Celular,
                'whatsapp' => $request->Whatsapp,
                'email' => $request->Correo,
                'facebook' => $request->NombreFacebook,
                'contactoCentroM' => $request->Formacontactar,
                'motivoCons' => $request->MotivoConsulta,
                'GP' => $request->GP,
                'FUR' => $request->FUR,
                'PAP' => $request->PAP,
                'MAC' => $request->MAC,
                'RAM' => $request->RAM,
                'antecedenteP' => $request->AntecendesPersonales,
                'antecedenteF' => $request->AntecendesFamiliares,
                'pa' => $request->PA,
                't' => $request->T,
                'fc' => $request->FC,
                'fr' => $request->FR,
                'peso' => $request->Peso,
                'talla' => $request->Talla,
                'Comentclinico' => $request->ComentarioExamenClinico,
                'diagnostico' => $diagnosticoPaciente,
                'DocLaboratorio' => $request->documentoLabotario,
                'imageneologia' => $request->imageneologia,
                'pcita' => ($request->proximacita != "null") ? $request->proximacita  : null,
                'vigencia_paciente' => 1,
                "fecha_creacion" => $fechaActual
            );
            if ($request->id_paciente != 'null') {
                $datos += [
                    "id" => $request->id_paciente
                ];
                //   $usuarios = Staff::find($formulario->id_staff)->update($formularioStaff);
                $paciente = HistoriaClinica::find($datos['id'])->update($datos);
                if (!empty($request->documentoLabotario)) {
                    $fechaActual = date('Y-m-d');
                    $Documento = array(
                        'usuario_id' => $request->id_user,
                        'hclinip_id' => $request->id_paciente,
                        'documento' => $request->documentoLabotario,
                        'fecha_documento' => $fechaActual
                    );
                    DocumentoLaboratorio::create($Documento);
                }
                $repuesta = "";
                if ($paciente) {
                    $repuesta = "ok";
                } else {
                    $repuesta = "fallo";
                }
                return response()->json($repuesta);
            } else {
                $paciente = HistoriaClinica::create($datos);
                $DocumentoPaciente = HistoriaClinica::latest('id')->first();
                if (!empty($DocumentoPaciente->DocLaboratorio)) {
                    $fechaActual = date('Y-m-d');
                    $Documento = array(
                        'usuario_id' => $request->id_user,
                        'hclinip_id' => $DocumentoPaciente->id,
                        'documento' => $DocumentoPaciente->DocLaboratorio,
                        'fecha_documento' => $fechaActual
                    );
                    DocumentoLaboratorio::create($Documento);
                }



                $repuesta = "";
                if ($paciente) {
                    $repuesta = "ok";
                } else {
                    $repuesta = "fallo";
                }
                return response()->json($repuesta);
            }
        } else {
            $fallo = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'No hay datos llenos',
            );
            return $fallo;
        }
    }
    public function DesactivarPaciente(Request $request)
    {

        $json = $request->input('json', null);
        $request = json_decode($json, true);
        $ListaPaciente = HistoriaClinica::where('id', $request)->first();
        $ListaPaciente->vigencia_paciente = 0;
        $ListaPaciente->save();
        return response()->json('ok');
    }

    public function TraerHistoriaPaciente($id)
    {
        $ListaPaciente = HistoriaClinica::where('id', $id)->first();
        $diagnostico = unserialize($ListaPaciente->diagnostico);
        $tratamientos = unserialize($ListaPaciente->Tratamiento);
        $ListaPaciente->diagnostico = $diagnostico;
        $ListaPaciente->Tratamiento = $tratamientos;
        return response()->json($ListaPaciente);
    }


    // EXPORTAR EN PDF INDICACIONES DEL PACIENTE
    private function Metodo($id)
    {
        $Hclinica = historiaclinica::where('id', $id)
            ->get()
            ->toArray();
        return $Hclinica;
    }
    public function List($id)
    {
        if (!is_numeric($id) || empty($id)) {
            return back();
        }
        $DocHclinica = $this->Metodo($id);
        $hclaboratorio = $DocHclinica->LaboratioClinico;
        return view('historiaClinica.DocHistoriaclinica.InforHC', compact('DocHclinica', 'id', 'hclaboratorio'));
    }


    public function MostrarPacientePDF($id)
    {
        if (!is_numeric($id) || empty($id)) {
            return back();
        }
        $DocHclinica = $this->Metodo($id);
        $diagnostico = unserialize($DocHclinica[0]['diagnostico']);
        foreach ($diagnostico as $value) {
            if ($value == "") {
                $diag = null;
            } else {
                if ($value == "null") {
                    $diag = null;
                } else {
                    $diag[] = $value;
                }
            }
        }
        $DocHclinica[0]['diagnostico'] = $diag;
        if ($DocHclinica[0]['Tratamiento'] != null) {
            $tratamiento = unserialize($DocHclinica[0]['Tratamiento']);
        } else {
            $tratamiento = null;
        }
        $DocHclinica[0]['Tratamiento'] = $tratamiento;
        $imagen = base64_encode(file_get_contents("https://www.sangeronimohistoriaclinica.com/apiMedico/public/img/sangeronimo.jpg"));
        // dd($imagen);
        $pdf = \PDF::loadView('pdf.historiaClinica', ['historiaM' => $DocHclinica, "imagen" => $imagen]);
        // return $pdf->stream();
        return $pdf->download('Historia del Paciente.pdf');
    }

    public function AlmacenarPaciente(Request $request)
    {
        $json = $request->input('json', null);
        $id_paciente = json_decode($json, true);
        $Hclinica = historiaclinica::where('id', $id_paciente)->first()->toArray();
        $fechaActual = date("Y-m-d");
        // dd($Hclinica);
        $datos = array(
            'usuario_id' => $Hclinica['user_id'],
            'hclinip' => $Hclinica['id'],
            'nCitamed' => $Hclinica['nCitamed'],
            'nombre' => $Hclinica['nombre'],
            'apellido' => $Hclinica['apellido'],
            'sexo' => $Hclinica['sexo'],
            'edad' => $Hclinica['edad'],
            'dni' => $Hclinica['dni'],
            'fecha_nacimiento' => $Hclinica['fecha_nacimiento'],
            'direccion' => $Hclinica['direccion'],
            'celular' => $Hclinica['celular'],
            'whatsapp' => $Hclinica['whatsapp'],
            'email' => $Hclinica['email'],
            'facebook' => $Hclinica['facebook'],
            'contactoCentroM' => $Hclinica['contactoCentroM'],
            'motivoCons' => $Hclinica['motivoCons'],
            'GP' => $Hclinica['GP'],
            'FUR' => $Hclinica['FUR'],
            'PAP' => $Hclinica['PAP'],
            'MAC' => $Hclinica['MAC'],
            'RAM' => $Hclinica['RAM'],
            'antecedenteP' => $Hclinica['antecedenteP'],
            'antecedenteF' => $Hclinica['antecedenteF'],
            'pa' => $Hclinica['pa'],
            't' => $Hclinica['t'],
            'fc' => $Hclinica['fc'],
            'fr' => $Hclinica['fr'],
            'peso' => $Hclinica['peso'],
            'talla' => $Hclinica['talla'],
            'Comentclinico' => $Hclinica['Comentclinico'],
            'diagnostico' => $Hclinica['diagnostico'],
            'Tratamiento' => $Hclinica['Tratamiento'],
            'DocLaboratorio' => $Hclinica['DocLaboratorio'],
            'imageneologia' => $Hclinica['imageneologia'],
            'pcita' => $Hclinica['pcita'],
            "fecha_creacion" => $fechaActual
        );

        $paciente = AlmacenarHistoriaClinica::create($datos);

        if ($paciente) {
            return response()->json('ok');
        } else {
            return response()->json('fallo');
        }
    }

    public function InicioFechaAtendido(Request $repuesta)
    {
        $paciente = AlmacenarHistoriaClinica::whereBetWeen("fecha_creacion", [$repuesta->fechaIncio, $repuesta->FechaFin])
            ->get()
            ->toArray();
        $arraymes = array();
        $Enero = 0;
        $Febrero = 0;
        $Marzo = 0;
        $Abril = 0;
        $Mayo = 0;
        $Junio = 0;
        $Julio = 0;
        $Agosto = 0;
        $Septiembre = 0;
        $Octubre = 0;
        $Noviembre = 0;
        $Diciembre = 0;
        foreach ($paciente as  $elemento) {
            $meses = array(
                "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
                "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
            );
            $fecha = Carbon::parse($elemento['fecha_creacion']);
            $mes = $meses[($fecha->format('n')) - 1];
            array_push($arraymes, $mes);
            switch ($mes) {
                case 'Enero':
                    $Enero++;
                    break;
                case 'Febrero':
                    $Febrero++;
                    break;
                case 'Marzo':
                    $Marzo++;
                    break;
                case 'Abril':
                    $Abril++;
                    break;
                case 'Mayo':
                    $Mayo++;
                    break;
                case 'Junio':
                    $Junio++;
                    break;
                case 'Julio':
                    $Julio++;
                    break;
                case 'Agosto':
                    $Agosto++;
                    break;
                case 'Septiembre':
                    $Septiembre++;
                    break;
                case 'Octubre':
                    $Octubre++;
                    break;
                case 'Noviembre':
                    $Noviembre++;
                    break;
                case 'Diciembre':
                    $Diciembre++;
                    break;
            }
        }
        $cantidadmes = [
            "Enero" => $Enero,
            "Febrero" => $Febrero,
            "Marzo" => $Marzo,
            "Abril" => $Abril,
            "Mayo" => $Mayo,
            "Junio" => $Junio,
            "Julio" => $Julio,
            "Agosto" => $Agosto,
            "Septiembre" => $Septiembre,
            "Octubre" => $Octubre,
            "Noviembre" => $Noviembre,
            "Diciembre" => $Diciembre,
        ];
        $mesAsignado = array();
        foreach ($cantidadmes as $elemento) {
            if ($elemento != 0) {
                array_push($mesAsignado, $elemento);
            }
        }
        $mesesAtendido = array_unique($arraymes);
        $mesEscogido=array();
        foreach ($mesesAtendido as $elemento) {
                array_push($mesEscogido, $elemento);
        }
        $respuesta=array(
            "mes"=>$mesEscogido,
            "cantidadmes"=>$mesAsignado
        );
        return response()->json($respuesta);
    }
    function group_by($key, $data)
    {
        $result = array();

        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }

        return $result;
    }
}
