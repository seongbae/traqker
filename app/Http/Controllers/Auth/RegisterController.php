<?php

namespace App\Http\Controllers\Auth;

use App\Events\InviteAccepted;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Invitation;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Camroncade\Timezone\Facades\Timezone;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
    protected $redirectTo = '/dashboard';

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
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'timezone' => $data['timezone']
        ]);

        if (array_key_exists('invitation_token', $data))
        {
            $invite = Invitation::where('invitation_token', $data['invitation_token'])->whereNull('accepted_at')->whereNull('declined_at')->first();

            if ($invite)
            {
                $user->teams()->syncWithoutDetaching($invite->team_id, ['access'=>$invite->access,'title'=>$invite->title]);
                $invite->registered_at = Carbon::now()->toDateTimeString();
                $invite->accepted_at = Carbon::now()->toDateTimeString();
                $invite->to_user_id = $user->id;
                $invite->save();

                event(new InviteAccepted($invite));
            }
        }

        return $user;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $email = app('request')->input('email');

        $timezone = Timezone::selectForm(
            'US/Central',
            '',
            ['class' => 'form-control', 'name' => 'timezone']
        );

        return view('canvas::auth.register')
                ->with('email', $email)
                ->with('timezone', $timezone);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse(new UserResource($user), 201)
            : redirect($this->redirectPath());
    }
}
