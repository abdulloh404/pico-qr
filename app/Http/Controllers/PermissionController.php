<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function permissionStore(Request $request)
    {
        $authUser = auth()->user();
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|unique:roles,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $role = Role::create(['name' => $request->role_name, 'guard' => 'web', 'restaurant_id' => $authUser->id]);
        $permissions = $request->permission;

        $role->syncPermissions($permissions);

        return redirect()->back()->with('success', trans('layout.message.role_added'));

    }

    public function userPermissionUpdate(Request $request)
    {

        $roleName = $request->role_name;

        foreach ($request->permission as $key => $permission) {

            $role = Role::findById($key);
            $role->syncPermissions($permission);
        }


        return redirect()->back()->with('success', trans('layout.message.permission_update'));

    }

    public function roleDelete(Request $request)
    {
        $authUser = auth()->user();
        $role = Role::where('name', $request->role)->where('restaurant_id', $authUser->id)->first();
        $role->delete();

        return response()->json(['status'=>'success']);


    }
}
