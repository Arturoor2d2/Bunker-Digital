<?php

namespace App\Http\Controllers;

use App\Costos;
use App\Resevento;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Date\Date;
use App\Pubfija;
use App\Respubfija;
use App\Evento;
use App\User;

class CoordinadorController extends Controller
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
     * Funcion principal deshabilitada
     */
    public function index()
    {
        return view('shared.complete.404')->with('mensaje', 'No permitido');
    }

    /**
     * Funcion para listar los registros de tierra disponibles en la circunscripcion
     * @return Vista coordinador.tierra.index, variables: registros
     */
    public function tierraIndex()
    {
        $publicidadFija = Pubfija::project([
            'id'=>1,
            'partido'=>1,
            'alianza'=>1,
            'categoria'=>1,
            'subcategoria'=>1,
            'fecha_revision'=>1,
            'updated_at'=>1,
            'created_at'=>1,
            'cantidad'=>1,
            'estado'=>1,
            'direccion'=>1
        ])
            ->where('circunscripcion', Auth::user()->circunscripcion)
            ->where('status', 2)
            ->orderBy('fecha_revision', 'desc')
            ->get();

        return view('coordinador.tierra.index')
            ->with('registros', $publicidadFija);
    }

    /**
     * Funcion para mostrar todos los detalles de un documento por su id
     *
     * @param Request $request Peticion
     * @param $id Identificador del documento
     * @return view coordinador.tierra.detalle, variables: registro, revisor, fotos
     */
    public function tierraDetalles(Request $request, $id)
    {
        if(isset($id) && !empty($id))
        {
            try{
                $document = Pubfija::findOrFail($id);
                $usuarioRevisor = User::findOrFail($document->revisor);
                $fotos = array();
                if( count($document->fotos) > 0 )
                {
                    foreach ($document->fotos as $foto)
                        array_push($fotos, Storage::disk('s3')->url($foto));
                }

                return view('coordinador.tierra.detalle')
                    ->with('registro', $document)
                    ->with('revisor', $usuarioRevisor)
                    ->with('fotos', $fotos);
            }catch (ModelNotFoundException $e)
            {
                return view('shared.complete.404')->with('mensaje', 'Registro no encontrado');
            }
        }else{
            return view('shared.complete.404')->with('mensaje', 'No se puede localizar el registro');
        }
    }

    /**
     * Funcion para guardar el registro en la base de datos de aprobados, guardar y cerrar el registro
     * en la base de datos de intermedios
     * @param Request $request Petición
     * @return $this
     */
    public function tierraGuardar(Request $request)
    {
        if($request->isMethod('post'))
        {
            $id = $request->id;
            $cantidad = $request->cantidad;
            $compartida = $request->compartida;
            $quienes_aparecen = $request->quienes_aparecen;
            $precio = $request->precio;
            $foto = $request->foto;
            $campos = $request->except([
                'id','_token','cantidad', 'precio', 'compartida', 'quienes_aparecen', 'foto'
            ]);
            $atributos = array();
            foreach($campos as $key=>$value){
                if(isset($value) && !empty($value) ){
                    if( is_numeric($value) && is_double($value))
                        array_push($atributos, ["nombre"=>$key, "valor"=>doubleval($value)]);
                    elseif(is_numeric($value))
                        array_push($atributos, ["nombre"=>$key, "valor"=>intval($value)]);
                    else
                        array_push($atributos, ["nombre"=>$key, "valor"=>$value]);
                }else{
                    array_push($atributos, ["nombre"=>$key, "valor"=>null]);
                }
            }
            try{
                $document = Pubfija::findOrFail($id);
                //Obtengo quienes aparecen
                $quienes = $document->quienes_aparecen;
                //Reseteo valores
                foreach($quienes as $k => $v)
                    $quienes[$k] = false;
                //Asigno valores recibidos
                foreach($quienes_aparecen as $val){
                    if( array_key_exists($val , $quienes) )
                        $quienes[$val] = true;
                }
                //Creando nuevo registro en base final
                $resultado = new Respubfija();
                $resultado->precio = ($precio>0)?(double)$precio:1.0;
                $resultado->alianza = $document->alianza;
                $resultado->partido = $document->partido;
                $resultado->categoria = $document->categoria;
                $resultado->subcategoria = $document->subcategoria;
                $resultado->cantidad = ($cantidad>0)?$cantidad:1;
                $resultado->atributos = $atributos;
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
                $resultado->compartida = $compartida;
                $resultado->quienes_aparecen = $quienes;
                $resultado->ubicacion = $document->ubicacion;
                $resultado->direccion = $document->direccion;
                $resultado->estado = $document->estado;
                $resultado->estado_id = $document->estado_id;
                $resultado->circunscripcion = $document->circunscripcion;
                $resultado->fotos = $document->fotos;
                $resultado->mejor_foto = $foto;
                $resultado->referencias = $document->referencias;
                $resultado->comentarios = $document->comentarios;
                $resultado->usuario = $document->usuario;
                $resultado->revisor = $document->revisor;
                $resultado->fecha_revision = Date::parse($document->fecha_revision, 'America/Mexico_City')->format('Y-m-d H:i:s');
                $resultado->aprobador = Auth::id();
                //$resultado->creado = new UTCDateTime(strtotime('now')*1000);
                $resultado->creado = Date::parse('now', 'America/Mexico_City');

                //Actualizando y cerrando documento en interfisca
                $document->status = 3;
                $document->aprobado = Date::parse('now', 'America/Mexico_City');
                $document->precio = ($precio>0)?(double)$precio:1.0;
                $document->fueRechazado = false;
                //Guardando cambios
                if( $document->save() )
                {
                    //Guardando en coleccion de resultados
                    if($resultado->save() )
                    {
                        return view('shared.complete.200')
                            ->with('mensaje', 'Se guardo correctamente la aprobación del registro.')
                            ->with('destino', 'coordinadorTierraIndex');
                    }else{
                        return view('shared.complete.404')->with('mensaje', 'Se actualizó el registro pero no se guardaron los nuevos datos: '.$id);
                    }
                }else{
                    return view('shared.complete.404')->with('mensaje', 'No se guardaron los cambios al registro');
                }

            }catch (ModelNotFoundException $e)
            {
                return view('shared.complete.404')->with('mensaje', 'Ocurrió un error al guardar el registro: '.$e->getMessage());
            }

        }else{
            return view('shared.complete.404')->with('mensaje', 'Acción no permitida');
        }
    }

    /**
     * Funcion para marcar como rechazados registros
     * @param Request $request Peticion
     */
    public function tierraRechaza(Request $request)
    {
        if($request->isMethod('post'))
        {
            try{
                $document = Pubfija::findOrFail($request->id);
                $document->status = 5;
                $document->fueRechazado = true;
                $document->motivo = $request->motivo;
                $document->rechazado_en = Date::parse('now', 'America/Mexico_City');
                if(isset($document->bck_rechazos))
                {
                    $arr = $document->bck_rechazos;
                    array_push($arr, ["rechazado_en"=>$document->rechazado_en, "motivo"=>$request->motivo] );
                    $document->bck_rechazos = $arr;
                }else
                    $document->bck_rechazos = array(
                        ["rechazado_en"=>$document->rechazado_en, "motivo"=>$request->motivo]
                    );

                if($document->save()){
                    return response()->redirectToRoute('coordinadorTierraIndex');
                }else{
                    return view('shared.complete.404')->with('mensaje', 'No se logró rechazar el registro');
                }
            }catch (ModelNotFoundException $e){
                return view('shared.complete.404')->with('mensaje', 'No fue posible registrar el cambio'. $e->getMessage());
            }
        }else{
            return view('shared.complete.404')->with('mensaje', 'Acción no permitida');
        }
    }


    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /***************************************** FUNCIONES PARA EVENTOS *************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    /******************************************************************************************************************/

    /**
     * Funcion para obtener todos los eventos disponibles
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function eventoIndex(){

        $eventos = Evento::project([
            'id'=>1,
            'alianza'=>1,
            'partido'=>1,
            'sede'=>1,
            'aforo'=>1,
            'duracion'=>1,
            'fecha'=>1
        ])
            ->where('circunscripcion', Auth::user()->circunscripcion)
            ->where('status', 2)
            ->get();

        return view('coordinador.evento.index')
            ->with('eventos', $eventos);
    }

    /**
     * Funcion para cargar los datos del evento selecionado
     * @param Request $request
     * @param string $id
     * @return $this
     */
    public function eventoDetalle(Request $request, string $id){

        try{
            $evento = Evento::findOrFail($id);
            $staff = User::findOrFail($evento->revisor);

            //Parsear los datos para adjuntar categoria y url validas al storage
            $dataEstructura = $this->parseaElementosFoto($evento->estructura, 'estructura', false);
            $dataEspectacular = $this->parseaElementosFoto($evento->espectacular, 'espectacular', false);
            $dataUtilitario = $this->parseaElementosFoto($evento->utilitario, 'utilitario', false);
            $dataTransporte = $this->parseaElementosFoto($evento->transporte, 'transporte', false);
            $dataProduccion = $this->parseaElementosFoto($evento->produccion, 'produccion', false);
            $dataAnimacion = $this->parseaElementosFoto($evento->animacion, 'animacion', false);
            $dataAdicionales = $this->parseaElementosFoto($evento->adicionales, 'adicionales', false);

            return view('coordinador.evento.detalle')
                ->with('evento', $evento)
                ->with('staff', $staff)
                ->with('estructura', $dataEstructura)
                ->with('espectacular', $dataEspectacular)
                ->with('utilitario', $dataUtilitario)
                ->with('transporte', $dataTransporte)
                ->with('produccion', $dataProduccion)
                ->with('animacion', $dataAnimacion)
                ->with('adicional', $dataAdicionales);

        }catch(ModelNotFoundException $e)
        {
            return view('shared.complete.404')
                ->with('mensaje', 'No se localizó el evento '.$e->getMessage());
        }
    }

    /**
     * Funcion para cargar los datos de un registro del detalle del evento
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

            }catch(ModelNotFoundException $e) {
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
     * Funcion para cargar los datos al reporte del detalle del evento seleccionado
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function guardaEventoDetalle(Request $request){
        if( $request->ajax()
            && $request->isMethod('post')) {
            $referencia = $request->input('referencia', null);
            if(!is_null($referencia))
            {
                list($categoria, $numero) = explode('-', $referencia);
                $evento_id = $request->input('evento_id', null);
                $precioUnitario = (double)$request->input('precio', 0);
                try{
                    $evento = Evento::project([
                        'id'=>1,
                        'precio'=>1,
                        $categoria=>1
                    ])->findOrFail($evento_id);

                    $elemento = $evento->$categoria[$numero];
                    $elemento['cantidad'] = $request->input('cantidad', 1);
                    $elemento['precio'] = $precioUnitario;
                    $atributos = $elemento['atributos'];
                    $temporal = array();
                    foreach ($atributos as $att)
                    {
                        $attTemp = str_replace(' ', '', $att['nombre']);
                        $valor = $request->input($attTemp, null);
                        if ( is_string($valor) && ( strtolower($valor)=='true' || strtolower($valor)=='false' ) )
                            $att['valor'] = (bool) $valor;
                        elseif ( is_string($valor) && (strtolower($valor) == 'si' || strtolower($valor) == 'Si' || strtolower($valor) == 'SI' ) )
                            $att['valor'] = true;
                        elseif ( is_string($valor) && (strtolower($valor) == 'no' || strtolower($valor) == 'No' || strtolower($valor) == 'NO' ) )
                            $att['valor'] = false;
                        elseif ( is_bool($valor) )
                            $att['valor'] = (bool) $valor;
                        elseif ( is_numeric($valor) )
                            $att['valor'] = (double) $valor;
                        elseif (is_string($valor))
                            $att['valor'] = (string) $valor;
                        elseif( is_null($valor)){
                            $att['valor'] = '';
                        }
                        $temporal[] = $att;
                    }
                    $elemento['atributos'] = $temporal;
                    $actuales  = $evento->$categoria;
                    $actuales[$numero] = $elemento;
                    $evento->$categoria = $actuales;

                    if($evento->save()){
                        $valorEvento =  $this->obtenerValorEvento($evento->id);
                        if( is_numeric($valorEvento) ) {
                            $evento->precio = (double)$valorEvento;
                            $evento->save();
                            return response()->json([
                                'error'=>false,
                                'errmess'=>null,
                                'data'=>array('precio'=> $evento->precio)
                            ], 200);
                        }else{
                            return response()->json([
                                'error'=>true,
                                'errmess'=>'No se proceso el valor total del evento. '.$valorEvento,
                                'data'=>array('precio'=> $evento->precio)
                            ], 500);
                        }
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
                    'errmess'=>'Referencia no recibida.',
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
     * Funcion para agregar un elemento adicional
     * @param Request $request
     */
    public function agregaElementoAdicional(Request $request)
    {
        if($request->isMethod('post'))
        {
            $descripcion = $request->input('adicionalDesc', null);
            $cantidad = $request->input('adicionalCantidad', 0);
            $precio = $request->input('adicionalPrecio', 0);
            if( is_numeric($cantidad) && is_numeric($precio) && !empty($descripcion) )
            {
                $nuevoElemento = [
                    'subcategoria'=>"OTROS SERVICIOS",
                    'cantidad'=>(int)$cantidad,
                    'atributos'=>[
                        array('nombre'=>'descripcion', 'valor'=>$descripcion)
                    ],
                    'precio'=>(double)$precio
                ];
                try{
                    $evento = Evento::project([
                        'id'=>1,
                        'precio'=>1,
                        'adicionales'=>1
                    ])->findOrFail($request->input('evento_id', null));

                    if(isset($evento->adicionales))
                    {
                        $elementos = array($nuevoElemento);
                        foreach($evento->adicionales as $item)
                            array_push($elementos, $item);
                        $evento->adicionales = $elementos;
                    }else{
                        $evento->adicionales = array( $nuevoElemento );
                    }

                    if($evento->save())
                    {
                        $preciototal = $this->obtenerValorEvento($evento->id);
                        if(is_numeric($preciototal))
                        {
                            $evento->precio = $preciototal;
                        }else{
                            $evento->precio += $precio;
                        }
                        if($evento->save()){
                            return redirect()->route('coordinadorEventoDetalles', [
                                'id'=>$evento->id
                            ]);
                        }else{
                            return view('shared.complete.404')
                                ->with('mensaje', 'Precio del evento no actualizado');
                        }

                    }else{
                        return view('shared.complete.404')
                            ->with('mensaje', 'Evento no actualizado');
                    }
                }catch(ModelNotFoundException $e)
                {
                    return view('shared.complete.404')
                        ->with('mensaje', 'Evento no localizado');
                }
            }else{
                return view('shared.complete.404')
                    ->with('mensaje', 'Los datos del elemento adicional son incorrectos');
            }
        }else{
            return view('shared.complete.404')
                ->with('mensaje', 'Metodo no aceptado');
        }
    }

    /**
     * Funcion para finalizar un evento
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function aprobarEvento(Request $request){
        if($request->ajax()
            && $request->isMethod('post'))
        {
            $evento_id = $request->input('evento_id', null);
            try{
                $evento = Evento::findOrFail($evento_id);

                $completo = true;
                if($evento->precio == 0 )
                {
                    $completo = false;
                }
                if ($evento->estructura){
                    foreach($evento->estructura as $element) {
                        if ($element['precio'] == 0)
                            $completo = false;
                    }
                }
                if ($evento->espectacular){
                    foreach($evento->espectacular as $element) {
                        if ($element['precio'] == 0)
                            $completo = false;
                    }
                }
                if ($evento->utilitario) {
                    foreach ($evento->utilitario as $element) {
                        if ($element['precio'] == 0)
                            $completo = false;
                    }
                }
                if ($evento->transporte) {
                    foreach ($evento->transporte as $element) {
                        if ($element['precio'] == 0)
                            $completo = false;
                    }
                }
                if ($evento->produccion) {
                    foreach ($evento->produccion as $element) {
                        if ($element['precio'] == 0)
                            $completo = false;
                    }
                }
                if ($evento->animacion) {
                    foreach ($evento->animacion as $element) {
                        if ($element['precio'] == 0)
                            $completo = false;
                    }
                }

                if($completo)
                {
                    $evento->status = 3;
                    $evento->fecha_aprobado = Date::parse('now', 'America/Mexico_City');
                    $evento->aprobado_por = Auth::id();
                    if($evento->save())
                    {
                        $resultado = new Resevento();
                        $resultado->sede = $evento->sede;
                        $resultado->alianza = $evento->alianza;
                        $resultado->partido = $evento->partido;
                        $resultado->aforo = (int)$evento->aforo;
                        $resultado->fecha = Date::parse($evento->fecha, 'America/Mexico_City')->format('Y-m-d H:i:s');
                        $resultado->compartido = $evento->compartido;
                        $resultado->quienes = $evento->quienes;
                        $resultado->duracion = $evento->duracion;
                        $resultado->ubicacion = $evento->ubicacion;
                        $resultado->direccion = $evento->direccion;
                        $resultado->estado = $evento->estado;
                        $resultado->estado_id = $evento->estado_id;
                        $resultado->circunscripcion = $evento->circunscripcion;
                        $resultado->usuario = $evento->usuario;
                        $resultado->precio = $evento->precio;
                        $resultado->estructura = $evento->estructura;
                        $resultado->espectacular = $evento->espectacular;
                        $resultado->utilitario = $evento->utilitario;
                        $resultado->transporte = $evento->transporte;
                        $resultado->produccion = $evento->produccion;
                        $resultado->animacion = $evento->animacion;
                        $resultado->revisor = $evento->revisor;
                        $resultado->fecha_revisado = Date::parse($evento->fecha_revisado,'America/Mexico_City')->format('Y-m-d H:i:s');
                        $resultado->comentarios = $evento->comentarios;
                        $resultado->fecha_enviado_revision = Date::parse($evento->fecha_enviado_revision,'America/Mexico_City')->format('Y-m-d H:i:s');
                        $resultado->fecha_aprobado = Date::parse($evento->fecha_aprobadom,'America/Mexico_City')->format('Y-m-d H:i:s');
                        $resultado->aprobado_por = $evento->aprobado_por;

                        if($resultado->save()){
                            return response()->json([
                                "error"=>false,
                                "errmess"=>null,
                                "data"=>null
                            ], 200);
                        }else{
                            $evento->status = 2;
                            $evento->save();
                            return response()->json([
                                "error"=>true,
                                "errmess"=>"No se guardo el evento.",
                                "data"=>null
                            ], 500);
                        }
                    }else{
                        return response()->json([
                            "error"=>true,
                            "errmess"=>"No se puede actualizar el evento.",
                            "data"=>null
                        ], 500);
                    }
                }else{
                    return response()->json([
                        "error"=>true,
                        "errmess"=>"Revisa que todos los elementos tenga PRECIO/VALOR ESTIMADO",
                        "data"=>null
                    ], 500);
                }

            }catch(ModelNotFoundException $e)
            {
                return response()->json([
                    "error"=>true,
                    "errmess"=>"Identificador incorrecto. ".$e->getMessage(),
                    "data"=>null
                ], 500);
            }
        }else{
            return response()->json([
                "error"=>true,
                "errmess"=>"Método no aceptado",
                "data"=>null
            ], 500);
        }
    }

    /**
     * Funcion para rechazar el evento
     * @param Request $request
     * @return $this
     */
    public function rechazaEvento(Request $request)
    {
        if( $request->isMethod('post'))
        {
            $id = $request->input('evento_id', null);
            $motivo = $request->input('motivo', '');
            try{
                $evento = Evento::findOrFail($id);
                $evento->status = 5;
                $evento->motivo = $motivo;
                $evento->fueRechazado = true;
                $evento->fecha_rechazo = Date::parse('now', 'America/Mexico_City');
                $evento->rechazado_por = Auth::id();
                if(isset($evento->bck_rechazos))
                {
                    $arr = $evento->bck_rechazos;
                    array_push($arr, [
                        "fecha_rechazo"=>Date::parse($evento->fecha_rechazo, 'America/Mexico_City')->format('l j F Y H:i:s'),
                        "motivo"=>$motivo,
                        "rechazado_por"=>$evento->rechazado_por
                    ] );
                    $evento->bck_rechazos = $arr;
                }else {
                    $evento->bck_rechazos = array(
                        ["fecha_rechazo" => Date::parse($evento->fecha_rechazo, 'America/Mexico_City')->format('l j F Y H:i:s'),
                            "motivo" => $motivo,
                            "rechazado_por" => $evento->rechazado_por
                        ]
                    );
                }

                if($evento->save())
                {
                    return view('shared.complete.200')
                        ->with('mensaje', 'El evento fue rechazado.')
                        ->with('destino', 'coordinadorEventoIndex');
                }else{
                    return view('shared.complete.404')
                        ->with('mensaje', 'No se actualizo el evento. ');
                }
            }catch (ModelNotFoundException $e)
            {
                return view('shared.complete.404')
                    ->with('mensaje', 'No se encontro el evento. '.$e->getMessage());
            }
        }else{
            return view('shared.complete.404')
                ->with('mensaje', 'Metodo no aceptado');
        }
    }

    /**
     * Funcion para establecer el costo de la sede del evento
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function asignaPrecioSedeEvento(Request $request)
    {
        if($request->isMethod('post'))
        {
            try{
                $evento = Evento::project(['id'=>1, 'precioSede'=>1, 'precio'=>1])->findOrFail(
                    $request->input('evento', null)
                );

                $precioSede = $request->input('precioSede', 0);
                if(is_numeric($precioSede))
                {
                    $evento->precioSede = (double)$precioSede;

                    if($evento->save()){
                        $precio = $this->obtenerValorEvento($evento->id);
                        if( is_numeric($precio))
                        {
                            $evento->precio = (double)$precio;
                        }else{
                            $evento->precio = $evento->precio + $evento->precioSede;
                        }
                        if($evento->save())
                            return redirect()->route('coordinadorEventoDetalles', ['id'=>$evento->id]);
                        else
                            return view('shared.complete.404')
                                ->with('mensaje', 'Error al salvar al calcular el precio total del evento');
                    }
                    else
                        return view('shared.complete.404')
                            ->with('mensaje', 'Error al salvar el costo de la sede');
                }else{
                    return view('shared.complete.404')
                        ->with('mensaje', 'El Costo de la Sede debe ser un NÙMERO VÁLIDO.');
                }
            }catch (ModelNotFoundException $e)
            {
                return view('shared.complete.404')
                    ->with('mensaje', 'Evento no encontrado');
            }
        }else{
            return view('shared.complete.404')
                ->with('mensaje', 'Metodo no aceptado');
        }
    }

    /**
     * Funcion para obtener costos por cada categoria
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtienePrecios(Request $request)
    {
        if($request->isMethod('get'))
        {
            $costos['estructura'] = Costos::where('categoria','estructura')->orderBy('subcategoria','asc')->get();
            $costos['utilitario'] = Costos::where('categoria','utilitario')->orderBy('subcategoria','asc')->get();
            $costos['animacion'] = Costos::where('categoria','animacion')->orderBy('subcategoria','asc')->get();
            $costos['produccion'] = Costos::where('categoria','produccion')->orderBy('subcategoria','asc')->get();
            $costos['transporte'] = Costos::where('categoria','transporte')->orderBy('subcategoria','asc')->get();
            $costos['espectacular'] = Costos::where('categoria','espectacular')->orderBy('subcategoria','asc')->get();

            return response()->json(['error'=>false, 'data'=>$costos]);
        }else{
            return response()->json(['error'=>true, 'errmess'=>'Metodo no aceptado']);
        }
    }

    /**
     * Funcion para obtener el valor total del evento
     * @param $evento_id
     * @return double|string
     */
    protected function obtenerValorEvento($evento_id)
    {
        try{
            $precio  = 0.0;
            $evento = Evento::project([
                'estructura'=>1,
                'espectacular'=>1,
                'utilitario'=>1,
                'transporte'=>1,
                'produccion'=>1,
                'animacion'=>1,
                'adicionales'=>1,
                'precio'=>1,
                'precioSede'=>1
            ])->findOrFail($evento_id);

            if(isset($evento['estructura']))
            {
                foreach($evento['estructura'] as $element)
                    $precio += (isset($element['precio']))?(double)$element['precio']:0.0;
            }
            if(isset($evento['espectacular']))
            {
                foreach($evento['espectacular'] as $element)
                    $precio += (isset($element['precio']))?(double)$element['precio']:0.0;
            }
            if(isset($evento['utilitario']))
            {
                foreach($evento['utilitario'] as $element)
                    $precio += (isset($element['precio']))?(double)$element['precio']:0.0;
            }
            if(isset($evento['transporte']))
            {
                foreach($evento['transporte'] as $element)
                    $precio += (isset($element['precio']))?(double)$element['precio']:0.0;

            }
            if(isset($evento['produccion']))
            {
                foreach($evento['produccion'] as $element)
                    $precio += (isset($element['precio']))?(double)$element['precio']:0.0;

            }
            if(isset($evento['animacion']))
            {
                foreach($evento['animacion'] as $element)
                    $precio += (isset($element['precio']))?(double)$element['precio']:0.0;
            }
            if(isset($evento['adicionales']))
            {
                foreach($evento['adicionales'] as $element)
                    $precio += (isset($element['precio']))?(double)$element['precio']:0.0;
            }

            if(isset($evento['precioSede']) && $evento['precioSede'] > 0)
            {
                $precio+= (double)$evento['precioSede'];
            }
            return (double)$precio;
        }catch(ModelNotFoundException $e){
            return $e->getMessage();
        }
    }

}
