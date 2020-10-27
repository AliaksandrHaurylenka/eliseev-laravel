<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Entity\User;
use Illuminate\Validation\Rule;
use App\Http\Requests\Auth\UpdateUsersRequest;
use App\Http\Requests\Auth\StoreUsersRequest;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    
    public function create()
    {
        return view('admin.users.create');
    }

    
    // public function store(Request $request)
    public function store(StoreUsersRequest $request)
    {
        $user = User::create($request->only(['name', 'email']) + [
            'password' => bcrypt(Str::random()),
            'status' => User::STATUS_ACTIVE,
        ]);

        return redirect()->route('admin.users.show', $user);
    }

    
    public function show(User $user)
    {
        // dd($user);
        return view('admin.users.show', compact('user'));
    }

    
    public function edit(User $user)
    {
        $statuses = [
            User::STATUS_WAIT => 'Waiting',
            User::STATUS_ACTIVE => 'Active',
        ];

        return view('admin.users.edit', compact('user', 'statuses'));
    }

   
    
    public function update(UpdateUsersRequest $request, User $user)
    {
        $user->update($request->only(['name', 'email', 'status']));

        return redirect()->route('admin.users.show', $user);
    }

    
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index');
    }
}
