<?php

namespace App\Http\Controllers;

use App\HistoriaClinica;
use App\IndicacionesDoc;
use App\User;
use Illuminate\Http\Request;

class IndicacionesDocController extends Controller
{
    public function IndicacionesMedicas(Request $request)
    {
        // dd($request->all());
        $datos = array(
            "hclinip" => $request->hclinip,
            "usuario_id" => $request->usuario_id,
            "medicamento" => $request->medicamento,
            "cantidad" => $request->cantidad,
            "formingerir" => $request->formingerir,
            // "dias" => $request->dias!=null ? $request->dias : " "
        );
        $respuesta= IndicacionesDoc::create($datos);
        if ($respuesta) {
            return response()->json('ok');
        }else{
            return response()->json('error');
        }
    }

    public function MostrarIndicaciones($id)
    {
        $Indicaciones = IndicacionesDoc::where('hclinip', $id)->get();
        return response()->json($Indicaciones);
    }
    public function ExportarPdf($id)
    {
        $hclinicaPaciente = HistoriaClinica::select('id','nombre','apellido','nCitamed','dni','edad')
        ->findOrFail($id);
     
        $IndicacionDoctor = $hclinicaPaciente->IndicacionDoc->toArray();
    
        $DatosDoc = IndicacionesDoc::findOrFail($IndicacionDoctor[0]['id'])->toArray();
      
        $nombreDoctor=User::select('id','nombre','apellido')
        ->where('id',$DatosDoc['usuario_id'])
        ->first()
        ->toArray();
        $imagen=base64_encode(file_get_contents("https://www.sangeronimohistoriaclinica.com/apiMedico/public/img/sangeronimo.jpg"));
        $pdf = \PDF::loadView('pdf.indicaciones', ['nombreDoctor' => $nombreDoctor,'IndiDoctor' => $IndicacionDoctor, 'Doctor' => $DatosDoc, 'Paciente' => $hclinicaPaciente,"imagen"=>$imagen]);
        
        return $pdf->stream();
    }
    public function EliminarIndicacion(Request $request){
        $json = $request->input('json', null);
        $params = json_decode($json, true); 
        $respuesta= IndicacionesDoc::where('hclinip',$params)->delete();
        if ($respuesta) {
            return response()->json('ok');
        }else{
            return response()->json('error');
        }
    }

    public function AlmacenarIndicacion(Request $request){
        $json = $request->input('json', null);
        $params = json_decode($json, true); 
        $respuesta= IndicacionesDoc::where('hclinip',$params)->get()->toArray();
        $indicacion=array();
        foreach ($respuesta as  $elemento) {
            $dato=array(
                "indicaciones"=>$elemento['formingerir']
            );
            array_push($indicacion,$dato);
        }
        $tratamientos = serialize($indicacion);
        $hclinica=HistoriaClinica::where('id',$respuesta[0]['hclinip'])->first();
        $hclinica->Tratamiento=$tratamientos;
        if ($hclinica->save()) {
            return response()->json('ok');
        }else{
            return response()->json('error');
        }
    }

    static function EliminarTratamientoPaciente(Request $request){
        $json = $request->input('json', null);
        $params = json_decode($json, true);
        $HistoriaClinica= HistoriaClinica::where('id',$params)->first();
        $HistoriaClinica->Tratamiento="";
        $HistoriaClinica->save();
        return response()->json("ok");
    }


}
