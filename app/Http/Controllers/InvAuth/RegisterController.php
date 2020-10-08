<?php

namespace App\Http\Controllers\InvAuth;

use App\Jobs\SendVerificationEmail;
use App\Models\RegisterConfirmation;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class RegisterController extends \App\Http\Controllers\Auth\RegisterController
{

    public function __construct()
    {
        $this->redirectTo = route('register.thanks');
        parent::__construct();
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->registerConfirmation($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * @param User $user
     */
    protected function registerConfirmation(User $user)
    {

        $token = bin2hex(random_bytes(32));
        RegisterConfirmation::create([
            'token' => $token,
            'user_id' => $user->id
        ]);
        dispatch(new SendVerificationEmail($user, $token));

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->assignRole('user');

        return $user;
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        \Log::info('Rejestracja nowego użytkownika. ' . $user->name .
            ', email: ' . $user->email . ' z ip ' . $request->ip()
        );
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function thanks()
    {
        return view('auth.thanks');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function notConfirmed()
    {
        return view('auth.notConfirmed');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function resendActivation()
    {
        return view('auth.resendActivation');
    }

    /**
     * Show the admin dashboard.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function resendActivationEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $request->session()->flash('error', 'Nie znaleziono takiego adresu email');

        } else {

            if($user->active == 0){
                return redirect()->route('login');
            }

            foreach ($user->activationTokens as $row) {
                $row->delete();
            }

            $this->registerConfirmation($user);
            $request->session()->flash('success', 'Wysłano email poprawnie');
        }
        return redirect()->route('register.resend.email');

    }

    public function activateAccount(Request $request)
    {
        $activationToken = RegisterConfirmation::where('token',$request->token)->first();

        if (!$activationToken) {
            $request->session()->flash('error', 'Błędny kod aktywacyjny');
            return redirect()->route('register.resend.email');
        }

        $user = $activationToken->user;
        $user->active = 1;
        $user->save();

        $activationToken->delete();


        \Log::info('Aktywacja konta. ' . $user->name .
            ', email: ' . $user->email . ' z ip ' . $request->ip()
        );

        $request->session()->flash('success', 'Aktywowano konto poprawnie. Możesz się zalogować.');
        return redirect()->route('login');
    }
}
