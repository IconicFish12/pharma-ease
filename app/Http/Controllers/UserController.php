<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('user_id', 'like', "%{$search}%");
            });
        }

        $limit = $request->input('paginate', 15);
        $data = $query->paginate($limit);

        // ubah datanya menjadi Resource Collection HANYA jika request meminta JSON (API)
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'User Data fetched Successfully',
                'data' => UserResource::collection($data),
                'pagination' => [
                    'total' => $data->total(),
                    'per_page' => $data->perPage(),
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                ]
            ]);
        }

        return view('admin.users.users_management', [
            'title' => 'User Management',
            'mainHeader' => 'User Management',
            'subHeader' => 'List Management User / Pegawai Apotek Lamtama',
            'dataArr' => $data,
            'roles' => ['admin', 'owner', 'pharmacist', 'cashier'],
            'shifts' => ['pagi', 'siang', 'malam'],
        ]);
    }

    public function create()
    {

    }

    public function store(StoreUserRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['password'] = Hash::make($validated['password']);

            User::create($validated);

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'User created successfully',
                ]);
            }
            return redirect()->back()->with('success', 'User created successfully');

        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Failed: ' . $e->getMessage())->withInput();
        }
    }

    public function show(User $user)
    {

    }

    public function edit(User $user)
    {

    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $validated = $request->validated();

            if (empty($validated['password'])) {
                unset($validated['password']);
            } else {
                $validated['password'] = Hash::make($validated['password']);
            }

            $user->update($validated);

            if ($request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => 'User updated successfully']);
            }
            return redirect()->back()->with('success', 'User updated successfully');

        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Failed: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();

            if (request()->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => 'User deleted successfully']);
            }
            return redirect()->back()->with('success', 'User deleted successfully');

        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Failed: ' . $e->getMessage());
        }
    }
}