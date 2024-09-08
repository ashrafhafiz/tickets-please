<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\V1\AuthorFilter;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;

class AuthorController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(AuthorFilter $filters)
    {
//        if ($this->include('tickets')) {
//            return UserResource::collection(User::with('tickets')->paginate());
//        }
//        return UserResource::collection(User::paginate());

//        return UserResource::collection(User::filter($filters)->paginate());
        return UserResource::collection(
            User::select('users.*')
            ->join('tickets','users.id', '=', 'tickets.user_id')
            ->filter($filters)
            ->distinct()
            ->paginate()
        );

    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $author)
    {
        //        if ($this->include('tickets')) {
        //            return new UserResource(User::with('ticket')->where('id', $user->id)->first());
        //        }
        //        return new UserResource(User::where('id', $user->id)->first());
        if ($this->include('tickets')) {
            return new UserResource($author->load('tickets'));
        }
        return new UserResource($author);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(UpdateUserRequest $request, User $author)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $author)
    {
        //
    }
}
