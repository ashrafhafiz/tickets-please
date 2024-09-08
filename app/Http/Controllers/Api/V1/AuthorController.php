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
     * index
     *
     * Display a listing of the authors.
     *
     * @group Author Management
     *
     * @param AuthorFilter $filters
     * @return void
     */
    public function index(AuthorFilter $filters)
    {
        return UserResource::collection(
            User::select('users.*')
                ->join('tickets', 'users.id', '=', 'tickets.user_id')
                ->filter($filters)
                ->distinct()
                ->paginate()
        );
    }

    /**
     * show
     *
     * Display the specified author.
     *
     * @group Author Management
     * @param AuthorFilter $filters
     * @return void
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
}
