<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserChangePasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use App\User;
use App\Http\Requests\UserRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Jenssegers\Date\Date;

class UserController extends Controller
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
     * Function to list all users
     * @return $this
     */
    public function index()
    {
        $usuarios = User::where('active', true)
        ->where('web', true)
        ->orderBy('perfil', 'asc')->orderBy('name', 'asc')->get();

        return view('admin.usuarios.index')
            ->with('usuarios', $usuarios);
    }
    /**
     * Function to show the user create form page
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        if($request->isMethod('get'))
        {
            return view('admin.usuarios.create');
        }else{
            return view('shared.complete.404')
                ->with('mensaje','Metodo no aceptado');
        }
    }
    /**
     * Function to store new user
     * @param UserRequest $request
     * @return $this
     */
    public function store(UserRequest $request)
    {
        if($request->isMethod('post'))
        {
            // Create user object with data
            $user = new User($request->all());
            $user->name = ucwords($request->name);
            $user->active = (boolean)$request->input('active', false);
            $user->circunscripcion = (int)$request->input('circunscripcion', 1);
            //encrypt password
            $user->password = bcrypt($request->password);
            //Set users to web app
            $user->web = true;
            $user->estado_id = $this->getStateId($user->estado);
            //Save user to database
            if($user->save())
            {
                return view('shared.complete.200')
                    ->with('mensaje', 'Usuario creado')
                    ->with('destino', 'usuarios');

            }else{
                return view('shared.complete.404')
                    ->with('mensaje','No se creo el usuario');
            }
        }else{
            return view('shared.complete.404')
                ->with('mensaje','Metodo no aceptado');
        }
    }

    /**
     * Function to show update form
     * @param Request $request
     * @param $id
     * @return $this
     */
    public function edit(Request $request, $id)
    {
        try{
            $user = User::findOrFail($id);
            return view('admin.usuarios.view')
                ->with('usuario', $user);
        }catch(ModelNotFoundException $e)
        {
            return view('shared.complete.404')
                ->with('mensaje', 'Usuario no encontrado. '.$e->getMessage());
        }
    }

    /**
     * Function to store changes for update user data
     * @param UserUpdateRequest $request
     * @return $this
     */
    public function update(UserUpdateRequest $request)
    {
        if($request->isMethod('post'))
        {
            try{
                $id = $request->input('id', null);
                $user = User::findOrFail($id);
                $user->name = ucwords($request->input('name', ''));
                $user->email = $request->input('email', '');
                $user->active = $request->input('active', false);
                $user->perfil = $request->input('perfil', 'staff');
                $user->circunscripcion = $request->input('circunscripcion', 1);
                $user->estado = $request->input('estado', '');
                $user->estado_id = $this->getStateId($user->estado);
                if($user->save())
                {
                    return view('shared.complete.200')
                        ->with('mensaje', 'Usuario actualizado')
                        ->with('destino', 'usuarios');
                }else{
                    return view('shared.complete.404')
                        ->with('mensaje', 'Usuario no actualizado');
                }

            }catch(ModelNotFoundException $e)
            {
                return view('shared.complete.404')
                    ->with('mensaje', 'Usuario no encontrado. '.$e->getMessage());
            }
        }else{
            return view('shared.complete.404')
                ->with('mensaje', 'Método no aceptado');
        }

    }
    /**
     * Function to delete a user
     * @param Request $request
     * @param $id
     */
    public function destroy(Request $request, $id)
    {
        if($request->isMethod('get'))
        {
            try{
                $user = User::findOrFail($id);
                $user->active = false;
                $user->save();
                if( $user->delete() )
                {
                    return view('shared.complete.200')
                        ->with('mensaje', 'Usuario Eliminado')
                        ->with('destino', 'usuarios');
                }else{
                    return view('shared.complete.404')
                        ->with('mensaje', 'El usuario no fue eliminado. ');
                }
            }catch(ModelNotFoundException $e)
            {
                return view('shared.complete.404')
                    ->with('mensaje', 'Usuario no encontrado. '.$e->getMessage());
            }
        }else{
            return view('shared.complete.404')
                ->with('mensaje', 'Método no aceptado');
        }
    }

    /**
     * Function to show form for change password
     * @param Request $request
     * @param $id
     * @return $this
     */
    public function changePassword(Request $request, $id)
    {
        if($request->isMethod('get'))
        {
            try{
                $user = User::findOrFail($id);
                return view('admin.usuarios.changepass')
                    ->with('usuario', $user);
            }catch(ModelNotFoundException $e)
            {
                return view('shared.complete.404')
                    ->with('mensaje', 'Usuario no encontrado. '.$e->getMessage());
            }
        }else{
            return view('shared.complete.404')
                ->with('mensaje', 'Método no aceptado');
        }
    }

    /**
     * Function to set the new password of a user
     * @param UserChangePasswordRequest $request
     * @return $this
     */
    public function updatePassword(UserChangePasswordRequest $request)
    {
        if($request->isMethod('post'))
        {
            $newpassword = $request->input('newpassword', '');
            $id = $request->input('id', null);
            try{
                $user = User::findOrFail($id);
                $user->password = bcrypt($newpassword);
                if($user->save())
                {
                    return view('shared.complete.200')
                        ->with('mensaje', 'Contraseña de acceso actualizada')
                        ->with('destino', 'usuarios');
                }else{
                    return view('shared.complete.404')
                        ->with('mensaje', 'No se actualizó la contraseña');
                }
            }catch (ModelNotFoundException $e)
            {
                return view('shared.complete.404')
                    ->with('mensaje', 'Usuario no encontrado. '.$e->getMessage());
            }

        }else{
            return view('shared.complete.404')
                ->with('mensaje', 'Método no aceptado');
        }
    }

    /**
     * Function to get the state_id value
     * @param $state
     * @return string
     */
    protected function getStateId($state) : string
    {
        $result = '';
        switch ($state)
        {
            case 'Aguascalientes':
                $result = '01';
                break;
            case 'Baja California':
                $result = '02';
                break;
            case 'Baja California Sur':
                $result = '03';
                break;
            case 'Campeche':
                $result = '04';
                break;
            case 'Coahuila de Zaragoza':
                $result = '05';
                break;
            case 'Colima':
                $result = '06';
                break;
            case 'Chiapas':
                $result = '07';
                break;
            case 'Chihuahua':
                $result = '08';
                break;
            case 'Ciudad de México':
                $result = '09';
                break;
            case 'Durango':
                $result = '10';
                break;
            case 'Guanajuato':
                $result = '11';
                break;
            case 'Guerrero':
                $result = '12';
                break;
            case 'Hidalgo':
                $result = '13';
                break;
            case 'Jalisco':
                $result = '14';
                break;
            case 'Estado de México':
                $result = '15';
                break;
            case 'Michoacán de Ocampo':
                $result = '16';
                break;
            case 'Morelos':
                $result = '17';
                break;
            case 'Nayarit':
                $result = '18';
                break;
            case 'Nuevo León':
                $result = '19';
                break;
            case 'Oaxaca':
                $result = '20';
                break;
            case 'Puebla':
                $result = '21';
                break;
            case 'Querétaro':
                $result = '22';
                break;
            case 'Quintana Roo':
                $result = '23';
                break;
            case 'San Luis Potosí':
                $result = '24';
                break;
            case 'Sinaloa':
                $result = '25';
                break;
            case 'Sonora':
                $result = '26';
                break;
            case 'Tabasco':
                $result = '27';
                break;
            case 'Tamaulipas':
                $result = '28';
                break;
            case 'Tlaxcala':
                $result = '29';
                break;
            case 'Veracruz de Ignacio de la Llave':
                $result = '30';
                break;
            case 'Yucatán':
                $result = '31';
                break;
            case 'Zacatecas':
                $result = '32';
                break;
            default:
                $result='';
                break;
        }
        return $result;
    }
}
