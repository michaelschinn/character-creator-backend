<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class UsersController extends Controller
{
    //
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);
        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid Credentials']);
        }
        $userId = auth()->user()->id;
        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        $event = "login";
        $createdAt = date("l jS \of F Y h:i:s A");
        return response([
            'user' => auth()->user(),
            'success' => true,
            'access_token' => $accessToken,
            'token' => $accessToken,
            'event' => $event,
            'created_at' => $createdAt]
    );
    }
  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email|unique:users',
      'password' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => $validator->errors(),
      ], 401);
    }
    $input = $request->all();
    $input['password'] =bcrypt($input['password']);
    $input['status']=true;
    $input['is_active']=true;
    $user = User::create($input);
    $success['token'] = $user->createToken('appToken')->accessToken;
    $event = "register";
    $createdAt = date("l jS \of F Y h:i:s A");
    return response()->json([
      'success' => true,
      'access_token' => $success,
      'user' => $user,
      'event' => $event,
      'created_at' => $createdAt
    ]);
  }
  public function logout()
  {
    if (Auth::user()) {
      $user = Auth::user()->token();
      $user->revoke();
      return response()->json([
        'success' => true,
        'message' => 'Logout successfully',
      ]);
    } else {
      return response()->json([
        'success' => false,
        'message' => 'Unable to Logout',
      ]);
    }
  }
  public function update(Request $request, $id)
  {
        $user = User::find($id);
        $user->id = $request->id;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->username = $request->name;
        $user->save();
        return $user->toArray();
  }
}
