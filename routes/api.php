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






Route::post('login','UsuarioController@login');


Route::get('pdf','UsuarioController@PruebapDF');

Route::post('registrado','UsuarioController@register');

Route::get('consultaDNI','UsuarioController@DniGetReniec');

Route::name('print')->get('/imprimir', 'GeneradorController@imprimir');
    //Paciente ARCHVIO(IMAGEN Y PDF)
Route::post('login/Paciente/perfil/update','HistoriaClinicaController@UploadPerfil');
Route::post('login/Paciente/pdf/update','HistoriaClinicaController@DocumentoLaboratorio');
Route::get('login/perfil/{filename}','HistoriaClinicaController@getImagen');


Route::get('Indicaciones/Doctor/Exportar/{id}', 'IndicacionesDocController@ExportarPdf');

Route::get('Paciente/Reporte/{id?}','HistoriaClinicaController@MostrarPacientePDF');

Route::get('Paciente/Reporte/HistoriaClinica/{id?}','DocumentoLaboratorioController@MostrarTodoPDF');
Route::get('Paciente/Reporte/Documento/{file_name}','DocumentoLaboratorioController@obtenerPDF');

Route::post('EliminarSession','UsuarioController@EliminarSesion');
Route::get('imagenUsuario/{foto}','UsuarioController@getImagenUsuario');

Route::post('usuarios/listado','UsuarioController@ListaUsuario');



Route::middleware('api.auth', 'throttle:60,1')->group(function () {
//MEDICOS-USUARIOS
    // Route::post('login/usuarion/update','UsuarioController@updateUser');
    Route::get('login/usuarion/datouser/{id_usuario}','UsuarioController@DatosUsuario');
    //Registrar Doctor(users)
    Route::post('registrarEditDoctor','UsuarioController@registerEditDoctor');
    Route::post('EmailExistente','UsuarioController@EmailExistente');
    Route::post('DniExistente','UsuarioController@DNIExistente');
    Route::post('usuarios/listado','UsuarioController@ListaUsuario');
    Route::post('usuarios/listado/deshablitado','UsuarioController@ListaUsuarioDeshabilitado');
    Route::post('Deshabilitar','UsuarioController@DeshabilitarUsuario');
    Route::post('habilitar','UsuarioController@habilitarUsuario');
    Route::post('ConsultarUsuario','UsuarioController@ConsultaUsuario');
    Route::get('GetDoctorId/{id}','UsuarioController@TraerDatosDoctor');

    //Perfil Doctor(user)
    Route::post('UpdatePerfil/Medico','UsuarioController@actualizarUsuario');
 

    //Paciente Historia Clinica
    Route::post('PacienteHistoria/listado','HistoriaClinicaController@ListaHistoriaPaciente');
    Route::post('PacienteHistoria/listado/Deshabilitado','HistoriaClinicaController@ListaHistoriaPacienteDeshabilitado');
    Route::post('DniExistentePaciente','HistoriaClinicaController@DNIExistentePaciente');
    Route::post('InsertarPaciente','HistoriaClinicaController@InsertHistoriaPaciente');
    Route::get('TraerPaciente/{id}','HistoriaClinicaController@TraerHistoriaPaciente');
    Route::post('DeshabilitarPaciente','HistoriaClinicaController@DesactivarPaciente');
    Route::post('habilitarPaciente','HistoriaClinicaController@ActivarPaciente');
    Route::post('AlmacenarPaciente','HistoriaClinicaController@AlmacenarPaciente');
    Route::post('TratamientoPaciente/Eliminar','IndicacionesDocController@EliminarTratamientoPaciente');

    // DOCUMENTO LABORATORIO
    Route::post('TraerDocumento','DocumentoLaboratorioController@listaDocumentoLabo');
    Route::post('Insert/Documentos/Laboratorio','DocumentoLaboratorioController@InsertarDocumentoLabo');
    Route::get('TraerDatoPaciente/{id}','DocumentoLaboratorioController@TraerPaciente');
    Route::post('Borrar/Documentos/Laboratorio','DocumentoLaboratorioController@EliminarArchivoPDF');

    //ENVIAR MENSAJE WHASSAP
    Route::post('Enviar/Mensaje/Whatsapp','EnviarMensajeController@EnviarWhassap');

    
    
    //Indiciciones Medicas
    Route::post('Insert/IndicacionesDoctor/Medicas','IndicacionesDocController@IndicacionesMedicas');
    Route::get('Mostrar/Indicaciones/Medicas/{id}','IndicacionesDocController@MostrarIndicaciones');
    Route::post('Eliminar/Indicaciones','IndicacionesDocController@EliminarIndicacion');
    Route::post('Almacenar/Indicaciones','IndicacionesDocController@AlmacenarIndicacion');


    //GRAFICO MEDICOS
    Route::post('listado/Paciente','HistoriaClinicaController@InicioFechaAtendido');



});