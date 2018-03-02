<?php

namespace App\Http\Controllers;

use App\Evento;
use App\Eventorechazado;
use App\Pubrechazada;
use function GuzzleHttp\default_ca_bundle;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Pubfija;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Date\Date;


class StaffController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        Date::setLocale('es_MX');
    }

    /**
     * Funcion para mostrar el panel de trabajo principal para la parte de tierra
     * únicamente los registros correspondientes a la circunscripcion del usuario
     * @param $rechazado Mostrar activos o rechazados
     * @return Vista staff.tierra.index, variable registros :: staff.tierra.rechazados, variables: registros
     */
    public function tierraIndex(Request $request, int $rechazado=0)
    {
        //Obtenemos los registros de publicidad fija correspondientes a:
        // Circunscripcion del usuario actual
        // Dependiendo si se solicitan como rechazados o no
        // Si no son los rechazados se busca con status 0 y 1
        if(!$rechazado){
            $publicidadFija = Pubfija::project(
                [
                    'id'=>1,
                    'alianza'=>1,
                    'categoria'=>1,
                    'subcategoria'=>1,
                    'cantidad'=>1,
                    'estado'=>1,
                    'direccion'=>1,
                    'ubicacion'=>1,
                    'fecha_envio'=>1,
                    'status'=>1
                ]
            )->where('circunscripcion', Auth::user()->circunscripcion)
                ->where('status', 'exists', true)
                ->where(function($query){
                    $query->where('status', '=',0)
                        ->orWhere(function($qq){
                            $qq->where('status', '=', 1)
                                ->where('revisor','exists',true)
                                ->where('revisor','=',Auth::id());
                        });
                })
                ->orderBy('status', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('staff.tierra.index')
                ->with('registros', $publicidadFija);
        }else{
            $publicidadFija = Pubfija::project(
                [
                    'id'=>1,
                    'alianza'=>1,
                    'categoria'=>1,
                    'subcategoria'=>1,
                    'cantidad'=>1,
                    'estado'=>1,
                    'direccion'=>1,
                    'ubicacion'=>1,
                    'fecha_envio'=>1,
                    'status'=>1
                ]
            )->where('circunscripcion', Auth::user()->circunscripcion)
                ->where('status', 'exists', true)
                ->where('fueRechazado', 'exists', true)
                ->where('status', '=',5)
                ->where('fueRechazado', '=', true)
                ->where('revisor','exists',true)
                ->where('revisor','=',Auth::id())
                ->orderBy('rechazado_en', 'desc')
                ->get();
            //->where('estado_id', '=', Auth::user()->estado_id )
            return view('staff.tierra.rechazados')
                ->with('registros', $publicidadFija);
        }
    }

    /**
     * Funcion para sacar indicadores generales de registros activos y rechazados por usuario
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse activos, rechazados
     */
    public function tierraContabilizaGral(Request $request){
        if($request->ajax() )
        {
            $totalActivos = Pubfija::project([
                'id'=>1
            ])
                ->where('circunscripcion', Auth::user()->circunscripcion)
                ->where('status', 'exists', true)
                ->where(function ($query){
                    return $query->where('status', '=', 0)
                        ->orWhere(function($qq){
                            $qq->where('status', '=', 1)
                                ->where('revisor','exists',true)
                                ->where('revisor','=',Auth::id());
                        });
                })
                ->get()
                ->count();
            $totalRechazo = Pubfija::project([
                'id'=>1
            ])
                ->where('circunscripcion', Auth::user()->circunscripcion)
                ->where('status', 'exists', true)
                ->where('fueRechazado', 'exists', true)
                ->where('revisor','exists',true)
                ->where('status', '=',5)
                ->where('revisor','=',Auth::id())
                ->where('fueRechazado', '=', true)
                ->get()
                ->count();
                //->where('estado_id', '=', Auth::user()->estado_id );*/

            return response()->json(["error"=>false,
                "errmes"=>null,
                "data"=>array('activos'=>$totalActivos, 'rechazados'=>$totalRechazo)],200);
        }else{
            return response()->json(["error"=>true,
                "errmes"=>"Metodo no permitido",
                "data"=>null],404);
        }
    }

    /**
     * Funcion para obtener los detalles de un reporte de Tierra en base a su ID
     * @param Request $request Peticion que contiene el id del documento
     * @return \Illuminate\Http\JsonResponse Documento seleccionado
     */
    public function tierraDetalles(Request $request)
    {
        if($request->filled('id') && $request->ajax() )
        {
            $id = $request->input('id', null);
            try{
                $publicidad = Pubfija::findOrFail($id);
                $publicidad->status = 1;
                $publicidad->revisor = Auth::id();
                $publicidad->save();
                $resultado = array("error"=>false, "errmes"=>"", "data"=> $publicidad );
            }catch(ModelNotFoundException $e){
                $resultado = array(
                    "error"=>true,
                    "errmes"=>"No se encontro el registro",
                    "data"=>null
                );
            }
            return response()->json($resultado);
        }else{
            return response()->json(["error"=>true,
                "errmes"=>"No se recibio el identificador",
                "data"=>null],404);
        }

    }

    /**
     * Funcion para regresar detalles de un registro de los marcados como rechazados
     * @param Request $request Peticion
     * @return \Illuminate\Http\JsonResponse Documento seleccionado
     */
    public function tierraDetalleRechaza(Request $request)
    {
        if($request->filled('id') && $request->ajax() )
        {
            try{
                $id = $request->input('id', null);
                $publicidad = Pubfija::findOrFail($id);
                $resultado = array("error"=>false, "errmes"=>"", "data"=> $publicidad );
            }catch(ModelNotFoundException $e){
                $resultado = array(
                    "error"=>true,
                    "errmes"=>"No se encontro el registro",
                    "data"=>null
                );
            }
            return response()->json($resultado);
        }else{
            return response()->json(["error"=>true,
                "errmes"=>"No se recibio el identificador",
                "data"=>null],404);
        }
    }

    /**
     * Funcion que se llama por el metodo get para obtener la informacion y mostrar el formulario
     * para complementarla
     *
     * @param Request $request Peticion
     * @param $id Identificador del documento
     * @return Vista staff.completar, variables: registro,fotos
     */
    public function tierraCompletar(Request $request, $id)
    {
        if(isset($id))
        {
            try{
                //Obtener documento por el Id
                $documento = Pubfija::findOrFail($id);
                $documento->status = 1;
                $documento->revisor = Auth::id();
                $documento->save();
                //Recuperar Fotos
                $fotos = array();
                if( count( $documento->fotos ) > 0 ){
                    foreach ($documento->fotos as $foto)
                        array_push($fotos, Storage::disk('s3')->url($foto));
                }
                $opcionesPatridos = explode('-', $documento->alianza);
                return view('staff.tierra.completar')
                    ->with('registro', $documento)
                    ->with('partidos', $opcionesPatridos)
                    ->with('fotos', $fotos)
                    ->with('categorias', StaffController::TIERRA_CATEGORIAS);

            }catch(ModelNotFoundException $e){
                return view('shared.complete.404')->with('mensaje', 'No se localizó el registro seleccionado');
            }
        }else{
            return view('shared.complete.404')->with('mensaje', 'No se localizó el registro seleccionado');
        }
    }

    /**
     * Funcion que recibe la peticion para actualizar los datos del documento
     * @param Request $request Peticion POST
     * @return Vista 200 OK o Error 404
     */
    public function tierraGuardaCambios(Request $request)
    {
        if($request->isMethod('post'))
        {
            //Obteniendo atributos estaticos
            $id = $request->input('id', null);
            $cantidad = $request->input('cantidad', 1);
            $referencias = $request->input('referencias', '');
            $compartida =  $request->input('compartida', false);
            $quienes_aparecen = $request->input('quienes_aparecen', null);
            $comentarios = $request->input('comentarios', '');
            $foto = $request->input('foto', 0);
            $partido = $request->input('partido', null);

            //Obteniendo atributos dinamicos excluyendo los datos estaticos recibidos
            $campos = $request->except([
                'id','_token','cantidad', 'referencias', 'compartida', 'quienes_aparecen', 'comentarios', 'foto',
                'partido'
            ]);
            $atributos = array();
            foreach($campos as $key=>$value){
                if(isset($value) && !empty($value) ){
                    if( is_numeric($value) && is_double($value))
                        array_push($atributos, ["nombre"=>$key, "valor"=>doubleval($value)]);
                    elseif(is_numeric($value))
                        array_push($atributos, ["nombre"=>$key, "valor"=>intval($value)]);
                    elseif( is_string($value) && (strtolower($value)==='si' || strtolower($value)==='true' ) )
                        array_push($atributos, ["nombre"=>$key, "valor"=>true]);
                    elseif( is_string($value) && (strtolower($value)==='no' || strtolower($value)==='false' ) )
                        array_push($atributos, ["nombre"=>$key, "valor"=>false]);
                    else
                        array_push($atributos, ["nombre"=>$key, "valor"=>$value]);
                }else{
                    array_push($atributos, ["nombre"=>$key, "valor"=>null]);
                }
            }

            try{
                //Localizo el documento y empiezo a guardar cambios
                $document = Pubfija::findOrFail($id);
                $document->cantidad = ($cantidad>0)?$cantidad:1;
                $document->referencias = $referencias;

                if ( is_string($compartida) && ( strtolower($compartida)=='true' ) )
                    $compartida = true;
                elseif ( is_string($compartida) && ( strtolower($compartida)=='false' ) )
                        $compartida = false;
                elseif ( is_string($compartida) && strtolower($compartida) == 'si' )
                    $compartida = true;
                elseif ( is_string($compartida) && strtolower($compartida) == 'no' )
                    $compartida = false;
                elseif ( is_bool($compartida) )
                    $compartida = (bool) $compartida;

                $document->compartida = $compartida;
                //Obtengo quienes aparecen
                $quienes = $document->quienes_aparecen;
                if( isset($quienes) && !empty($quienes) )
                {
                    //Reseteo valores
                    foreach($quienes as $k => $v)
                        $quienes[$k] = false;
                    //Asigno valores recibidos
                    foreach($quienes_aparecen as $val){
                        if( array_key_exists($val , $quienes) )
                            $quienes[$val] = true;
                    }
                }else{
                    $quienes = array();
                    foreach ($quienes_aparecen as $q )
                        $quienes[$q] = true;
                }
                $document->quienes_aparecen = $quienes;
                $document->comentarios = $comentarios;
                $document->mejor_foto = $foto;
                $document->partido = (!is_null($partido))?implode('-', $partido):$document->alianza;
                $document->status = 2;
                //$document->fecha_revision = new UTCDateTime(strtotime('now')*1000);
                $document->fecha_revision = Date::parse('now', 'America/Mexico_City');
                $atributos_back = $document->atributos;
                $document->atributos_back = $atributos_back;
                $document->atributos = $atributos;
                //Salvamos el documento
                if($document->save())
                    return view('shared.complete.200')
                        ->with('mensaje', 'Los cambios fueron guardados y enviados a revisión.')
                        ->with('destino', 'staffTierraIndex');
                else
                    return view('shared.complete.404')->with('mensaje', 'No se guardaron los cambios al registro');
            }catch(ModelNotFoundException $e){
                return view('shared.complete.404')->with('mensaje', 'No se actualizó la información. '.$e->getMessage());
            }
        }else{
            return view('shared.complete.404')->with('mensaje', 'Método no aceptado.');
        }
    }

    /**
     * Funcion para rechazar el registro desde el staff, se marca con status 9
     * @param Request $request Peticion
     * @return Vista shared.complete.200, shared.complete.404
     */
    public function tierraRechazar(Request $request)
    {
        if( $request->isMethod('post'))
        {
            $id = $request->id;
            try{
                $document = Pubfija::findOrFail($id);
                $document->motivo_staff = $request->input('motivo', 'Sin motivo registrado');
                $prevStatus = $document->status;
                $document->status = 9;
                //$document->fecha_rechazo = new UTCDateTime(strtotime('now')*1000);
                $document->fecha_rechazo = Date::parse('now', 'America/Mexico_City');
                $document->rechazado_por = Auth::id();

                if( $document->save() ){
                    //Creando la copia del documento en otra coleccion y borrandolo de la actual
                    $copy = new Pubrechazada();
                    $copy->precio = (isset($document->precio) && $document->precio>0)?(double)$document->precio:1.0;
                    $copy->alianza = $document->alianza;
                    $copy->partido = $document->partido;
                    $copy->categoria = $document->categoria;
                    $copy->subcategoria = $document->subcategoria;
                    $copy->cantidad = ($document->cantidad>0)?$document->cantidad:1;
                    $copy->atributos = $document->atributos;
                    $copy->compartida = (bool)$document->compartida;
                    $copy->quienes_aparecen = $document->quienes_aparecen;
                    $copy->ubicacion = $document->ubicacion;
                    $copy->direccion = $document->direccion;
                    $copy->estado = $document->estado;
                    $copy->estado_id = $document->estado_id;
                    $copy->circunscripcion = $document->circunscripcion;
                    $copy->fotos = $document->fotos;
                    $copy->mejor_foto = (isset($document->mejor_foto))?$document->mejor_foto:0;
                    $copy->referencias = (isset($document->referencias))?$document->referencias:null;
                    $copy->comentarios = (isset($document->comentarios))?$document->comentarios:null;
                    $copy->usuario = $document->usuario;
                    $copy->revisor = (isset($document->revisor))?$document->revisor:null;
                    $copy->fecha_revision = (isset($document->fecha_revision))?Date::parse($document->fecha_revision, 'America/Mexico_City')->format('Y-m-d H:i:s'):null;
                    $copy->aprobador = (isset($document->aprobador))?$document->aprobador:null;
                    $copy->fecha_rechazo = Date::parse($document->fecha_rechazo, 'America/Mexico_City')->format('Y-m-d H:i:s');
                    $copy->rechazado_por = $document->rechazado_por;
                    $copy->motivo_staff = (isset($document->motivo_staff))?$document->motivo_staff:null;
                    $copy->atributos_back = (isset($document->atributos_back))?$document->atributos_back:null;
                    $copy->fueRechazado = (isset($document->fueRechazado))?$document->fueRechazado:null;
                    $copy->motivo = (isset($document->motivo))?$document->motivo:null;
                    $copy->rechazado_en = (isset($document->rechazado_en))?Date::parse($document->rechazado_en, 'America/Mexico_City')->format('Y-m-d H:i:s'):null;
                    $copy->bck_rechazos = (isset($document->bck_rechazos))?$document->bck_rechazos:null;
                    if( $copy->save() ){
                        $document->delete();
                        return view('shared.complete.200')
                            ->with('mensaje', 'Se registro el rechazo exitosamente.')
                            ->with('destino', 'staffTierraIndex');
                    }else{
                        $document->status = $prevStatus;
                        $document->save();
                        return view('shared.complete.404')->with('mensaje', 'No se rechazo el documento correctamente.');
                    }


                } else{
                    return view('shared.complete.404')->with('mensaje', 'No se guardaron los cambios al registro');
                }
            }catch (ModelNotFoundException $e) {
                return view('shared.complete.404')->with('mensaje', 'No se rechazó el registro. '.$e->getMessage());
            }

        }else{
            return view('shared.complete.404')->with('mensaje', 'Método no aceptado.');
        }
    }

    /**
     * Funcion para obtener las subcategorias de la categoria solicitada de Tierra
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subcategoriasTierra(Request $request)
    {
        if($request->ajax() && $request->isMethod('post'))
        {
            $categoria = $request->input('categoria', null);
            if(!is_null($categoria))
                return response()->json(["subcategoria"=>StaffController::TIERRA_SUBCATEGORIAS[$categoria]], 200);
            else
                return response()->json(["subcategoria"=>null], 404);
        }else{
            return response()->json(["subcategoria"=>null], 500);
        }

    }

    /**
     * Funcion para asignar la categoria y subcategoria a un reporte de tierra
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tierraReclasifica(Request $request)
    {
        if($request->ajax() && $request->isMethod('post'))
        {
            $id = $request->input('elemento', null);
            $categoria = $request->input('categoria', null);
            $subcategoria = $request->input('subcategoria', null);

            try{
                $elemento = Pubfija::project([
                    'id'=>1,
                    'categoria'=>1,
                    'subcategoria'=>1,
                    'atributos'=>1,
                    'atributos_back'=>1
                ])->findOrFail($id);

                if( !empty($categoria) && !empty($subcategoria))
                {
                    $categoriaPasada = $elemento->categoria;
                    $subcategoriaPasada = $elemento->subcategoria;
                    $elemento->categoria = $categoria;
                    $elemento->subcategoria = $subcategoria;
                    $campos = StaffController::TIERRA_FIELDS[$categoria][$subcategoria];
                    $atributos = [];
                    foreach($campos as $campo=>$tipo){
                        array_push($atributos, array(
                            'nombre'=>$campo,
                            'valor'=>''
                        ));
                    }
                    $respaldo = ['categoria'=>$categoriaPasada,
                        'subcategoria'=>$subcategoriaPasada,
                        'atributos'=>$elemento->atributos
                    ];
                    if(isset($elemento->atributos_back))
                    {
                        $temp = array($respaldo);
                        foreach($elemento->atributos_back as $bck)
                            array_push($temp, $bck);
                        $elemento->atributos_back = $temp;
                    }else{
                        $elemento->atributos_back = [$respaldo];
                    }

                    $elemento->atributos = $atributos;

                    if($elemento->save())
                    {
                        return response()->json(['error'=>false, 'categoria'=>$elemento->categoria, 'subcategoria'=>$elemento->subcategoria], 200);
                    }else{
                        return response()->json(['error'=>true, 'mensaje'=>'No se actualizaron los datos'], 500);
                    }
                }else{
                    return response()->json(['error'=>true, 'mensaje'=>'Datos de categoría y subcategoría incorrectos'], 400);
                }

            }catch (ModelNotFoundException $e){
                return response()->json(['error'=>true, 'mensaje'=>'Registro no encontrado'], 404);
            }

        }else{
            return response()->json(['error'=>true, 'mensaje'=>'Metodo no aceptado'], 405);
        }
    }


    /**********************************************************************************************************************/
    /**********************************************************************************************************************/
    /************************************** FUNCIONES PARA PROCESAR LOS EVENTOS *******************************************/
    /**********************************************************************************************************************/
    /**********************************************************************************************************************/


    /**
     * Funcion para listar los eventos relacionados a la circunscripcion del usuario
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function eventoIndex(Request $request, int $rechazado=0)
    {
        //Encontrar los eventos de la circunscripcion del usuario
        if($rechazado) {
            $documents = Evento::project([
                'id' => 1,
                'alianza' => 1,
                'sede' => 1,
                'aforo' => 1,
                'duracion' => 1,
                'fecha' => 1,
                'ubicacion' => 1,
                'status' => 1
            ])
                ->where('circunscripcion', Auth::user()->circunscripcion)
                ->where('status', 'exists', true)
                ->where('fueRechazado', 'exists', true)
                ->where('status', '=',5)
                ->where('fueRechazado', '=', true)
                ->where('revisor','exists',true)
                ->where('revisor','=',Auth::id())
                ->orderBy('fecha_rechazo', 'desc')
                ->get();

            return view('staff.evento.rechazados')
                ->with('eventos', $documents);
        }else{
            $documents = Evento::project([
                '_id' => 1,
                'alianza' => 1,
                'sede' => 1,
                'aforo' => 1,
                'duracion' => 1,
                'fecha' => 1,
                'ubicacion' => 1,
                'status' => 1
            ])
                ->where('circunscripcion', Auth::user()->circunscripcion)
                ->where('status', 'exists', true)
                ->where(function ($query) {
                    return $query->where('status', 0)
                        ->orWhere(function ($query2) {
                            return $query2->where('status', 1)
                                ->where('revisor', 'exists', true)
                                ->where('revisor', Auth::id());
                        });
                })
                ->orderBy('status', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
            return view('staff.evento.index')
                ->with('eventos', $documents);
        }

    }

    /**
     * Funcion para obtener los detalles del evento seleccionado
     * @param Request $request
     */
    public function eventoDetalles(Request $request){
        if($request->filled('id') && $request->ajax() )
        {
            try{
                $id = $request->id;
                $evento = Evento::findOrFail($id);
                $evento->status = 1;
                $evento->revisor = Auth::id();
                $evento->fecha_revisado = Date::parse('now', 'America/Mexico_City');
                $evento->save();
                return response()->json(array(
                    "error"=>false,
                    "errmes"=>"",
                    "data"=> $evento
                ), 200);
            }catch(ModelNotFoundException $e){
                return response()->json(array(
                    "error"=>true,
                    "errmes"=>"No se encontro el registro. ".$e->getMessage(),
                    "data"=>null
                ), 500);
            }

        }else{
            return response()->json(["error"=>true,
                "errmes"=>"No se recibio el identificador",
                "data"=>null],404);
        }
    }

    /**
     * Funcion para mostrar los datos del evento seleccionado
     * @param Request $request
     * @param string $id
     * @return $this
     */
    public function eventoCompletar(Request $request, string $id)
    {
        if(isset($id) && !empty($id) )
        {
            try{
                //Datos Generales
                $evento = Evento::project([
                    'id'=>1,
                    'alianza'=>1,
                    'partido'=>1,
                    'sede'=>1,
                    'aforo'=>1,
                    'duracion'=>1,
                    'compartido'=>1,
                    'fecha'=>1,
                    'ubicacion'=>1,
                    'direccion'=>1,
                    'quienes'=>1,
                    'usuario'=>1,
                    'circunscripcion'=>1,
                    'estado'=>1,
                    'estado_id'=>1,
                    'fueRechazado'=>1,
                    'fecha_rechazo'=>1,
                    'rechazado_por'=>1,
                    'motivo'=>1
                ])->findOrFail($id);
                $evento->status = 1;
                $evento->revisor = Auth::id();
                $evento->fecha_revisado = Date::parse('now', 'America/Mexico_City');
                $evento->save();
                //Obtener reportes enviados en cada categoria
                $reportes = Evento::project([
                    'id'=>1,
                    'estructura'=>1,
                    'espectacular'=>1,
                    'utilitario'=>1,
                    'transporte'=>1,
                    'produccion'=>1,
                    'animacion'=>1
                ])->findOrFail($id);
                //Parsear los datos para adjuntar categoria y url validas al storage
                $dataEstructura = $this->parseaElementosFoto($reportes->estructura, 'estructura', false);
                $dataEspectacular = $this->parseaElementosFoto($reportes->espectacular, 'espectacular', false);
                $dataUtilitario = $this->parseaElementosFoto($reportes->utilitario, 'utilitario', false);
                $dataTransporte = $this->parseaElementosFoto($reportes->transporte, 'transporte', false);
                $dataProduccion = $this->parseaElementosFoto($reportes->produccion, 'produccion', false);
                $dataAnimacion = $this->parseaElementosFoto($reportes->animacion, 'animacion', false);

                $partidos = explode('-', $evento->alianza);

                return view('staff.evento.completar')
                    ->with('evento', $evento)
                    ->with('partidos', $partidos)
                    ->with('estructura', $dataEstructura)
                    ->with('espectacular', $dataEspectacular)
                    ->with('utilitario', $dataUtilitario)
                    ->with('transporte', $dataTransporte)
                    ->with('produccion', $dataProduccion)
                    ->with('animacion', $dataAnimacion)
                    ->with('categorias', StaffController::EVENTO_CATEGORIES);

            }catch (ModelNotFoundException $e){
                return view('shared.complete.404')->with('mensaje', 'No se localizó el evento seleccionado');
            }
        }else{
            return view('shared.complete.404')->with('mensaje', 'No se identificó el evento seleccionado');
        }
    }

    /**
     * Funcion para cargar los detalles de un reporte dea alguna categoria
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function eventoDetalleCategoria(Request $request)
    {
        if( $request->filled('id') &&
            $request->filled('ref') &&
            $request->ajax())
        {
            $id = $request->id;
            $evento_id = $request->ref;
            list($categoria, $numero) = explode('-',$id);

            try{
                $evento = Evento::project([
                    'id'=>1,
                    $categoria=>1
                ])
                ->findOrFail($evento_id);

                $data = $this->parseaElementosFoto($evento->$categoria, $categoria, true);

                return response()->json([
                    "error"=>false,
                    "errmess"=>null,
                    "data"=>$data[$numero]
                ]);

            }catch(ModelNotFoundException $e)
            {
                return response()->json([
                    "error"=>true,
                    "errmess"=>$e->getMessage(),
                    "data"=>null
                ]);
            }
        }else{
            return response()->json([
                "error"=>true,
                "errmess"=>"Datos no recibidos",
                "data"=>null
            ]);
        }
    }

    /**
     * Funcion para guardar los datos generales del evento
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function guardaEventoGenerales(Request $request)
    {
        if( $request->ajax() )
        {
            $duracion  = $request->input('duracion', 0);
            $aforo = $request->input('aforo', 0);
            $compartido = $request->input('compartido', null);
            $quienes_aparecen = $request->input('quienes', null);
            $evento_id = $request->input('evento_id', null);
            $partido = $request->input('partido', null);
            $descripcion = $request->input('descripcion_evento', null);

            if( !empty($duracion) && !empty($aforo) && !empty($compartido) && !empty($quienes_aparecen) )
            {
                try{
                    $evento = Evento::findOrFail($evento_id);
                    $evento->duracion = (double)$duracion;
                    $evento->aforo = (int)$aforo;
                    $evento->compartido = (bool)$compartido;
                    $evento->partido = (!is_null($partido))? implode('-', $partido): $evento->alianza;
                    $evento->descripcion_evento = (string)$descripcion;
                    $quienes = array(
                        'presidente'=>false,
                        'senador'=>false,
                        'diputadoFed'=>false,
                        'gobernador'=>false,
                        'alcalde'=>false
                    );
                    foreach($quienes_aparecen as $val) {
                        if (array_key_exists($val, $quienes))
                            $quienes[$val] = true;
                    }
                    $evento->quienes = $quienes;

                    if( $evento->save() ){
                        return response()->json([
                            'error'=>false,
                            'errmess'=>null,
                            'data'=>null
                        ], 200);
                    }else{
                        return response()->json([
                            'error'=>true,
                            'errmess'=>'No se salvaron los cambios.',
                            'data'=>null
                        ], 500);
                    }
                }catch(ModelNotFoundException $e){
                    return response()->json([
                        'error'=>true,
                        'errmess'=>'No se procesaron los datos. '.$e->getMessage(),
                        'data'=>null
                    ], 404);
                }
            }else{
                return response()->json([
                    'error'=>true,
                    'errmess'=>'Los datos enviados estan incompletos, revisa todos los campos.',
                    'data'=>null
                ], 500);
            }
        }else{
            return response()->json([
                'error'=>true,
                'errmess'=>'Método no aceptado.',
                'data'=>null
            ], 500);
        }
    }

    /**
     * Funcion para guardar los cambios a un elemento reportado
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function guardaEventoDetalle(Request $request)
    {
        if( $request->ajax() )
        {
            list($categoria, $numero)  = explode('-', $request->referencia);
            $evento_id = $request->input('evento_id', null);

            try{
                $evento = Evento::project([
                    'id'=>1,
                    $categoria=>1
                ])->findOrFail($evento_id);

                $elemento = $evento->$categoria[$numero];
                $elemento['cantidad'] = $request->input('cantidad', 1);
                $elemento['comentario_staff'] = $request->input('comentario_staff', 1);
                $atributos = $elemento['atributos'];
                $temporal = array();

                foreach ($atributos as $att)
                {
                    $attTemp = str_replace(' ', '', $att['nombre']);
                    $valor = $request->input($attTemp, null);
                    if ( is_string($valor) && ( strtolower($valor)=='true' || strtolower($valor)=='false' ) )
                        $att['valor'] = (bool) $valor;
                    elseif ( is_string($valor) && strtolower($valor) == 'si' )
                        $att['valor'] = true;
                    elseif ( is_string($valor) && strtolower($valor) == 'no' )
                        $att['valor'] = false;
                    elseif ( is_bool($valor) )
                        $att['valor'] = (bool) $valor;
                    elseif ( is_numeric($valor) )
                        $att['valor'] = (double) $valor;
                    elseif (is_string($valor))
                        $att['valor'] = (string) $valor;
                    elseif( is_null($valor)){
                        $att['valor'] = $valor;
                    }
                    $temporal[] = $att;
                }
                $elemento['atributos'] = $temporal;
                $actuales  = $evento->$categoria;
                $actuales[$numero] = $elemento;
                $evento->$categoria = $actuales;
                if($evento->save()){
                    return response()->json([
                        'error'=>false,
                        'errmess'=>null,
                        'data'=>null
                    ], 200);
                }else{
                    return response()->json([
                        'error'=>true,
                        'errmess'=>'No se guardaron los cambios.',
                        'data'=>null
                    ], 500);
                }
            }catch(ModelNotFoundException $e){
                return response()->json([
                    'error'=>true,
                    'errmess'=>'Ocurrió un error. '.$e->getMessage(),
                    'data'=>null
                ], 500);
            }
        }else{
            return response()->json([
                'error'=>true,
                'errmess'=>'Método no aceptado.',
                'data'=>null
            ], 500);
        }
    }

    /**
     * Funcion para enviar a revision del coordinador un evento
     *
     * @param Request $request
     * @return $this
     */
    public function mandaEventoRevisar(Request $request)
    {
        if( $request->isMethod('post'))
        {
            try{
                $evento = Evento::findOrFail($request->evento_id);
                $evento->comentarios = $request->comentarios;
                $evento->fecha_enviado_revision = Date::parse('now', 'America/Mexico_City');
                $evento->status = 2;
                if( $evento->save() )
                    return view('shared.complete.200')
                        ->with('mensaje','Evento enviado a revisión')
                        ->with('destino','staffEventoIndex');
                else
                    return view('shared.complete.404')
                        ->with('mensaje', 'No se actualizo el evento.');

            }catch (ModelNotFoundException $e) {
                return view('shared.complete.404')
                    ->with('mensaje', 'No se localizo el evento. '.$e->getMessage());
            }
        }else{
            return view('shared.complete.404')->with('mensaje', 'Metodo no aceptado');
        }
    }

    /**
     * Funcion que rechaza un evento desde el staff y lo elimina
     * @param Request $request
     * @return $this
     */
    public function rechazaEvento(Request $request){
        if($request->isMethod('post'))
        {
            $motivo = $request->input('motivo', '');
            $id = $request->input('id', null);
            try{
                $evento = Evento::findOrFail($id);
                //Copiando a nuevo evento
                $rechazado = new Eventorechazado();
                $rechazado->sede = $evento->sede;
                $rechazado->alianza = $evento->alianza;
                $rechazado->partido = $evento->partido;
                $rechazado->aforo = (int)$evento->aforo;
                $rechazado->fecha = Date::parse($evento->fecha, 'America/Mexico_City')->format('Y-m-d H:i:s');
                $rechazado->compartido = $evento->compartido;
                $rechazado->quienes = $evento->quienes;
                $rechazado->duracion = $evento->duracion;
                $rechazado->estado = $evento->estado;
                $rechazado->estado_id = $evento->estado_id;
                $rechazado->circunscripcion = $evento->circunscripcion;
                $rechazado->usuario = $evento->usuario;
                $rechazado->precio = $evento->precio;
                $rechazado->precioSede = $evento->precioSede;
                $rechazado->estructura = $evento->estructura;
                $rechazado->espectacular = $evento->espectacular;
                $rechazado->utilitario = $evento->utilitario;
                $rechazado->transporte = $evento->transporte;
                $rechazado->produccion = $evento->produccion;
                $rechazado->animacion = $evento->animacion;
                $rechazado->adicionales = $evento->adicionales;
                $rechazado->comentarios = $evento->comentarios;
                $rechazado->revisor = $evento->revisor;
                $rechazado->fecha_revisado = Date::parse($evento->fecha_revisado,'America/Mexico_City')->format('Y-m-d H:i:s');
                $rechazado->fecha_enviado_revision = Date::parse($evento->fecha_enviado_revision,'America/Mexico_City')->format('Y-m-d H:i:s');
                $rechazado->fueRechazado = $evento->fueRechazado;
                $rechazado->motivo = $evento->motivo;
                $rechazado->fecha_rechazo = Date::parse($evento->fecha_rechazo,'America/Mexico_City')->format('Y-m-d H:i:s');
                $rechazado->rechazado_por = $evento->rechazado_por;
                $rechazado->bck_rechazos = $evento->bck_rechazos;
                $rechazado->rechazado_staff = Auth::id();
                $rechazado->fecha_rechazo_staff = Date::parse('now', 'America/Mexico_City');
                $rechazado->motivo_staff = $motivo;
                if( $rechazado->save())
                {
                    //Guardando datos de rechazo
                    $evento->rechazado_staff = Auth::id();
                    $evento->fecha_rechazo_staff = Date::parse('now', 'America/Mexico_City');
                    $evento->motivo_staff = $motivo;
                    $evento->status = 9;
                    $evento->save();
                    //Eliminando evento
                    if( $evento->delete() )
                    {
                        return view('shared.complete.200')
                            ->with('mensaje', 'Evento eliminado correctamente')
                            ->with('destino', 'staffEventoIndex');
                    }else{
                        return view('shared.complete.404')
                            ->with('mensaje', 'No se eliminó el evento');
                    }
                }else{
                    return view('shared.complete.404')
                        ->with('mensaje', 'Error al rechazar el evento');
                }

            }catch(ModelNotFoundException $e){
                return view('shared.complete.404')
                    ->with('mensaje', 'No se localizo el evento');
            }
        }else{
            return view('shared.complete.404')
                ->with('mensaje', 'Método no aceptado');
        }
    }

    /**
     * Funcion para obtener las subcategorias de la categoria solicitada de Tierra
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subcategoriasEvento(Request $request)
    {
        if($request->ajax() && $request->isMethod('post'))
        {
            $categoria = $request->input('categoria', null);
            if(!is_null($categoria))
                return response()->json(["subcategoria"=>StaffController::EVENTO_SUBCATEGORIES[$categoria]], 200);
            else
                return response()->json(["subcategoria"=>null], 404);
        }else{
            return response()->json(["subcategoria"=>null], 500);
        }

    }

    /**
     * Funcion para reclasificar un elemento reportado en un evento
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function eventoReclasifica(Request $request)
    {
        if($request->ajax() && $request->isMethod('post'))
        {
            $id = $request->input('elemento', null);
            $categoria = $request->input('categoria', null);
            $subcategoria = $request->input('subcategoria', null);
            $referencia = $request->input('referencia', null);
            if( !empty($referencia) &&
                !empty($categoria) &&
                !empty($subcategoria) &&
                !empty($id)
            )
            {
                list($categoriaPasada, $numero) = explode('-',$referencia);
                $categoriaPasada = strtolower($categoriaPasada);
                $categoria = strtolower($categoria);
                $subcategoria = strtolower($subcategoria);

                try{
                    $evento = Evento::project([
                        'id'=>1,
                        $categoriaPasada=>1,
                        $categoria=>1,
                    ])->findOrFail($id);

                    $elemento = $evento->$categoriaPasada[$numero];
                    $subcategoriaPasada =$elemento['subcategoria'];
                    $elemento['subcategoria'] = $subcategoria;

                    $campos = StaffController::EVENTO_FIELDS[$categoria][$subcategoria];
                    $atributos = [];
                    foreach($campos as $key=>$tipo){
                        array_push($atributos, array(
                            'nombre'=>$key,
                            'valor'=>''
                        ));
                    }

                    $respaldo = array('categoria'=>$categoriaPasada, 'subcategoria'=>$subcategoriaPasada
                    ,'atributos'=>[]);
                    foreach($elemento['atributos'] as $att){
                        array_push($respaldo['atributos'], $att);
                    }


                    $temp = array($respaldo);
                    if(isset($elemento['atributos_back']) ){
                        foreach($elemento['atributos_back'] as $att)
                            array_push($temp, $att);

                    }

                    $elemento['atributos_back'] = $temp;
                    $elemento['atributos'] = $atributos;

                    $pasados = [];

                    foreach ($evento->$categoriaPasada as $index=>$item){
                        if($index != $numero)
                            array_push($pasados, $item);
                    }

                    $evento->$categoriaPasada = $pasados;

                    $nuevos = [$elemento];
                    if(isset($evento->$categoria)){
                        foreach($evento->$categoria as $item)
                            array_push($nuevos, $item);
                    }
                    $evento->$categoria = $nuevos;

                    if($evento->save())
                    {
                        return response()->json(['error'=>false], 200);
                    }else{
                        return response()->json(['error'=>true, 'mensaje'=>'No se actualizaron los datos'], 500);
                    }
                }catch (ModelNotFoundException $e){
                    return response()->json(['error'=>true, 'mensaje'=>'Registro no encontrado'], 404);
                }

            }else{
                return response()->json(['error'=>true, 'mensaje'=>'Datos incorrectos'], 400);
            }
        }else{
            return response()->json(['error'=>true, 'mensaje'=>'Metodo no aceptado'], 405);
        }
    }
/**********************************************************************************************************************/
/**********************************************************************************************************************/
/**********************************************************************************************************************/
/**********************************************************************************************************************/
/**********************************************************************************************************************/
/**********************************************************************************************************************/
    /**
     * Ejemplo de query agrupado por partido obteniendo registros
     * y como recorrerlo para obtener sus propiedades
     */
    protected function queryAgrupado()
    {
        //Obteniendo los registro de publicidad fija reportados que aun no estan atendidos
        /*$publicidadFija = Pubfija::where('status', 'exists', true)
            ->where('status',0)
            ->orderBy('created_at', 'desc')
            ->get();
        */

        //Obtenendo todos los registros agrupados por partidos
        $data = Pubfija::raw(function($collection)
        {
            return $collection->aggregate([
                [
                    '$group'    => [
                        '_id'   => '$partido',
                        'registros' => [
                            '$push'  => '$$ROOT'
                        ]
                    ]
                ]
            ]);
        });

        $temp = "";
        //Por cada fila
        foreach($data as $row){
            //Obtengo el partido
            $temp .= $row->_id."==="; //Partido
            //Se crea una coleccion de objetos del tipo Pubfija (modelo)
            $registros = Pubfija::hydrate($row->registros->bsonSerialize());
            //Por cada objeto puedo acceder a sus propiedades
            foreach($registros as $fila){
                $temp.= $fila->categoria."-";
            }

        }
        dd($temp);
    }

}
