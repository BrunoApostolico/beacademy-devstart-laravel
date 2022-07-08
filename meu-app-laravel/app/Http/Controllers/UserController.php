<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateUserFormRequest;
use Illuminate\Http\Request;
use App\Models\User;
use function GuzzleHttp\default_user_agent;

class UserController extends Controller
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function index()
    {
//        $users = User::all();
        $users = User::paginate(5);
        return view ('users.index', compact('users'));

       // dd($users); //dump e die
    }

    public function show($id)
    {
        if (!$user = User::find($id))
            return redirect()->route('users.index');

        $title = 'Usuario '.$user->name;
        return view('users.show', compact('user','title'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUpdateUserFormRequest $request)
    {
//        $user = new User;
//        $user->name = $request->name;
//        $user->email = $request->email;
//        $user->password = bcrypt($request->password);
//        $user->save();

        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        if($request->image){
            $file = $request['image'];
            $path = $file->store('profile','public');
            $data['image'] = $path;
        }


        $this->model->create($data);

        return redirect()->route('users.index');
    }

    public function edit($id)
    {
        if(!$user = $this->model->find($id))
            return redirect()->route('users.index');

        return view('users.edit', compact('user'));
    }

    public function update(StoreUpdateUserFormRequest $request, $id)
    {
        if(!$user = $this->model->find($id))
            return redirect()->route('users.index');
        $data = $request->only('name','email'); //usar o metodo all para form com mais campos

        if($request->password)
            $data['password']= bcrypt($request->password);
        $user->update($data);

        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        if(!$user = $this->model->find($id))
            return redirect()->route('users.index');

        $user->delete();

        return redirect()->route('users.index');

    }
}
