<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
 * Ruta principal
 */
Route::get('/', 'HomeController@index')->name('home');
/**
 * Autogeneración de rutas y vistas para la autenticacion de usuarios
 */
Auth::routes();
/**
 * Rutas para la gente de staff que revisa la información enviada desde las app moviles
 */
Route::prefix('staff')->middleware(['auth'])->group(function(){
    /* Rutas para recuperar la clasificacion y los campos de atributos*/
    //Route::post('camposTierra', 'StaffController@camposTierra' )->name('staffCamposTierra');
    //Route::post('camposEvento', 'StaffController@camposEvento' )->name('staffCamposEvento');

    /**
     * Rutas para la seccion de tierra
     */
    Route::prefix('tierra')->group(function(){
        //Mostrar las subcategorias
        Route::post('subcategorias', 'StaffController@subcategoriasTierra')->name('staffSubcategoriasTierra');
        //Reclasificar un elemento
        Route::post('reclasifica', 'StaffController@tierraReclasifica')->name('staffTierraReclasifica');
        //Mostrar todos los registros
        Route::get('index/{rechazado?}', 'StaffController@tierraIndex')->name('staffTierraIndex');
        //Mostrar el detalle de los registros en el index tanto los activos como los rechazados
        Route::get('detalle', 'StaffController@tierraDetalles')->name('staffTierraDetalles');
        Route::get('detalleRechaza', 'StaffController@tierraDetalleRechaza')->name('staffTierraDetalleRechaza');
        //Mostrar toda la inforamcion del registro para completar datos
        Route::get('completar/{id?}', 'StaffController@tierraCompletar')->name('staffTierraCompletar');
        //Guardar los cambios
        Route::post('completar', 'StaffController@tierraGuardaCambios')->name('staffTierraGuardar');
        //Rechazar un reporte
        Route::post('rechazar', 'StaffController@tierraRechazar')->name('staffTierraRechazar');
        //Contabilizar los reportes activos y rechazados
        Route::get('contabiliza', 'StaffController@tierraContabilizaGral')->name('staffTierraContabiliza');
    });
    /**
     * Rutas para la seccion de eventos
     */
    Route::prefix('evento')->group(function(){
        //Obtiene las subcategorias
        Route::post('subcategorias', 'StaffController@subcategoriasEvento')->name('staffSubcategoriasEvento');
        //Reclasifica un reporte
        Route::post('reclasifica', 'StaffController@eventoReclasifica')->name('staffEventoReclasifica');
        //Muestra los eventos
        Route::get('index/{rechazado?}', 'StaffController@eventoIndex')->name('staffEventoIndex');
        //Muestra el detalle de los eventos en el index
        Route::get('detalle', 'StaffController@eventoDetalles')->name('staffEventoDetalles');
        //Muestra toda la informacion del evento para completar los datos
        Route::get('completar/{id?}', 'StaffController@eventoCompletar')->name('staffEventoCompletar');
        //Obtiene los datos de reporte de una categoria
        Route::get('datosCategoria', 'StaffController@eventoDetalleCategoria')->name('staffEventoDetalleCategoria');
        //Guarda los datos generales de un evento
        Route::post('salvaGenerales', 'StaffController@guardaEventoGenerales')->name('staffEventoGuardaGenerales');
        //Guarda los datos de un reporte de un evento
        Route::post('salvaCategoriaDetalles', 'StaffController@guardaEventoDetalle')->name('staffEventoGuardaDetalles');
        //Manda revisar el evento
        Route::post('mandaRevisar', 'StaffController@mandaEventoRevisar')->name('staffEventoRevisar');
        //Rechaza el evento
        Route::post('rechaza', 'StaffController@rechazaEvento')->name('staffEventoRechaza');
    });
});

/**
 * Rutas para los coordinadores que aprueban
 */
