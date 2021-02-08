<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $messages = [
            'date_format' => 'The value in the :attribute field is not the correct format.',
            'confirmed' => 'The passwords you have entered don\'t match.'
        ];

        $rules = [
            'first_name' => ['sometimes','required', 'string', 'max:255'],
            'last_name' => ['sometimes','required', 'string', 'max:255'],
            'email' => ['sometimes','required', 'string', 'email', 'max:255', 'unique:users'],
            'date_of_birth' => ['sometimes','required', 'date_format:d/m/Y'],
            'password' => ['sometimes','required', 'string', 'min:8'],
            'password_confirmation' => ['sometimes','required']
        ];


        $v = Validator::make($data, $rules, $messages);

        $v->sometimes('password', 'confirmed', function($data) {
            return isset($data['password_confirmation']);
        });

        return $v;
      
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $dateTimestamp = strtotime(str_replace('/', '-', $data['date_of_birth']));
        $formattedDate = date("Y-m-d", $dateTimestamp);

        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'date_of_birth' => $formattedDate,
            'password' => Hash::make($data['password']),
        ]);
    }
}
