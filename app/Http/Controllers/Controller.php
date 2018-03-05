<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /** CONSTANTES DE TIERRA  */
     const TIERRA_CATEGORIAS = [
        'Fija',
        'Movil'
    ];
     const TIERRA_SUBCATEGORIAS = [
        'Fija'=>[
            'Espectaculares',
            'Bardas',
            'Lonas',
            'Puentes',
            'Pendones',
            'Kioscos',
            'Carteles',
            'Parabuses',
            'Mobiliario/EspacioPublico',
            'Volantes y Pegatinas',
            'Valla Impresa',
            'Valla Digital',
            'Pantallas Fijas',
            'Propaganda en Columnas',
            'Buzones',
            'Cajas de Luz',
            'Marquesinas',
            'Muebles Urbanos',
            'Espectaculares de Pantallas Digitales',
            'Mantas (Igual o Mayor a 12 MTS)',
            'Mantas (Menores a 12 MTS)'
        ],
        'Movil'=>[
            'Transporte Público(combis, micros, camiones)',
            'Vehículos Publicidad(pantallas o lonas)',
            'Particulares',
            'Taxis',
            'Metro (dentro de vagones)',
            'Brigadas (reparten utilitarios,en cruceros pueden abrir lonas)',
            'Bicicletas/Bicitaxis/Mototaxis',
            'Perifoneo',
            'Pantallas Moviles'
        ]
    ];
     const TIERRA_FIELDS = [
        'Fija'=>[
            'Espectaculares'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'Bardas'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'Lonas'=>[
                'alto'=>'string',
                'largo'=>'string',
                'tipo lona'=>'string',
                'publicidad'=>'boolean'
            ],
            'Puentes'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'Pendones'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'Kioscos'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'Carteles'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'Parabuses'=>[
                'alto'=>'string',
                'largo'=>'string',
                'tipo'=>'string',
                'publicidad'=>'boolean'
            ],
            'Mobiliario/EspacioPublico'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'Volantes y Pegatinas'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'Valla Impresa'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'Valla Digital'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'Pantallas Fijas'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'Propaganda en Columnas'=>[
                'alto'=>'string',
                'largo'=>'string',
                'circunferencia'=>'string',
                'publicidad'=>'boolean',
                'tipo publicidad'=>'string'
            ],
            'Buzones'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'Cajas de Luz'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'Marquesinas'=>[
                'alto'=>'string',
                'largo'=>'string',
                'ancho'=>'string',
                'publicidad'=>'boolean'
            ],
            'Muebles Urbanos'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'Espectaculares de Pantallas Digitales'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'Mantas (Igual o Mayor a 12 MTS)'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'Mantas (Menores a 12 MTS)'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ]
        ],
        'Movil'=>[
            'Transporte Público(combis, micros, camiones)'=>[
                'publicidad'=>'boolean',
                'tipo publicidad'=>'string',
                'placa'=>'string',
                'alto'=>'string',
                'largo'=>'string'
            ],
            'Vehículos Publicidad(pantallas o lonas)'=>[
                'tipo vehiculo'=>'string',
                'publicidad'=>'boolean',
                'tipo publicidad'=>'string',
                'placa'=>'string',
                'alto'=>'string',
                'largo'=>'string'
            ],
            'Particulares'=>[
                'publicidad'=>'boolean',
                'tipo publicidad'=>'string'
            ],
            'Taxis'=>[
                'publicidad'=>'boolean',
                'tipo publicidad'=>'string',
                'placa'=>'string'
            ],
            'Metro (dentro de vagones)'=>[
                'publicidad'=>'boolean',
                'tipo publicidad'=>'string',
                'linea'=>'string'
            ],
            'Brigadas (repartes utilitarios, en cruceros pueden abrir lonas)'=>[
                'publicidad'=>'boolean',
                'tipo publicidad'=>'string',
                'personas'=>'string'
            ],
            'Bicicletas/Bicitaxis/Mototaxis'=>[
                'publicidad'=>'boolean',
                'tipo publicidad'=>'string',
                'placa'=>'string'
            ],
            'Perifoneo'=>[
                'tipo vehiculo'=>'string',
                'publicidad'=>'boolean',
                'tipo publicidad'=>'string',
                'placa'=>'string'
            ],
            'Pantallas Móviles'=>[
                'publicidad'=>'boolean',
                'tipo publicidad'=>'string'
            ]
        ]
    ];
    /** CONSTANTES DE EVENTOS  */
     const EVENTO_CATEGORIES = [
        'estructura',
        'espectacular',
        'utilitario',
        'transporte',
        'animacion',
        'produccion'
    ];
     const EVENTO_SUBCATEGORIES = [
        'estructura'=>[
            'vallas',
            'gradas',
            'sillas',
            'sillones',
            'carpas',
            'templete',
            'mampara',
            'arañas',
            'mesas',
            'otros'
        ],
        'espectacular'=>[
            'lonas',
            'pendones',
            'columnas',
            'carteleras',
            'parabuses',
            'muros',
            'puentes',
            'marquesinas',
            'vehículos de transporte',
            'buzones/cajas de luz',
            'muebles urbanas',
            'vallas',
            'panoramicos',
            'otros'
        ],
        'utilitario'=>[
            'aguas',
            'refrescos',
            'gorras',
            'playeras',
            'pusleras',
            'lonches',
            'abanicos',
            'sombrillas',
            'banderas',
            'banderines',
            'banderolas',
            'stikers',
            'botones',
            'impermeable',
            'chaleco',
            'sudadera',
            'cobija',
            'mangas',
            'mandiles',
            'mochilas',
            'vasos',
            'otros'
        ],
        'transporte'=>[
            'camiones',
            'camionetas',
            'automoviles',
            'taxi',
            'combi/microbus',
            'otros'
        ],
        'animacion'=>[
            'grupos musicales/djs',
            'artistas',
            'edecanes',
            'animador/maestro de ceremonias',
            'otros'
        ],
        'produccion'=>[
            'platas de luz',
            'pantallas',
            'video walls',
            'equipo de audio',
            'consola de audio',
            'micrófonos',
            'cámaras de video',
            'computadoras',
            'dron',
            'luces',
            'proyectores',
            'gruas de cámara',
            'personal de seguridad',
            'otros'
        ]
    ];
     const EVENTO_FIELDS = [
        'estructura'=>[
            'vallas'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'gradas'=>[
                'alto'=>'string',
                'largo'=>'string',
                'ancho'=>'string',
                'cupo'=>'string',
                'material'=>'string',
                'techo'=>'boolean',
                'publicidad'=>'boolean'
            ],
            'sillas'=>[
                'plegable'=>'boolean',
                'material'=>'string',
                'asiento acojinado'=>'boolean'
            ],
            'sillones'=>[
                'material'=>'string',
                'tamaño'=>'string'
            ],
            'carpas'=>[
                'alto'=>'string',
                'largo'=>'string',
                'ancho'=>'string',
                'publicidad'=>'boolean'
            ],
            'templete'=>[
                'alto'=>'string',
                'largo'=>'string',
                'ancho'=>'string',
                'publicidad'=>'boolean',
                'escaleras'=>'boolean',
                'material'=>'string'
            ],
            'mampara'=>[
                'alto'=>'string',
                'largo'=>'string',
                'estructura_metalica'=>'boolean',
                'publicidad'=>'boolean',
                'tipo de mampara'=>'string'
            ],
            'arañas'=>[
                'alto'=>'string',
                'largo'=>'string',
                'material'=>'string',
                'publicidad'=>'boolean'
            ],
            'mesas'=>[
                'cupo'=>'string',
                'tipo'=>'string',
                'material'=>'string',
                'mantel'=>'boolean',
                'publicidad'=>'boolean'
            ],
            'otros'=>[
                'descripcion'=>'string',
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ]
        ],
        'espectacular'=>[
            'lonas'=>[
                'alto'=>'string',
                'largo'=>'string',
                'tipo lona'=>'string',
                'publicidad'=>'boolean'
            ],
            'pendones'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'columnas'=>[
                'alto'=>'string',
                'largo'=>'string',
                'circunferencia'=>'string',
                'publicidad'=>'boolean',
                'tipo publicidad'=>'string'
            ],
            'carteleras'=>[
                'alto'=>'string',
                'largo'=>'string',
                'ancho'=>'string',
                'publicidad'=>'boolean'
            ],
            'parabuses'=>[
                'alto'=>'string',
                'largo'=>'string',
                'tipo'=>'string',
                'publicidad'=>'boolean'
            ],
            'muros'=>[
                'alto'=>'string',
                'largo'=>'string',
                'ancho'=>'string',
                'publicidad'=>'boolean'
            ],
            'puentes'=>[
                'alto'=>'string',
                'largo'=>'string',
                'tipo publicidad'=>'string',
                'publicidad'=>'boolean'
            ],
            'marquesinas'=>[
                'alto'=>'string',
                'largo'=>'string',
                'ancho'=>'string',
                'publicidad'=>'boolean'
            ],
            'vehículos de transporte'=>[
                'alto'=>'string',
                'largo'=>'string',
                'tipo publicidad'=>'string',
                'publicidad'=>'boolean'
            ],
            'buzones/cajas de luz'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'muebles urbanas'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'vallas'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'panoramicos'=>[
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ],
            'otros'=>[
                'descripcion'=>'string',
                'alto'=>'string',
                'largo'=>'string',
                'publicidad'=>'boolean'
            ]
        ],
        'utilitario'=>[
            'aguas'=>[
                'capacidad'=>'string',
                'publicidad'=>'boolean'
            ],
            'refrescos'=>[
                'capacidad'=>'string',
                'publicidad'=>'boolean'
            ],
            'gorras'=>[
                'tipo'=>'string',
                'publicidad'=>'boolean'
            ],
            'playeras'=>[
                'tipo'=>'string',
                'publicidad'=>'boolean'
            ],
            'pusleras'=>[
                'tipo'=>'string',
                'publicidad'=>'boolean'
            ],
            'lonches'=>[
                'tipo'=>'string',
                'publicidad'=>'boolean'
            ],
            'abanicos'=>[
                'publicidad'=>'boolean'
            ],
            'sombrillas'=>[
                'publicidad'=>'boolean'
            ],
            'banderas'=>[
                'material'=>'string',
                'largo'=>'string',
                'alto'=>'string',
                'tipo de asta'=>'string',
                'publicidad'=>'boolean'
            ],
            'banderines'=>[
                'material'=>'string',
                'largo'=>'string',
                'alto'=>'string',
                'tipo de asta'=>'string',
                'publicidad'=>'boolean'
            ],
            'banderolas'=>[
                'material'=>'string',
                'largo'=>'string',
                'alto'=>'string',
                'tipo de asta'=>'string',
                'publicidad'=>'boolean'
            ],
            'stikers'=>[
                'material'=>'string',
                'largo'=>'string',
                'alto'=>'string',
                'publicidad'=>'boolean'
            ],
            'botones'=>[
                'material'=>'string',
                'dimensiones'=>'string',
                'publicidad'=>'boolean'
            ],
            'impermeable'=>[
                'material'=>'string',
                'tipo'=>'string',
                'publicidad'=>'boolean'
            ],
            'chaleco'=>[
                'material'=>'string',
                'publicidad'=>'boolean'
            ],
            'sudadera'=>[
                'material'=>'string',
                'tipo'=>'string',
                'con gorro'=>'boolean',
                'publicidad'=>'boolean'
            ],
            'cobija'=>[
                'material'=>'string',
                'tamaño'=>'string',
                'publicidad'=>'boolean'
            ],
            'mangas'=>[
                'publicidad'=>'boolean'
            ],
            'mandiles'=>[
                'publicidad'=>'boolean'
            ],
            'mochilas'=>[
                'tipo'=>'string',
                'material'=>'string',
                'publicidad'=>'boolean'
            ],
            'vasos'=>[
                'tipo'=>'string',
                'material'=>'string',
                'publicidad'=>'boolean'
            ],
            'otros'=>[
                'descripcion'=>'string',
                'publicidad'=>'boolean'
            ]
        ],
        'transporte'=>[
            'camiones'=>[
                'publicidad'=>'boolean',
                'tipo publicidad'=>'string'
            ],
            'camionetas'=>[
                'publicidad'=>'boolean',
                'tipo publicidad'=>'string'
            ],
            'automoviles'=>[
                'publicidad'=>'boolean',
                'tipo publicidad'=>'string'
            ],
            'taxi'=>[
                'publicidad'=>'boolean',
                'tipo publicidad'=>'string'
            ],
            'combi/microbus'=>[
                'publicidad'=>'boolean',
                'tipo publicidad'=>'string'
            ],
            'otros'=>[
                'publicidad'=>'boolean',
                'tipo publicidad'=>'string'
            ]
        ],
        'animacion'=>[
            'grupos musicales/djs'=>[
                'integrantes'=>'string',
                'tipo grupo'=>'string',
                'tipo de musica'=>'string',
                'en todo el evento'=>'boolean',
                'duracion'=>'string'
            ],
            'artistas'=>[
                'tipo de artista'=>'string',
                'descripcion'=>'string',
                'en todo el evento'=>'boolean',
                'duracion'=>'string'
            ],
            'edecanes'=>[
                'publicidad en vestuario'=>'boolean',
                'en todo el evento'=>'boolean',
            ],
            'animador/maestro de ceremonias'=>[
                'publicidad en vestuario'=>'boolean',
                'en todo el evento'=>'boolean',
            ],
            'otros'=>[
                'descripcion'=>'string',
                'en todo el evento'=>'boolean',
                'duracion'=>'string'
            ]
        ],
        'produccion'=>[
            'platas de luz'=>[
                'alto'=>'string',
                'largo'=>'string',
                'ancho'=>'string',
                'tipo'=>'string',
                'capacidad'=>'string',
                'vehículo de remolque'=>'boolean',
                'tipo de vehículo'=>'string',
                'publicidad'=>'boolean',
                'en todo el evento'=>'boolean'
            ],
            'pantallas'=>[
                'pulgadas'=>'string',
                'tipo'=>'string',
                'publicidad'=>'boolean'
            ],
            'video walls'=>[
                'alto'=>'string',
                'largo'=>'string',
                'cantidad de secciones/pantallas'=>'string',
                'tipo de pantallas'=>'string',
                'pulgadas pantallas'=>'string',
                'publicidad'=>'boolean'
            ],
            'equipo de audio'=>[
                'tipo'=>'string',
                'instalación'=>'string',
                'numero de bocinas'=>'string'
            ],
            'consola de audio'=>[
                'profesional'=>'boolean',
                'unidas'=>'boolean',
                'largo'=>'string',
                'ancho'=>'string'
            ],
            'micrófonos'=>[
                'inalámbricos'=>'boolean'
            ],
            'cámaras de video'=>[
                'profesionales'=>'boolean',
                'tipo'=>'string',
                'operadores'=>'string'
            ],
            'computadoras'=>[
                'tipo'=>'string',
                'marca'=>'string'
            ],
            'dron'=>[
                'tamaño'=>'string',
                'profesional'=>'boolean',
                'operadores'=>'string'
            ],
            'luces'=>[
                'tipo'=>'string',
                'instalacion'=>'string',
                'estructura metálica'=>'boolean',
                'automáticas'=>'boolean'
            ],
            'proyectores'=>[
                'largo de imagen'=>'string',
                'alto de imagen'=>'string',
                'superficie de proyección'=>'string',
                'publicidad'=>'boolean'
            ],
            'gruas de cámara'=>[
                'carga cámara profesional'=>'boolean',
                'operadores'=>'string',
                'largo'=>'string',
                'alto'=>'string',
                'ancho'=>'string',
                'tipo'=>'string'
            ],
            'personal de seguridad'=>[
                'armados'=>'boolean',
                'tipo'=>'string',
                'publicidad en uniforme'=>'boolean'
            ],
            'otros'=>[
                'descripcion'=>'string',
                'publicidad'=>'boolean'
            ]
        ]
    ];

    /**
     * Funcion que parsea elementos reportados en arreglos para devolverlos en json
     * @param $elementos Conjunto de reportes
     * @param $categoria Categoria a la que pertenecen
     * @return array Elementos parseados
     */
    protected function parseaElementosFoto($elementos, $categoria, $completo=false)
    {
        $resultado = array();
        if(count($elementos)>0){
            foreach($elementos as $index=>$elemento)
            {
                if($completo)
                {
                    $fotos = array();
                    if( isset($elemento['evidencia']) && count($elemento['evidencia'])>0){
                        foreach($elemento['evidencia'] as $foto)
                        {

                            array_push($fotos, Storage::disk('s3')->url($foto));
                        }
                    }
                    array_push($resultado,
                        [
                            'numero'=>$index,
                            'categoria'=>$categoria,
                            'subcategoria'=>$elemento['subcategoria'],
                            'cantidad'=>(array_key_exists('cantidad', $elemento) && isset($elemento['cantidad']))?$elemento['cantidad']:0,
                            'comentario_staff'=>(array_key_exists('comentario_staff', $elemento) && isset($elemento['comentario_staff']))?$elemento['comentario_staff']:"",
                            'atributos'=>(array_key_exists('atributos', $elemento) &&isset($elemento['atributos']))?$elemento['atributos']:array(),
                            'evidencia'=>$fotos,
                            'precio'=>(array_key_exists('precio', $elemento) &&isset($elemento['precio']))?$elemento['precio']:0
                        ]);
                }else{
                    array_push($resultado,
                        [
                            'numero'=>$index,
                            'categoria'=>$categoria,
                            'subcategoria'=>$elemento['subcategoria'],
                            'cantidad'=>(array_key_exists('cantidad', $elemento) && isset($elemento['cantidad']))?$elemento['cantidad']:0,
                            'precio'=>(array_key_exists('precio', $elemento) &&isset($elemento['precio']))?$elemento['precio']:0
                        ]);
                }
            }
        }
        return $resultado;
    }


    /**
     * Funcion para exportar datos a excel
     * @param $data
     * @param string $folder
     * @param bool $s3
     * @return mixed
     */
    protected function exportToExcel( $data, $folder='tierra', $s3 = false)
    {
        $filename = 'Respaldo_'.$folder.'_'.date('YmdHis');
        $file = Excel::create($filename, function($excel) use($data, $folder){
            $excel->setTitle('Respaldo_'.date('Y_m_d_H_i_s'));
            $excel->setDescription('Respaldo del '.date('Y_m_d_H_i_s'));
            if($folder === 'tierra'){
                $excel->sheet('Datos', function($sheet) use($data){
                    $sheet->fromArray($data, null, 'A1', true, true);
                });
            }else{
                foreach($data as $key=>$evento)
                {
                    $excel->sheet("Evento $key", function($sheet)use($evento)
                    {
                        $sheet->cell('A1', function($cell){
                            $cell->setValue('DATOS GENERALES');
                            $cell->setFontSize(16);
                            $cell->setFontWeight('bold');
                        });
                        //Datos Generales
                        $sheet->appendRow(2,array(
                            'alianza',
                            'partido',
                            'fecha',
                            'aforo',
                            'duracion',
                            'compartido',
                            'quienes_aparecen',
                            'sede',
                            'precio_sede',
                            'ubicacion',
                            'direccion',
                            'estado',
                            'estado_id',
                            'circunscripcion',
                            'precio',
                            'usuario_capturo',
                            'revisor',
                            'fecha_revisado',
                            'comentarios',
                            'fecha_enviado_revision',
                            'aprobado_por',
                            'fecha_aprobado',
                            'created_at',
                            'updated_at',
                            'id'
                        ));
                        $sheet->appendRow($evento['generales']);
                        //Reportes
                        $sheet->cell('A4', function($cell){
                            $cell->setValue('REPORTES DEL EVENTO');
                            $cell->setFontSize(16);
                            $cell->setFontWeight('bold');
                        });
                        $sheet->appendRow(5,array(
                            'categoria',
                            'subcategoria',
                            'cantidad',
                            'precio',
                            'fotos_evidencia',
                            'atributos'
                        ));
                        $sheet->rows($evento['reportes']);
                        //foreach($evento['reportes'] as $reporte)
                        //    $sheet->appendRow($reporte);
                    });
                }
            }
        })->string('xlsx');
        $path = 'respaldos/'.$folder.'/'.$filename;
        $upload = Storage::disk('s3')->put($path, $file);
        if($upload)
            return $path;
        else
            return null;
    }
}
