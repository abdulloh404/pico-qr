<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class LoginController extends Controller
{
    public function logout()
    {
        cache()->flush();
        $authUser = auth()->user();

        auth()->logout();

        return redirect()->route('login');
    }
}
