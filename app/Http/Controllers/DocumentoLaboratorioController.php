<?php

namespace App\Http\Controllers;

use App\AlmacenarHistoriaClinica;
use App\DocumentoLaboratorio;
use App\HistoriaClinica;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Genert\BBCode\BBCode;

class DocumentoLaboratorioController extends Controller
{
    public function GuardarDocumento(Request $request){
        $pdf = $request->file('file0');
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
            $pdf_name = time() . $pdf->getClientOriginalName();
            \Storage::disk('documentolaboratorio')->put($pdf_name, \File::get($pdf));
            $data = array(
                'status' => 'succes',
                'code' => 200,
                'pdf' => $pdf_name,

            );
        }
        return response()->json($data);
    }


    public function TraerPaciente($id)
    {
        $ListaPaciente = HistoriaClinica::where('id', $id)->first();
        $usuario=User::where('id',$ListaPaciente->user_id)->first()
        ->toArray();
        $ListaPaciente->DocLaboratorio=$usuario['nombre'];

        return response()->json($ListaPaciente);
    }

    public function listaDocumentoLabo($id_paciente){
        $Laboratorio=DocumentoLaboratorio::where('hclinip_id',$id_paciente)
        ->get()
        ->toArray();
        return response()->json($Laboratorio);
    }
    public function InsertarDocumentoLabo(Request $request){
        $fechaActual=date('Y-m-d');
        $Documento=array(
            'usuario_id'=>$request->usuario_id,
            'hclinip_id'=>$request->id_paciente,
            'documento'=>$request->documento_name,
            'fecha_documento' =>$fechaActual
        );
        $resp= DocumentoLaboratorio::create($Documento);
        if ($resp) {
           return response()->json('ok');
        }else{
            return response()->json('error');
        }
    }
    //Mostrando toda la informacion medica del Paciente
    private function Metodo($id){
        $Hclinica = AlmacenarHistoriaClinica::where('hclinip', $id)
        ->get()
        ->toArray();
        return $Hclinica;
    }

    public function obtenerPDF($filename)
    {
        return response( Storage::disk('documentolaboratorio')->get($filename), 200)
        ->header('Content-Type', Storage::disk('documentolaboratorio')
            ->mimeType($filename)
        );
    }

    public function MostrarTodoPDF($id){
        if (!is_numeric($id) || empty($id) ) {
            return back();
        }
            $DocHclinica=$this->Metodo($id);
            for ($i=0; $i <count(($DocHclinica)) ; $i++) { 
                $diagnostico = unserialize($DocHclinica[$i]['diagnostico']);  
                // dd($diagnostico);
                if ($diagnostico[0]=="null") {
                    $diagnostico=null;
                }
                $DocHclinica[$i]['diagnostico'] = $diagnostico;
                if ($DocHclinica[$i]['Tratamiento']!=null) {
                    $tratamiento=unserialize($DocHclinica[$i]['Tratamiento']);
                }else{
                    $tratamiento=null;
                }
                $DocHclinica[$i]['Tratamiento'] = $tratamiento;
            }
            $imagen=base64_encode(file_get_contents("img/sangeronimo.jpg"));

           
            $pdf = \PDF::loadView('pdf.historiaClinica', ['historiaM' => $DocHclinica,"imagen"=>$imagen]);
            // return $pdf->stream();
            return $pdf->download('Historial Clinico del Paciente.pdf');
       
    }

    public function EliminarArchivoPDF(Request $request){
        $json = $request->input('json', null);
        $id_documento = json_decode($json, true);
        $documento_clinico_lab=DocumentoLaboratorio::find($id_documento);
        Storage::disk('documentolaboratorio')->delete($documento_clinico_lab->documento);
        $documento_clinico_lab->delete();
        return response()->json("ok");
        
    }



  
}
