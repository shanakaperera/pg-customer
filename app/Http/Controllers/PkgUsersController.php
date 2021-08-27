<?php

namespace App\Http\Controllers;

use App\Models\PkgUser;
use Illuminate\Support\Facades\Request;

class PkgUsersController extends Controller
{
    //pkg_users
    public function users()
    {
        return response()->json([
            // 'filters' => Request::all('search'),
            'users' => PkgUser::orderBy('id')
                ->filter(Request::only('search'))
                ->take(20)
                ->get()
                ->map(function ($pkg_user) {
                    return [
                        'id'   => $pkg_user->id,
                        'name' => $pkg_user->id . ' : ' . $pkg_user->username,
                    ];
                })->toArray()
        ]);
    }

    public function show($email)
    {
        $user = PkgUser::where('mail', $email);

        return response()->json(['user' => $user->exists() ? $user->first() : null]);
    }
}
