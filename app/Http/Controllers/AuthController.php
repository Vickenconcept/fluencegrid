<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Mail\WelcomeMail;
use App\Models\Product;
use App\Services\InfluencerService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function register(CreateUserRequest $request)
    {

        $requestData = $request->validated();
        $requestData['is_admin'] = 1;

        $user = User::create($requestData);
        // Mail::to($requestData['email'])->send(new WelcomeMail($requestData['password']));


        return redirect('login');
    }

    public function login(CreateUserRequest $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $request->wantsJson()
                ? Response::api('Invalid Credentials', Response::HTTP_BAD_REQUEST)
                : back()->with('error', 'Invalid Credentials');
        }

        return redirect()->intended(route('home'));
    }

    



    public function logout()
    {
        Auth::logout();

        return to_route('login');
    }
}
