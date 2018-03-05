<?php
namespace App\Http\Controllers;

use App\Resevento;
use App\Respaldos;
use App\Respubfija;
use App\User;
use PDF;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Date\Date;
use MongoDB\BSON\UTCDateTime;

/**
 * Class ConsultorController
 * @package App\Http\Controllers
 *
 * Clase encargada de las funciones de un Consultor
 * - Visualizar los registros de Tierra y Filtrarlo
 * - Visualizar los registros de Eventos y Filtrarlo
 * - Visualizar las exportaciones creadas de Tierra
 * - Visualizar las exportaciones creadas de Evento
 * - Las exportaciones se realizan de todos los registros (de tierra o eventos) disponibles en un periodo
 * de un día, del día actual a las 0 horas hasta mañana a las 0 horas
 */
class ConsultorController extends Controller
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
     * Funcion para regresar elementos registrados de tierra
     * @param Request $request
     * @return $this
     */
    public function indexTierra(Request $request)
    {
        //Obteniendo todos los registros de tierra
        $publicidadFija = Respubfija::project([
            'id' => 1,
            'alianza' => 1,
            'partido' => 1,
            'categoria' => 1,
            'subcategoria' => 1,
            'creado' => 1,
            'fecha_revision' => 1,
            'estado' => 1,
            'direccion' => 1
        ])->orderBy('creado', 'desc')->get();
        //Regresando la vista
        return view('consultor.tierra.index')
            ->with('categorias', StaffController::TIERRA_CATEGORIAS)
            ->with('subcategorias', StaffController::TIERRA_SUBCATEGORIAS)
            ->with('registros', $publicidadFija);
    }

    /**
     * Funcion para mostrar los registros aprobados de tierra utilizando filtros
     * @param Request $request
     * @return $this
     */
    public function indexTierraFiltro(Request $request)
    {
        //Comprobando el metodo http
        if($request->isMethod('post'))
        {
            //Recuperando datos de filtrado
            $categoria = $request->input('categoria', null);
            $alianza = $request->input('alianza', null);
            $subcategoria = $request->input('subcategoria', null);
            //Buscando los elementos con los filtros
            $publicidadFija = Respubfija::project([
                'id' => 1,
                'alianza' => 1,
                'partido' => 1,
                'categoria' => 1,
                'subcategoria' => 1,
                'creado' => 1,
                'fecha_revision' => 1,
                'estado' => 1,
                'direccion' => 1
            ])->where(function($query) use($categoria, $alianza, $subcategoria){
                if(!empty($categoria))
                    $query->where('categoria', '=',$categoria);
                if(!empty($alianza))
                    $query->where('alianza', '=', $alianza);
                if(!empty($subcategoria))
                    $query->where('subcategoria', '=', $subcategoria);
            })->orderBy('creado', 'desc')
                ->get();

        }else {
            //en caso contrario obtener todos los elementos
            $publicidadFija = Respubfija::project([
                'id' => 1,
                'alianza' => 1,
                'partido' => 1,
                'categoria' => 1,
                'subcategoria' => 1,
                'creado' => 1,
                'fecha_revision' => 1,
                'estado' => 1,
                'direccion' => 1
            ])->orderBy('creado', 'desc')->get();
        }
        //Regresar la vista junto con las categorias y subcategorias disponibles
        return view('consultor.tierra.index')
            ->with('categorias', StaffController::TIERRA_CATEGORIAS)
            ->with('subcategorias', StaffController::TIERRA_SUBCATEGORIAS)
            ->with('registros', $publicidadFija);
    }

    /**
     * Funcion para mostar los detalles de un reporte de tierra
     * @param Request $request
     * @param null $id
     * @return $this
     */
    public function tierraDetalles(Request $request, $id=null)
    {
        //Comrpobando el metodo http y la existencia del parametro
        if(isset($id) && !empty($id) && $request->isMethod('get'))
        {
            try{
                //Buscando el registro o en su caso lanzar una excepcion
                $document = Respubfija::findOrFail($id);
                //Buscando datos de usuarios
                $usuarioAprobador = User::findOrFail($document->aprobador);
                $usuarioRevisor = User::findOrFail($document->revisor);
                //Obteniendo URLS de las fotos asociadas
                $fotos = array();
                if( count($document->fotos) > 0 )
                {
                    foreach ($document->fotos as $foto)
                        array_push($fotos, Storage::disk('s3')->url($foto));
                }
                //Regresando la vista con los datos
                return view('consultor.tierra.detalle')
                    ->with('registro', $document)
                    ->with('aprobador', $usuarioAprobador)
                    ->with('revisor', $usuarioRevisor)
                    ->with('fotos', $fotos);
            }catch (ModelNotFoundException $e)
            {
                //En caso de no encontrar el registro mandar mensaje de error
                return view('shared.complete.404')->with('mensaje', 'Registro no encontrado');
            }
        }else{
            //Mandar mensaje de error
            return view('shared.complete.404')->with('mensaje', 'No se puede localizar el registro');
        }
    }

    /**
     * Funcion para obtener los eventos
     * @param Request $request
     * @return $this
     */
    public function indexEvento(Request $request)
    {
        //Evaluando el metodo http
        if($request->isMethod('post'))
        {
            //Recuperando paramentros
            $estado = $request->input('estado', null);
            $alianza = $request->input('alianza', null);
            //Obteniendo los eventos con filtros
            $eventos = Resevento::project([
                'id'=>1,
                'alianza'=>1,
                'sede'=>1,
                'aforo'=>1,
                'fecha'=>1,
                'estado'=>1,
                'precio'=>1
            ])->where(function($query) use($estado, $alianza){
                if(!empty($estado))
                    $query->where('estado_id', '=',$estado);
                if(!empty($alianza))
                    $query->where('alianza', '=', $alianza);
            })->orderBy('fecha', 'desc')
                ->get();
        }else{
            //En caso contrario obtener todos
            $eventos = Resevento::project([
                'id'=>1,
                'alianza'=>1,
                'sede'=>1,
                'aforo'=>1,
                'fecha'=>1,
                'estado'=>1,
                'precio'=>1
            ])->orderBy('fecha', 'desc')->get();
        }
        //Regresan la vista con los datos
        return view('consultor.evento.index')
            ->with('eventos', $eventos);
    }

    /**
     * Funcion para mostrar los detalles de un evento seleccionado
     * @param Request $request
     * @param null $id
     * @return $this
     */
    public function eventoDetalles(Request $request, $id=null)
    {
        //Evaluar el metodo http y los parametros
        if(isset($id) && !empty($id) && $request->isMethod('get'))
        {
            try{
                //Recuperando el evento o mandando excepcion
                $document = Resevento::findOrFail($id);
                //Recuperando los usuarios
                $usuarioAprobador = User::findOrFail($document->aprobado_por);
                $usuarioRevisor = User::findOrFail($document->revisor);
                //Parsear los datos para adjuntar categoria y url validas al storage
                $dataEstructura = $this->parseaElementosFoto($document->estructura, 'estructura', false);
                $dataEspectacular = $this->parseaElementosFoto($document->espectacular, 'espectacular', false);
                $dataUtilitario = $this->parseaElementosFoto($document->utilitario, 'utilitario', false);
                $dataTransporte = $this->parseaElementosFoto($document->transporte, 'transporte', false);
                $dataProduccion = $this->parseaElementosFoto($document->produccion, 'produccion', false);
                $dataAnimacion = $this->parseaElementosFoto($document->animacion, 'animacion', false);
                $dataAdicionales = $this->parseaElementosFoto($document->adicionales, 'adicionales', false);
                //Regresando la vista con los datos
                return view('consultor.evento.detalle')
                    ->with('evento', $document)
                    ->with('aprobador', $usuarioAprobador)
                    ->with('revisor', $usuarioRevisor)
                    ->with('estructura', $dataEstructura)
                    ->with('espectacular', $dataEspectacular)
                    ->with('utilitario', $dataUtilitario)
                    ->with('transporte', $dataTransporte)
                    ->with('produccion', $dataProduccion)
                    ->with('animacion', $dataAnimacion)
                    ->with('adicional', $dataAdicionales);

            }catch(ModelNotFoundException $e){
                //Lanzando excepcion en pantalla de error
                return view('shared.complete.404')->with('mensaje', 'Registro no encontrado'.$e->getMessage());
            }

        }else{
            //Regresar pantalla de error
            return view('shared.complete.404')->with('mensaje', 'No se puede localizar el registro');
        }
    }

    /**
     * Funcion para cargar el detalle de una categoria del evento
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function eventoDetalleCategoria(Request $request)
    {
        //Evaluando paramentros y evaluando tipo de http
        if( $request->filled('id') &&
            $request->filled('ref') &&
            $request->ajax())
        {
            //Recuperando parametros
            $id = $request->id;
            $evento_id = $request->ref;
            list($categoria, $numero) = explode('-',$id);

            try{
                //Recupera los datos de la categoria solicitada
                $evento = Resevento::project([
                    'id'=>1,
                    $categoria=>1
                ])->findOrFail($evento_id);
                //Parseo los elementos para mostrarlos
                $data = $this->parseaElementosFoto($evento->$categoria, $categoria, true);
                //Regresa los datos
                return response()->json([
                    "error"=>false,
                    "errmess"=>null,
                    "data"=>$data[$numero]
                ]);

            }catch(ModelNotFoundException $e) {
                //Regresa error con excepcion capturada
                return response()->json([
                    "error"=>true,
                    "errmess"=>$e->getMessage(),
                    "data"=>null
                ]);
            }
        }else{
            //Regresa error
            return response()->json([
                "error"=>true,
                "errmess"=>"Datos no recibidos",
                "data"=>null
            ]);
        }
    }

    /**
     * Funcion para obtener los respaldos generados anteriormente
     * @param Request $request
     * @return $this
     */
    public function respaldoTierraIndex(Request $request)
    {
        //Recupera los registros de respaldo de tierra
        $data = Respaldos::where('tipo','=','tierra')
            ->orderBy('fecha', 'desc')->get();
        //Parseando los registros incluyendo urls validos
        $respaldos = array();
        foreach ($data as $row)
            array_push($respaldos, array(
                'id'=>$row->id,
                'fecha'=>$row->fecha,
                'ubicacion'=>Storage::disk('s3')->url($row->ubicacion)
            ));
        //Regresando la vista con los datos
        return view('consultor.evento.respaldos')
            ->with('respaldos', $respaldos);
    }

    /**
     * Funcion para preparar los datos de tierra y exportarlos a excel
     * @param $registros
     * @return array
     */
    private function respaldaTierra($registros)
    {
        if(!empty($registros) && count($registros) > 0)
        {
            $data = array();
            foreach($registros as $registro)
            {
                $atributos = '';
                foreach ($registro['atributos'] as $att)
                    $atributos.= ucfirst($att['nombre'])."=".$att['valor'];
                $quienes = '';
                if($registro['quienes_aparecen']['presidente'])
                    $quienes.=' PRESIDENTE,';
                if($registro['quienes_aparecen']['senador'])
                    $quienes.=' SENADOR,';
                if($registro['quienes_aparecen']['diputadoFed'])
                    $quienes.=' DIPUTADO FEDERAL,';
                if($registro['quienes_aparecen']['gobernador'])
                    $quienes.=' GOBERNADOR,';
                if($registro['quienes_aparecen']['alcalde'])
                    $quienes.=' ALCALDE,';
                $quienes = substr($quienes, 0, -1);

                $revisor = User::find($registro->revisor);
                $aprobador = User::find($registro->aprobador);
                $temp = [
                    'categoria'=>$registro['categoria'],
                    'subcategoria'=>$registro['subcategoria'],
                    'alianza'=>$registro['alianza'],
                    'partido'=>$registro['partido'],
                    'precio'=>$registro['precio'],
                    'cantidad'=>$registro['cantidad'],
                    'estado'=>$registro['estado'],
                    'estado_id'=>$registro['estado_id'],
                    'circunscripcion'=>$registro['circunscripcion'],
                    'mejor_foto'=>$registro['mejor_foto'],
                    'numero_fotos'=>count($registro['fotos']),
                    'direccion'=>$registro['direccion'],
                    'ubicacion'=>"long:".$registro['ubicacion']['coordinates'][0].",lat:".$registro['ubicacion']['coordinates'][1],
                    'referencias'=>$registro['referencias'],
                    'comentarios'=>$registro['comentarios'],
                    'atributos'=>$atributos,
                    'compartida'=>$registro['compartida'],
                    'quienes_aparecen'=>$quienes,
                    'usuario_capturo'=>$registro['usuario']['nombre'],
                    'usuario_revisor'=>$revisor->name,
                    'fecha_revision'=>Date::parse($registro['fecha_revision'],'America/Mexico_City')->format('Y-m-d H:i:s'),
                    'usuario_aprobador'=>$aprobador->name,
                    'creado'=>Date::parse($registro['creado'], 'America/Mexico_City')->format('Y-m-d H:i:s'),
                    'created_at'=>Date::parse($registro['created_at'], 'America/Mexico_City')->format('Y-m-d H:i:s'),
                    'updated_at'=>Date::parse($registro['updated_at'],'America/Mexico_City')->format('Y-m-d H:i:s'),
                    'id'=>$registro['id']
                ];
                array_push($data, $temp);
            }
            return $this->crearArchivo($data, 'tierra');
        }else{
            return array('error'=>true,'mensaje'=>'No hay datos que respaldar.');
        }
    }

    /**
     * Funcion
     * @param Request $request
     * @return $this
     */
    public function respaldoEventoIndex(Request $request)
    {
        $data = Respaldos::where('tipo','=','eventos')
            ->orderBy('fecha', 'desc')->get();
        $respaldos = array();
        foreach ($data as $row)
            array_push($respaldos, array(
                'id'=>$row->id,
                'fecha'=>$row->fecha,
                'ubicacion'=>Storage::disk('s3')->url($row->ubicacion)
            ));
        return view('consultor.evento.respaldos')
            ->with('respaldos', $respaldos);
    }

    /**
     * Funcion para respaldar eventos
     * @param $eventos
     * @return array
     */
    private function respaldaEventos($eventos)
    {
        if(!empty($eventos) && count($eventos) > 0)
        {
            $data = array();
            foreach($eventos as $evento)
            {
                $quienes = '';
                if($evento['quienes']['presidente'])
                    $quienes.=' PRESIDENTE,';
                if($evento['quienes']['senador'])
                    $quienes.=' SENADOR,';
                if($evento['quienes']['diputadoFed'])
                    $quienes.=' DIPUTADO FEDERAL,';
                if($evento['quienes']['gobernador'])
                    $quienes.=' GOBERNADOR,';
                if($evento['quienes']['alcalde'])
                    $quienes.=' ALCALDE,';
                $quienes = substr($quienes, 0, -1);

                $revisor = User::find($evento['revisor']);
                $aprobador = User::find($evento['aprobado_por']);

                $reportes = array();
                if(is_array($evento['estructura']) && !empty($evento['estructura']) && count($evento['estructura'])>0)
                {
                    foreach($evento['estructura'] as $item)
                    {
                        $atributos = '';
                        foreach ($item['atributos'] as $att)
                            $atributos.= ucfirst($att['nombre'])."=".$att['valor'];
                        $reportes[] = array(
                            'estructura',
                            $item['subcategoria'],
                            $item['cantidad'],
                            $item['precio'],
                            count($item['evidencia']),
                            $atributos
                        );
                    }
                }
                if(is_array($evento['espectacular']) && !empty($evento['espectacular']) && count($evento['espectacular'])>0)
                {
                    foreach($evento['espectacular'] as $item)
                    {
                        $atributos = '';
                        foreach ($item['atributos'] as $att)
                            $atributos.= ucfirst($att['nombre'])."=".$att['valor'];
                        $reportes[] = array(
                            'espectacular',
                            $item['subcategoria'],
                            $item['cantidad'],
                            $item['precio'],
                            count($item['evidencia']),
                            $atributos
                        );
                    }
                }
                if(is_array($evento['utilitario']) && !empty($evento['utilitario']) && count($evento['utilitario'])>0)
                {
                    foreach($evento['utilitario'] as $item)
                    {
                        $atributos = '';
                        foreach ($item['atributos'] as $att)
                            $atributos.= ucfirst($att['nombre'])."=".$att['valor'];
                        $reportes[] = array(
                            'utilitario',
                            $item['subcategoria'],
                            $item['cantidad'],
                            $item['precio'],
                            count($item['evidencia']),
                            $atributos
                        );
                    }
                }
                if(is_array($evento['transporte']) && !empty($evento['transporte']) && count($evento['transporte'])>0)
                {
                    foreach($evento['transporte'] as $item)
                    {
                        $atributos = '';
                        foreach ($item['atributos'] as $att)
                            $atributos.= ucfirst($att['nombre'])."=".$att['valor'];
                        $reportes[] = array(
                            'transporte',
                            $item['subcategoria'],
                            $item['cantidad'],
                            $item['precio'],
                            count($item['evidencia']),
                            $atributos
                        );
                    }
                }
                if(is_array($evento['produccion']) && !empty($evento['produccion']) && count($evento['produccion'])>0)
                {
                    foreach($evento['produccion'] as $item)
                    {
                        $atributos = '';
                        foreach ($item['atributos'] as $att)
                            $atributos.= ucfirst($att['nombre'])."=".$att['valor'];
                        $reportes[] = array(
                            'produccion',
                            $item['subcategoria'],
                            $item['cantidad'],
                            $item['precio'],
                            count($item['evidencia']),
                            $atributos
                        );
                    }
                }
                if(is_array($evento['animacion']) && !empty($evento['animacion']) && count($evento['animacion'])>0)
                {
                    foreach($evento['animacion'] as $item)
                    {
                        $atributos = '';
                        foreach ($item['atributos'] as $att)
                            $atributos.= ucfirst($att['nombre'])."=".$att['valor'];
                        $reportes[] = array(
                            'animacion',
                            $item['subcategoria'],
                            $item['cantidad'],
                            $item['precio'],
                            count($item['evidencia']),
                            $atributos
                        );
                    }
                }
                if(is_array($evento['adicionales']) && !empty($evento['adicionales']) && count($evento['adicionales'])>0)
                {
                    foreach($evento['adicionales'] as $item)
                    {
                        $atributos = '';
                        foreach ($item['atributos'] as $att)
                            $atributos.= ucfirst($att['nombre'])."=".$att['valor'];
                        $reportes[] = array(
                            'adicionales',
                            $item['subcategoria'],
                            $item['cantidad'],
                            $item['precio'],
                            count($item['evidencia']),
                            $atributos
                        );
                    }
                }

                $temp = ['generales'=>array(
                    'alianza'=>$evento['alianza'],
                    'partido'=>$evento['partido'],
                    'fecha'=>Date::parse($evento['fecha'], 'America/Mexico_City')->format('Y-m-d H:i:s'),
                    'aforo'=>$evento['aforo'],
                    'duracion'=>$evento['duracion'],
                    'compartido'=>($evento['compartido'])?'SI':'NO',
                    'quienes_aparecen'=>$quienes,
                    'sede'=>ucfirst($evento['sede']),
                    'precio_sede'=>$evento['precioSede'],
                    'ubicacion'=>"long:".$evento['ubicacion']['coordinates'][0].",lat:".$evento['ubicacion']['coordinates'][1],
                    'direccion'=>$evento['direccion'],
                    'estado'=>$evento['estado'],
                    'estado_id'=>$evento['estado_id'],
                    'circunscripcion'=>$evento['circunscripcion'],
                    'precio'=>$evento['precio'],
                    'usuario_capturo'=>$evento['usuario']['nombre'],
                    'revisor'=>$revisor->name,
                    'fecha_revisado'=>Date::parse($evento['fecha_revisado'], 'America/Mexico_City')->format('Y-m-d H:i:s'),
                    'comentarios'=>$evento['comentarios'],
                    'fecha_enviado_revision'=>Date::parse($evento['fecha_enviado_revision'], 'America/Mexico_City')->format('Y-m-d H:i:s'),
                    'aprobado_por'=>$aprobador->name,
                    'fecha_aprobado'=>Date::parse($evento['fecha_aprobado'], 'America/Mexico_City')->format('Y-m-d H:i:s'),
                    'created_at'=>Date::parse($evento['created_at'], 'America/Mexico_City')->format('Y-m-d H:i:s'),
                    'updated_at'=>Date::parse($evento['updated_at'],'America/Mexico_City')->format('Y-m-d H:i:s'),
                    'id'=>$evento['id']
                ),
                'reportes'=>$reportes];
                $data[$evento['sede']] = $temp;
            }

            return $this->crearArchivo($data, 'eventos');
        }else{
            return array('error'=>true,'mensaje'=>'No hay datos que respaldar.');
        }
    }

    /**
     * Funcion para exportar a excel los datos
     * @param Request $request
     * @return $this
     */
    public function exportar(Request $request)
    {
        if($request->isMethod('post')){

            $tipo = $request->input('tipo', null);
            if(!empty($tipo))
            {
                $limit = array( new UTCDateTime(Date::parse('today','	America/Mexico_City')), new UTCDateTime(Date::parse('tomorrow','America/Mexico_City')));
                if($tipo == 'tierra')
                {
                    $registros = Respubfija::whereBetween('created_at', $limit)->get();
                    $resultado = $this->respaldaTierra($registros);

                    if($resultado['error'])
                    {
                        return view('shared.complete.404')->with('mensaje', $resultado['mensaje']);
                    }else{
                        return response()->redirectToRoute('consultorTierraRespaldos');
                    }
                }elseif($tipo == 'eventos')
                {
                    $registros = Resevento::whereBetween('created_at', $limit)->get();
                    $resultado = $this->respaldaEventos($registros);
                    if($resultado['error'])
                    {
                        return view('shared.complete.404')->with('mensaje', $resultado['mensaje']);
                    }else{
                        return response()->redirectToRoute('consultorEventoRespaldos');
                    }
                }else{
                    return view('shared.complete.404')->with('mensaje', 'Tipo de respaldo incorrecto.');
                }
            }else{
                return view('shared.complete.404')->with('mensaje', 'No se puede identificar el tipo de respaldo.');
            }
        }else{
            return view('shared.complete.404')->with('mensaje', 'No se puede localizar el registro');
        }
    }

    /**
     * Funcion para crear el archivo de excel y guardarlo en amazon
     * @param $data
     * @param $tipo
     * @return array
     */
    private function crearArchivo($data, $tipo)
    {
        $archivoCreado = $this->exportToExcel($data, $tipo,true);

        if(isset($archivoCreado) && !empty($archivoCreado) && !is_null($archivoCreado))
        {
            $respaldo = new Respaldos();
            $respaldo->tipo = $tipo;
            $respaldo->fecha = Date::parse('now','America/Mexico_City');
            $respaldo->ubicacion = $archivoCreado;
            $urlArchivo = Storage::disk('s3')->url($archivoCreado);
            if($respaldo->save())
            {
                return array('error'=>false, 'mensaje'=>null);
            }else{
                return array('error'=>true,'mensaje'=>'Error al registrar el respaldo, pero esta disponible <a href="'.$urlArchivo.'">DESCARGAR</a>');
            }
        }else{
            return array('error'=>true,'mensaje'=>'No se logro generar el archivo de respaldo');
        }
    }



    private function sumaCategoriasEventos($alianza, $categoria)
    {
        $gasto = 0.0;
        $elementos = Resevento::project([$categoria=>1])->where('alianza', $alianza)->get();
        if(!empty($elementos))
        {
            foreach($elementos as $elem){
                if(is_array($elem[$categoria]) && !empty($elem[$categoria]))
                {
                    foreach($elem[$categoria] as $el)
                        $gasto+=$el['precio'];
                }
            }
        }
        return $gasto;
    }

    public function indexReportes(Request $request){
        if($request->isMethod('get'))
        {
            $priEstructura = $this->sumaCategoriasEventos('PRI-PVEM-PANAL', 'estructura');
            $panEstructura = $this->sumaCategoriasEventos('PAN-PRD-MC', 'estructura');
            $morenaEstructura = $this->sumaCategoriasEventos('MORENA-PT-PES', 'estructura');
            $priEspectacular = $this->sumaCategoriasEventos('PRI-PVEM-PANAL', 'espectacular');
            $panEspectacular = $this->sumaCategoriasEventos('PAN-PRD-MC', 'espectacular');
            $morenaEspectacular = $this->sumaCategoriasEventos('MORENA-PT-PES', 'espectacular');
            $priUtilitario = $this->sumaCategoriasEventos('PRI-PVEM-PANAL', 'utilitario');
            $panUtilitario = $this->sumaCategoriasEventos('PAN-PRD-MC', 'utilitario');
            $morenaUtilitario = $this->sumaCategoriasEventos('MORENA-PT-PES', 'utilitario');
            $priTransporte = $this->sumaCategoriasEventos('PRI-PVEM-PANAL', 'transporte');
            $panTransporte = $this->sumaCategoriasEventos('PAN-PRD-MC', 'transporte');
            $morenaTransporte = $this->sumaCategoriasEventos('MORENA-PT-PES', 'transporte');
            $priProduccion = $this->sumaCategoriasEventos('PRI-PVEM-PANAL', 'produccion');
            $panProduccion = $this->sumaCategoriasEventos('PAN-PRD-MC', 'produccion');
            $morenaProduccion = $this->sumaCategoriasEventos('MORENA-PT-PES', 'produccion');
            $priAnimacion = $this->sumaCategoriasEventos('PRI-PVEM-PANAL', 'animacion');
            $panAnimacion = $this->sumaCategoriasEventos('PAN-PRD-MC', 'animacion');
            $morenaAnimacion = $this->sumaCategoriasEventos('MORENA-PT-PES', 'animacion');
            $priAdicionales = $this->sumaCategoriasEventos('PRI-PVEM-PANAL', 'adicionales');
            $panAdicionales = $this->sumaCategoriasEventos('PAN-PRD-MC', 'adicionales');
            $morenaAdicionales = $this->sumaCategoriasEventos('MORENA-PT-PES', 'adicionales');

            $fichas = [
                "pri"=>[
                    'eventos'=>Resevento::where('alianza', 'PRI-PVEM-PANAL')->orderBy('fecha', 'desc')->get(),
                    'numero'=>Resevento::where('alianza', 'PRI-PVEM-PANAL')->count(),
                    'valor'=>Resevento::where('alianza', 'PRI-PVEM-PANAL')->sum('precio'),
                    'sedes'=>Resevento::where('alianza', 'PRI-PVEM-PANAL')->sum('precioSede'),
                    'estructura'=>$priEstructura,
                    'espectacular'=>$priEspectacular,
                    'utilitario'=>$priUtilitario,
                    'transporte'=>$priTransporte,
                    'produccion'=>$priProduccion,
                    'animacion'=>$priAnimacion,
                    'adicionales'=>$priAdicionales
                ],
                "pan"=>[
                    'eventos'=>Resevento::where('alianza', 'PAN-PRD-MC')->orderBy('fecha', 'desc')->get(),
                    'numero'=>Resevento::where('alianza', 'PAN-PRD-MC')->count(),
                    'valor'=>Resevento::where('alianza', 'PAN-PRD-MC')->sum('precio'),
                    'sedes'=>Resevento::where('alianza', 'PAN-PRD-MC')->sum('precioSede'),
                    'estructura'=>$panEstructura,
                    'espectacular'=>$panEspectacular,
                    'utilitario'=>$panUtilitario,
                    'transporte'=>$panTransporte,
                    'produccion'=>$panProduccion,
                    'animacion'=>$panAnimacion,
                    'adicionales'=>$panAdicionales
                ],
                "morena"=>[
                    'eventos'=>Resevento::where('alianza', 'MORENA-PT-PES')->orderBy('fecha', 'desc')->get(),
                    'numero'=>Resevento::where('alianza', 'MORENA-PT-PES')->count(),
                    'valor'=>Resevento::where('alianza', 'MORENA-PT-PES')->sum('precio'),
                    'sedes'=>Resevento::where('alianza', 'MORENA-PT-PES')->sum('precioSede'),
                    'estructura'=>$morenaEstructura,
                    'espectacular'=>$morenaEspectacular,
                    'utilitario'=>$morenaUtilitario,
                    'transporte'=>$morenaTransporte,
                    'produccion'=>$morenaProduccion,
                    'animacion'=>$morenaAnimacion,
                    'adicionales'=>$morenaAdicionales
                ]
            ];
            return view('consultor.reportes.index')
                ->with('fichas', $fichas);

        }else{
            return view('shared.complete.404')->with('mensaje', 'Método no aceptado');
        }
    }


    public function eventoPdf(Request $request, $evento_id = null)
    {
        if(isset($evento_id) && $request->isMethod('get'))
        {
            try{
                $evento = Resevento::findOrFail($evento_id);
                /*if($evento->alianza == 'PRI-PVEM-PANAL')
                    view()->share('icono','pri.jpg');
                elseif($evento->alianza == 'PAN-PRD-MC')
                    view()->share('icono','pan.jpg');
                elseif($evento->alianza == 'MORENA-PT-PES')
                    view()->share('icono','morena.jpg');
                else
                    view()->share('icono','');*/

                $pEstructura = 0.0;
                if( is_array($evento['estructura']) && !empty($evento['estructura'])){
                    foreach($evento['estructura'] as $e)
                        $pEstructura+= $e['precio'];
                }
                view()->share('estructura',$pEstructura);

                $pEspectacular = 0.0;
                if( is_array($evento['espectacular']) && !empty($evento['espectacular'])){
                    foreach($evento['espectacular'] as $e)
                        $pEspectacular+= $e['precio'];
                }
                view()->share('espectacular',$pEspectacular);

                $pUtilitario = 0.0;
                if( is_array($evento['utilitario']) && !empty($evento['utilitario'])){
                    foreach($evento['utilitario'] as $e)
                        $pUtilitario+= $e['precio'];
                }
                view()->share('utilitario',$pUtilitario);

                $pTransporte = 0.0;
                if( is_array($evento['transporte']) && !empty($evento['transporte'])){
                    foreach($evento['transporte'] as $e)
                        $pTransporte+= $e['precio'];
                }
                view()->share('transporte',$pTransporte);

                $pProduccion = 0.0;
                if( is_array($evento['produccion']) && !empty($evento['produccion'])){
                    foreach($evento['produccion'] as $e)
                        $pProduccion+= $e['precio'];
                }
                view()->share('produccion',$pProduccion);

                $pAnimacion = 0.0;
                if( is_array($evento['animacion']) && !empty($evento['animacion'])){
                    foreach($evento['animacion'] as $e)
                        $pAnimacion+= $e['precio'];
                }
                view()->share('animacion',$pAnimacion);

                $pAdicionales = 0.0;
                if( is_array($evento['adicionales']) && !empty($evento['adicionales'])){
                    foreach($evento['adicionales'] as $e)
                        $pAdicionales+= $e['precio'];
                }
                view()->share('adicionales',$pAdicionales);

                $estructura = $this->parseaElementosFoto($evento['estructura'], 'estructura', true);
                $espectacular= $this->parseaElementosFoto($evento['espectacular'], 'espectacular', true);
                $utilitario = $this->parseaElementosFoto($evento['utilitario'], 'utilitario', true);
                $transporte = $this->parseaElementosFoto($evento['transporte'], 'transporte', true);
                $produccion = $this->parseaElementosFoto($evento['produccion'], 'produccion', true);
                $animacion = $this->parseaElementosFoto($evento['animacion'], 'animacion', true);
                $adicionales = $this->parseaElementosFoto($evento['adicionales'], 'adicionales', true);
                view()->share('Restructura', $estructura);
                view()->share('Respectacular', $espectacular);
                view()->share('Rutilitario', $utilitario);
                view()->share('Rtransporte', $transporte);
                view()->share('Rproduccion', $produccion);
                view()->share('Ranimacion', $animacion);
                view()->share('Radicionales', $adicionales);

                view()->share('evento',
                    [
                        "id"=>$evento['id'],
                        "alianza"=>$evento['alianza'],
                        "partido"=>$evento['partido'],
                        "fecha"=>$evento['fecha'],
                        "aforo"=>$evento['aforo'],
                        "compartido"=>$evento['compartido'],
                        "quienes"=>$evento['quienes'],
                        "duracion"=>$evento['duracion'],
                        "ubicacion"=>$evento['ubicacion'],
                        "sede"=>$evento['sede'],
                        "estado"=>$evento['estado'],
                        "estado_id"=>$evento['estado_id'],
                        "circunscripcion"=>$evento['circunscripcion'],
                        "direccion"=>$evento['direccion'],
                        "precioSede"=>$evento['precioSede'],
                        "precio"=>$evento['precio'],
                    ]);
                $pdf = PDF::loadView('consultor.reportes.eventopdf');
                return $pdf->download($evento->id.'.pdf');
                //return view('consultor.reportes.eventopdf');
            }catch (ModelNotFoundException $e)
            {
                return view('shared.complete.404')->with('mensaje', 'Evento no encontrado:'.$e->getMessage());
            }
        }else{
            return view('shared.complete.404')->with('mensaje', 'Método no aceptado');
        }
    }
}