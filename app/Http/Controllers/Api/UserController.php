<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller {
    /**
     * Show the account tied to the api key.
     *
     * @return bool
     */
    public function login($email, $password) {
        $results = User::where('email', $email)->limit(1)->first();

        if($results != null && password_verify($password, $results->pass)) {
            return true;
        }

        return false;
    }

    /**
     * Show the account tied to the api key.
     *
     * @return View
     */
    public function show() {
        //return view('user.profile', [
        //    'user' =>
        //]);
    }
}
