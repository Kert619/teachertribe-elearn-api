<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request){
        $request->validated($request->all());

        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            return $this->error(null, 'Credentials do not match our records', 401);
        }

        $user = User::where('email', $request->email)->first();

        return $this->success([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'token' => $user->createToken('API TOKEN OF ' . $user->name)->plainTextToken,
            'roles' => $user->roles->pluck('code')->toArray(),
            'permissions' => collect($user->permissions())->pluck('code')->toArray(),
            'levels_passed' => $user->levels
        ], 'Login success');
    }

    public function logout(Request $request){
       $request->user()->tokens()->delete();
        return $this->success(null,'You have been logged out');
    }

    public function me(Request $request){
        return $this->success([
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email
            ],
            'roles' => $request->user()->roles->pluck('code')->toArray(),
            'permissions' => collect($request->user()->permissions())->pluck('code')->toArray(),
            'levels_passed' => $request->user()->levels
        ]);
    }
}
