<?php

namespace App\Http\Controllers\Auth;

use App\Entity\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;
use App\Mail\Auth\VerifyMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use App\Http\Requests\Auth\RegisterRequest;

class RegisterController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

   public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'verify_token' => Str::random(),
            'status' => User::STATUS_WAIT,
        ]);


        Mail::to($user->email)->send(new VerifyMail($user));
        event(new Registered($user));

        flash('Check your email and click on the link to verify.')->success();
        return redirect()->route('login');
    }


    public function verify($token)
    {
        if (!$user = User::where('verify_token', $token)->first()) {
            return redirect()->route('login')
                ->with('error', 'Sorry your link cannot be identified.');
        }

        if ($user->status !== User::STATUS_WAIT) {
            return redirect()->route('login')
                ->with('error', 'Your email is alredy verified.');
        }

        $user->status = User::STATUS_ACTIVE;
        $user->verify_token = null;
        $user->save();

        flash('Your e-mail is verified. You can now login.')->success();

        return redirect()->route('login');
    }
}
