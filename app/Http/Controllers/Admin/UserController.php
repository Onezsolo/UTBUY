<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'suspended' => User::where('is_active', false)->count(),
            'admins' => User::where('role', 'admin')->count(),
        ];

        $users = User::query()
            ->withCount('orders')
            ->withSum(['orders as spent' => fn ($q) => $q->where('payment_status', 'paid')], 'total')
            ->latest()
            ->paginate(20);

        return view('admin.users', compact('users', 'stats'));
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
        ]);
        $user->role = $request->role;
        $user->save();

        return back()->with('status', 'User added successfully.');
    }

    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        abort_if($user->id === $request->user()->id, 403);

        $user->is_active = ! $user->is_active;
        $user->save();

        return back()->with('status', $user->is_active ? 'User activated.' : 'User suspended.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_if($user->id === $request->user()->id, 403);

        $user->delete();

        return back()->with('status', 'User deleted successfully.');
    }
}
