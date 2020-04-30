<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request)
     {

        if(\App\Course::find(\Hashids::decode($request->query('course'))[0] ?? null)) {
          return view('auth.register', ['course' => $request->query('course')]);
        }

        else if($request->query('admin') == config('app.admin_invite_key')) {
          return view('auth.register', ['admin' => $request->query('admin')]);
        }

        return view('auth.unrecognised');

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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'photo' => ['required'],
            'description' => ['string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'course' => ['sometimes','required', 'course'],
            'admin' => ['nullable']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data) {

        if(isset($data['photo'])) {
          $photo_path = $data['photo']->store('users/thumbnail', 'public');
          $full_photo_path = Storage::disk('public')->path($photo_path);
          \Image::make($full_photo_path)->orientate()->fit(480,480)->save();
        }

        $user =  User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'photo' => $photo_path ?? null,
            'description' => $data['description'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => isset($data['admin']) && ($data['admin'] == config('app.admin_invite_key'))
        ]);

        if(isset($data['course'])) {
          $user->courses()->attach(\Hashids::decode($data['course']));
        }


        return $user;


    }
}