Route::prefix('coordinador')->middleware(['auth'])->group(function(){
    //Route::get('precios', 'CoordinadorController@obtienePrecios')->name('coordinadorObtienePrecios');
    /**
     * Rutas para la seccion de tierra
     */
    Route::prefix('tierra')->group(function(){
        //Muestra los reportes enviados
        Route::get('/', 'CoordinadorController@tierraIndex')->name('coordinadorTierraIndex');
        //Muestra el detalle de un reporte
        Route::get('detalle/{id}', 'CoordinadorController@tierraDetalles')->name('coordinadorTierraDetalles');
        //Aprueba el reporte para su contabilizacion
        Route::post('detalle', 'CoordinadorController@tierraGuardar')->name('coordinadorTierraGuardar');
        //Rechaza un reporte al equipo de staff
        Route::post('rechaza', 'CoordinadorController@tierraRechaza')->name('coordinadorTierraRechaza');
    });
    /**
     * Rutas para la seccion de eventos
     */
    Route::prefix('evento')->group(function(){
        //Muestra todos los eventos enviados
        Route::get('/', 'CoordinadorController@eventoIndex')->name('coordinadorEventoIndex');
        //Muestra el detalle del evento
        Route::get('detalle/{id}', 'CoordinadorController@eventoDetalle')->name('coordinadorEventoDetalles');
        //Muestra los datos de un reporte
        Route::get('datosCategoria', 'CoordinadorController@eventoDetalleCategoria')->name('coordinadorEventoDetalleCategoria');
        //Guarda la informacion de un reporte
        Route::post('salvaCategoriaDetalles', 'CoordinadorController@guardaEventoDetalle')->name('coordinadorEventoGuardaDetalles');
        //Aprueba el evento para su contabilizacion
        Route::post('apruebaEvento', 'CoordinadorController@aprobarEvento')->name('coordinadorEventoAprueba');
        //Rechaza un evento y lo manda al staff
        Route::post('rechazaEvento', 'CoordinadorController@rechazaEvento')->name('coordinadorEventoRechaza');
        //Guarda el precio de la sede del evento
        Route::post('precioSede', 'CoordinadorController@asignaPrecioSedeEvento')->name('coordinadorEventoPrecioSede');
        //Guarda elementos adicionales que agrega al evento
        Route::post('elementoAdicional', 'CoordinadorController@agregaElementoAdicional')->name('coordinadorEventoAdicional');
    });

});


/**
 * Rutas del consultor, revisa solo la informacion aprobada por el coordinador
 */
Route::prefix('consultor')->middleware('auth')->group(function(){
    /**
     * Rutas de tierra
     */
    Route::prefix('tierra')->group(function(){
        //Muestra los elementos de tierra aprobados
        Route::get('/', 'ConsultorController@indexTierra')->name('consultorTierraIndex');
        //Filtra los elementos
        Route::post('/', 'ConsultorController@indexTierraFiltro')->name('consultorTierraIndexFiltrado');
        //Muestra el detalle de un reporte
        Route::get('detalle/{id}', 'ConsultorController@tierraDetalles')->name('consultorTierraDetalles');
        //Muestra los respaldos generados
        Route::get('respaldos', 'ConsultorController@respaldoTierraIndex')->name('consultorTierraRespaldos');
    });
    /**
     * Rutas de eventos
     */
    Route::prefix('evento')->group(function(){
        //Muestra los eventos guardados
        Route::get('/', 'ConsultorController@indexEvento')->name('consultorEventoIndex');
        //Filtra los eventos
        Route::post('/', 'ConsultorController@indexEvento')->name('consultorEventoIndexFiltro');
        //Muestra el detalle del evento
        Route::get('detalle/{id}', 'ConsultorController@eventoDetalles')->name('consultorEventoDetalles');
        //Carga el detalle de un reporte del evento
        Route::get('datosCategoria', 'ConsultorController@eventoDetalleCategoria')->name('consultorEventoDetalleCategoria');
        //Muestra los respaldos generados
        Route::get('respaldos', 'ConsultorController@respaldoEventoIndex')->name('consultorEventoRespaldos');
    });
    //Permite realizar la exportacion y respaldo de la informacion del dia
    Route::post('exportar', 'ConsultorController@exportar')->name('consultorExportar');

    /**
     * Rutas para la generación de reportes
     */
    Route::prefix('reportes')->group(function(){
        Route::get('/', 'ConsultorController@indexReportes')->name('consultorReportesIndex');
        Route::get('eventoPdf/{id}', 'ConsultorController@eventoPdf')->name('consultorReportesEventoPdf');
    });
});
/**
 * Rutas del adminsitrador
 */
Route::prefix('admin')->middleware('auth')->group(function(){
    /**
     * Rutas de los usuarios
     */
    Route::prefix('usuarios')->group(function(){
        //Muestra los usuarios registrados
        Route::get('/', 'UserController@index')->name('usuarios');
        //Muestra el formulario para la creacion
        Route::get('crear', 'UserController@create')->name('crearusuario');
        //Guarda el usuario
        Route::post('nuevo', 'UserController@store')->name('guardausuario');
        //Ve la informacion del usuario
        Route::get('ver/{id}', 'UserController@edit')->name('verusuario');
        //Guarda los cambios del usuario
        Route::post('guardar', 'UserController@update')->name('actualizausuario');
        //Borra un usuario
        Route::get('eliminar/{id}', 'UserController@destroy')->name('borrausuario');
        //Muestra el formulario para cambiar clave de acceso
        Route::get('acceso/{id}', 'UserController@changePassword')->name('cambiapassword');
        //Salva los cambios de clave de acceso
        Route::post('password', 'UserController@updatePassword')->name('guardanuevopassword');
    });
});