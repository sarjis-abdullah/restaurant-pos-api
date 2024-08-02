<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use App\Models\User;
use App\Repositories\Contracts\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private UserInterface $interface;

    public function __construct(UserInterface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): UserResourceCollection
    {
        $data = $this->interface->findBy($request->all());
        return new UserResourceCollection($data);
    }
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user instanceof User) {
            if (Hash::check($request->get('password'), $user->password)) {
                $token = $user->createToken('Password Grant Client')->plainTextToken;

                return response(['accessToken' => $token, 'user' => new UserResource($user)], 200);
            } else {
                return response(['message' => 'password mismatch error'], 422);
            }
        } else {
            return response(['message' => 'No user in this credentials'], 422);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $users = User::create($request->all());
        return new UserResource($users);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
