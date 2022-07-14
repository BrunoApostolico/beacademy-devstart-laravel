<?php

namespace App\Http\Controllers;

use App\Exceptions\UserControllerException;
use App\Http\Requests\StoreUpdateUserFormRequest;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Models\User;
use function GuzzleHttp\default_user_agent;

class UserController extends Controller
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function index(Request $request)
    {

        $users = $this->model->getUsers(
            $request->search ?? ''
        );

        return view ('users.index', compact('users'));
    }

    public function show($id)
    {
//        if (!$user = User::find($id))
//            return redirect()->route('users.index');

        $user = User::find($id);

        if($user){
            return view('users.show',compact('user'));
        }else{
            throw new UserControllerException('User não encontrado');
        }

//        return view('users.show', compact('user'));
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

        $request->session()->flash('create', 'Usuário cadastrado com sucesso');
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

        $data['is_admin'] = $request->admin ? 1 : 0;

        $user->update($data);

        return redirect()->route('users.index')->with('edit','Usuário atualizado com sucesso!');
    }

    public function destroy($id)
    {
        if(!$user = $this->model->find($id))
            return redirect()->route('users.index');

        $user->delete();

        return redirect()->route('users.index')->with('destroy','Usuário deletado com sucesso!');

    }
    public function admin()
    {
        return view('admin.index');
    }
}
