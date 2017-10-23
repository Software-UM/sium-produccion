<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use App\Empleado;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    // FUNCIONES AGREGADAS PARA EVITAR QUE LOS MAESTROS INGRESEN AL SIUM
    public function login(Request $request)
    {
        $this->validate($request, ['email'=>'required|email', 'password'=>'required|min:6']);
        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials))
        {
            $empleado = Empleado::find(Auth::user()->empleado_id);
            if($empleado->id_tipo_empleado == 10){
                $this->logout();
            }
            else{
                return redirect()->intended('/');
            }
        }
        return redirect('login')->withInput($request->only('email'))->withErrors(['error'=>'ACCESO DENEGADO']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('login');
    }
}
