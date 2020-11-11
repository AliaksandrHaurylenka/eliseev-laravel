<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Entity\User;
use Illuminate\Validation\Rule;
use App\Http\Requests\Auth\UpdateUsersRequest;
use App\Http\Requests\Auth\StoreUsersRequest;
use Illuminate\Support\Str;
use App\UseCases\Auth\RegisterService;

class UsersController extends Controller
{

    private $register;

    public function __construct(RegisterService $register)
    {
        $this->register = $register;
        // $this->middleware('can:admin-panel');
        // $this->middleware('can:manage-users');
    }

    
    public function index(Request $request)
    {
        $query = User::orderByDesc('id');

        if (!empty($value = $request->get('id'))) {
            $query->where('id', $value);
        }

        if (!empty($value = $request->get('name'))) {
            $query->where('name', 'like', '%' . $value . '%');
        }

        if (!empty($value = $request->get('email'))) {
            $query->where('email', 'like', '%' . $value . '%');
        }

        if (!empty($value = $request->get('status'))) {
            $query->where('status', $value);
        }

        if (!empty($value = $request->get('role'))) {
            $query->where('role', $value);
        }

        $users = $query->paginate(20);

        $statuses = [
            User::STATUS_WAIT => 'Waiting',
            User::STATUS_ACTIVE => 'Active',
        ];

        $roles = User::rolesList();

        return view('admin.users.index', compact('users', 'statuses', 'roles'));
    }

    
    public function create()
    {
        return view('admin.users.create');
    }

    
    // public function store(Request $request)
    public function store(StoreUsersRequest $request)
    {
        $user = User::new(
            $request['name'],
            $request['email']
        );

        return redirect()->route('admin.users.show', $user);
    }

    
    public function show(User $user)
    {
        // dd($user);
        return view('admin.users.show', compact('user'));
    }

    
    public function edit(User $user)
    {
        $roles = User::rolesList();

        return view('admin.users.edit', compact('user', 'roles'));
    }

   
    
    public function update(UpdateUsersRequest $request, User $user)
    {
        $user->update($request->only(['name', 'email']));

        if ($request['role'] !== $user->role) {
            $user->changeRole($request['role']);
        }

        return redirect()->route('admin.users.show', $user);
    }

    
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index');
    }


    public function verify(User $user)
    {
        $this->register->verify($user->id);

        return redirect()->route('admin.users.show', $user);
    }
}
