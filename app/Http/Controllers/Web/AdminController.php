<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

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
            case 'sendNotification':
                $title = $request->input('title', false);
                if(!$title) {
                    return response('Missing Title');
                }

                Notification::send(User::all(), new SystemNotification($request->input('title'), $request->input('body')));

                return response('Notification Sent');
            default:
                return response('Invalid Action');
        }
    }
}
