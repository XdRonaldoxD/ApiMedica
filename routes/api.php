<?php

use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/






Route::post('login','UsarioController@login');


Route::get('pdf','UsarioController@PruebapDF');

Route::post('registrado','UsarioController@register');

Route::get('consultaDNI','UsarioController@DniGetReniec');

Route::name('print')->get('/imprimir', 'GeneradorController@imprimir');
    //Paciente ARCHVIO(IMAGEN Y PDF)
Route::post('login/Paciente/perfil/update','HistoriaClinicaController@UploadPerfil');
Route::post('login/Paciente/pdf/update','HistoriaClinicaController@DocumentoLaboratorio');
Route::get('login/perfil/{filename}','HistoriaClinicaController@getImagen');


Route::get('Indicaciones/Doctor/Exportar/{id}', 'IndicacionesDocController@ExportarPdf');

Route::get('Paciente/Reporte/{id?}','HistoriaClinicaController@MostrarPacientePDF');

Route::get('Paciente/Reporte/HistoriaClinica/{id?}','DocumentoLaboratorioController@MostrarTodoPDF');
Route::get('Paciente/Reporte/Documento/{file_name}','DocumentoLaboratorioController@obtenerPDF');

Route::post('EliminarSession','UsarioController@EliminarSesion');
Route::middleware('api.auth', 'throttle:60,1')->group(function () {
//MEDICOS-USUARIOS
    // Route::post('login/usuarion/update','UsarioController@updateUser');
    Route::get('login/usuarion/datouser/{id_usuario}','UsarioController@DatosUsuario');
    //Registrar Doctor(users)
    Route::post('registrarEditDoctor','UsarioController@registerEditDoctor');
    Route::post('EmailExistente','UsarioController@EmailExistente');
    Route::post('DniExistente','UsarioController@DNIExistente');
    Route::get('usuarios/listado','UsarioController@ListaUsuario');
    Route::post('Deshabilitar','UsarioController@DeshabilitarUsuario');
    Route::get('GetDoctorId/{id}','UsarioController@TraerDatosDoctor');
 

    //Paciente Historia Clinica
    Route::get('PacienteHistoria/listado','HistoriaClinicaController@ListaHistoriaPaciente');
    Route::post('DniExistentePaciente','HistoriaClinicaController@DNIExistentePaciente');
    Route::post('InsertarPaciente','HistoriaClinicaController@InsertHistoriaPaciente');
    Route::get('TraerPaciente/{id}','HistoriaClinicaController@TraerHistoriaPaciente');
    Route::post('DeshabilitarPaciente','HistoriaClinicaController@DesactivarPaciente');
    Route::post('AlmacenarPaciente','HistoriaClinicaController@AlmacenarPaciente');
    Route::post('TratamientoPaciente/Eliminar','IndicacionesDocController@EliminarTratamientoPaciente');

    // DOCUMENTO LABORATORIO
    Route::get('TraerDocumento/{id}','DocumentoLaboratorioController@listaDocumentoLabo');
    Route::post('Insert/Documentos/Laboratorio','DocumentoLaboratorioController@InsertarDocumentoLabo');
    Route::get('TraerDatoPaciente/{id}','DocumentoLaboratorioController@TraerPaciente');
    Route::post('Borrar/Documentos/Laboratorio','DocumentoLaboratorioController@EliminarArchivoPDF');



    
    
    //Indiciciones Medicas
    Route::post('Insert/IndicacionesDoctor/Medicas','IndicacionesDocController@IndicacionesMedicas');
    Route::get('Mostrar/Indicaciones/Medicas/{id}','IndicacionesDocController@MostrarIndicaciones');
    Route::post('Eliminar/Indicaciones','IndicacionesDocController@EliminarIndicacion');
    Route::post('Almacenar/Indicaciones','IndicacionesDocController@AlmacenarIndicacion');






});