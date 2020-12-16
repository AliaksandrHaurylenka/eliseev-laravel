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
use App\UseCases\Auth\RegisterService;

class RegisterController extends Controller
{

    private $service;


    public function __construct(RegisterService $service)
    {
        $this->middleware('guest');
        $this->service = $service;
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

   public function register(RegisterRequest $request)
    {
        $this->service->register($request);

        //flash('Check your email and click on the link to verify.')->success();//для тестов выдает ошибку
        return redirect()->route('login')->with('success', 'Check your email and click on the link to verify.');
    }


    public function verify($token)
    {
        if (!$user = User::where('verify_token', $token)->first()) {
            return redirect()->route('login')
                ->with('error', 'Sorry your link cannot be identified.');
        }

        try {
            $this->service->verify($user->id);
            flash('Your e-mail is verified. You can now login.')->success();
            return redirect()->route('login')->with('success', 'Your e-mail is verified. You can now login.');
        } catch (\DomainException $e) {
            flash($e->getMessage())->error();
            return redirect()->route('login');
        }
        

        

        
    }
}
