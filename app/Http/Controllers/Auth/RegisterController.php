<?php

namespace App\Http\Controllers\Auth;

use App\Http\Transformers\UserTransformer;
use App\User;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use Helpers;


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

    public function users(){
        $user  =  User::paginate(1);
        if (! $user)
            return $this->response->errorNotFound();

        return $this->response->paginator($user, new UserTransformer());
        // return $this->response->array($user->toArray());
    }

    public function registerUser(Request $request){
        $rules = [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'bio' => ['required', 'min:10']
        ];

        $payload  =  $request->only('email', 'password', 'name', 'bio');
        // $payload = app('request')->only('username', 'password');
        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails())
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Errors found in the form.', $validator->errors());

        $created  =  User::create([
           'email' => $request->email,
           'bio' => $request->bio,
           'name' => $request->name,
           'password' => bcrypt($request->password),
        ]);

        if ($created)
            return $this->response->item($created, new UserTransformer())->addMeta('message','user created successfully!');
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
