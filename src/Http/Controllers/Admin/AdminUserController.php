<?php

namespace Meraki\Packages\Auth\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $userModel = config('meraki-auth.user_model');

        $users = $userModel::orderByDesc('created_at')->paginate(20);

        return view('meraki-auth::admin.users.index', compact('users'));
    }
}
