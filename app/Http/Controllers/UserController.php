<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use App\Models\User;



use Illuminate\Http\Request;
use DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::orderBy('name', 'asc')->get();
        return view('users.index',[
            'usuarios'=>$usuarios
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User();
        $almacenes = Almacen::all();
        return view('users.create',[
           'user'=>$user,
           'almacenes'=>$almacenes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {

        $this->validate($request, [
            'name' => 'required|min:3|max:50',
            'email' => 'email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->numero_celular = $request->operadora.$request->numero_celular;
        $user->save();
        
       
    
        return redirect()->route('user.index')->with([
            "info" => "Usuario creado con éxito!",
        ]);
    }
    public function inactivar(Request $request){
        $user = User::where([
            'id'=>$request->user_id,
         ])->first();

        if($user->status==1){
            $user->status=0;
        }else{
            $user->status=1;
        }
        $user->save();
        return redirect()->route('user.index')->with([
            "info" => "Usuario desactivado con éxito!",
        ]);

    }
    public function modal_inactivar(Request $request){
        $user = User::where([
            'id'=>$request->user_id,
         ])->first();
         return view('users.inactivar_usuario',['user'=>$user])->render();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
    public function profile(){

        $user = User::where('id',$this->getUserID())->first();
        $permissionNames = [];
        $roles =[];
        $permisos = [];

        return view('users.profile',[

            'user'=>$user,
            'roles'=>$roles,
            'permisos'=>$permisos,
            'permissionNames'=>$permissionNames,
        ]);
    }
    public function cambio_contrasena(Request $request){


        $user = User::where('id',$request->user_id)->first();

        return view('users.cambio_contrasena',[

            'user'=>$user,

        ]);
    }
    public function store_profile(Request $request){
        $user = User::where('id',$request->user_id)->first();
        $user->name = $request->name;
        $user->email  = $request->email;
        $user->almacen_id = $request->almacen_id;
        $user->numero_celular = $request->operadora.$request->numero_celular;
        $user->save();
        
        return redirect()->route('user.index');

    }
    public function store_contrasena(Request $request, User $user){

        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::where('id',$request->user_id)->first();
        $user->name = $request->name;
        $user->email  = $request->email;
        $user->password = bcrypt($request->password);
        $user->almacen_id = $request->almacen_id;
        $user->numero_celular = $request->operadora.$request->numero_celular;
        $user->save();
        if(isset($request->permisos)){
            $user->syncPermissions($request->permisos);
        }
        return redirect()->route('user.index')->with([
            "info" => "Datos de Usuario guardados con éxito!",
        ]);
    }
    
    public function store_permisos(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:users,email,' . $request->user_id,
        ]);

        $user = User::where('id', $request->user_id)->first();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->almacen_id = $request->almacen_id;
        $user->numero_celular = $request->operadora.$request->numero_celular;
        $user->save();


        if (isset($request->permisos)) {
            $user->syncPermissions($request->permisos);
        }

        return redirect()->route('home')->with([
            "success" => "Datos de Usuario guardados con éxito!",
        ]);
    }
    public function getUserID(){
        $user = auth()->user();
        return $user->id;
    }
    public function getUsuarios(Request $request){
       
        $usuarios = DB::table('users')
        ->select('users.id','almacenes.nombre as almacen' ,'users.name as nombre','users.email as correo',DB::raw('case when status=0 then \'inactivo\' else \'activo\' end as status'));
        $usuarios->leftJoin('almacenes', 'almacenes.id',"=",'users.almacen_id');
        if ($request->has('usuario') && $request->usuario != '') {
            $usuarios->where('users.id', $request->usuario);
        }
        if ($request->has('estado') && $request->estado != '') {
            $usuarios->where('status', $request->estado);
        }


        return datatables($usuarios)
        
            ->filter(function ($query) use ($request) {
        })
        ->addColumn('action', function ($documentos) {
            return $this->getActions($documentos);
        })
        ->toJson();
        
        return $usuarios;
    }
    public function getActions($usuarios){
        return view('users.actions',['user'=>$usuarios]);
    }
    public function detalle(Request $request){

        $user = User::where('id',$request->user_id)->first();
        $permissionNames = $user->getPermissionNames();
        $roles = Role::all()->pluck('name');
        $permisos = Permission::all()->pluck('name');

        $almacenes = Almacen::all();
       
        return view('users.profile',[

            'user'=>$user,
            'roles'=>$roles,
            'permisos'=>$permisos,
            'permissionNames'=>$permissionNames,
            'almacenes'=>$almacenes
        ]);
    }
    public function crear_permiso(){
        return view('users.create_permiso');
    }
    public function store_permiso_spatie(Request $request){
        $nombre_permiso = $request->permiso;
        $permiso = Permission::create(['name' => $nombre_permiso,'guard_name'=>'web']);
        Artisan::call('cache:clear');
        return redirect()->route('user.index')->with([
            "info" => "Permiso creado con exito",
        ]);
    }
}
