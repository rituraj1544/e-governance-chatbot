<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'citizen')->select('id', 'name', 'email', 'status', 'created_at');
        if ($search = trim((string) $request->query('search'))) {
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
        }
        return $query->latest()->paginate(20);
    }

    public function updateStatus(Request $request, User $user)
    {
        abort_if($user->role !== 'citizen', 404);
        $data = $request->validate(['status' => ['required', 'in:active,suspended']]);
        $user->update($data);
        return $user->only('id', 'name', 'email', 'status', 'created_at');
    }
}
