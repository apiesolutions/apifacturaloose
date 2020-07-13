<?php
namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Resources\UserCollection;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function columns()
    {
        return [
            'name' => 'Nombre'
        ];
    }

    public function records(Request $request)
    {
        $records = User::where($request->column, 'like', "%{$request->value}%")
            ->where('role', '<>', 'admin')
            ->orderBy('name');

        return new UserCollection($records->paginate(env('ITEMS_PER_PAGE', 5)));
    }

    public function record($id)
    {
        $record = new UserResource(User::findOrFail($id));

        return $record;
    }

    public function store(UserRequest $request)
    {
        $id = $request->input('id');
        $user = User::firstOrNew(['id' => $id]);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if (!$id) {
            $user->api_token = str_random(50);
            $user->password = bcrypt($request->input('password'));
            $user->role = 'user';
        }  elseif ($request->input('password') !== '') {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();

        return [
            'success' => true,
            'message' => ($id)?'Usuario actualizado':'Usuario registrado'
        ];
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return [
            'success' => true,
            'message' => 'Usuario eliminado con éxito'
        ];
    }
}