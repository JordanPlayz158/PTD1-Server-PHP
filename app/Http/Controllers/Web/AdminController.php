<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller {
    public function post(Request $request) {
        switch ($request->input('action')) {
            case 'loginAsUser':
                $id = $request->input('id');
                if(!empty($id)) {
                    Auth::loginUsingId($id);
                } else {
                    $email = $request->input('email');
                    if(!empty($email)) {
                        Auth::login(User::whereEmail($email)->first());
                    }
                }
                return response('User Switched');
            default:
                return response('Invalid Action');
        }
    }
}
