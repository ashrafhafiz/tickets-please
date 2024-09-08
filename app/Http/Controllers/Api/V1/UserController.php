<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\V1\AuthorFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\ReplaceUserRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\Api\V1\TicketResource;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\Ticket;
use App\Models\User;
use App\Policies\Api\V1\UserPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends ApiController
{
    protected $userPolicy = UserPolicy::class;
    /**
     * index
     *
     * Display a listing of the users.
     *
     * @group User Management
     *
     * @param AuthorFilter $filters
     * @return void
     */
    public function index(AuthorFilter $filters)
    {
        return UserResource::collection(User::filter($filters)->paginate());
    }

    /**
     * store
     *
     * Store a newly created user in storage.
     *
     * @group User Management
     *
     * @param StoreUserRequest $request
     * @return void
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $this->isAble('store', User::class);

            return new UserResource(User::create($request->mappedAttributes()));
        } catch (AuthorizationException $authorizationException) {
            return $this->error('You are not authorized to create this user', 401);
        }
    }

    /**
     * show
     *
     * Display the specified user.
     *
     * @group User Management
     *
     * @param User $user
     * @return void
     */
    public function show(User $user)
    {
        if ($this->include('tickets')) {
            return new UserResource($user->load('tickets'));
        }
        return new UserResource($user);
    }

    /**
     * update
     *
     * Update the specified user in storage.
     *
     * @group User Management
     *
     * @param UpdateUserRequest $request
     * @param [type] $user_id
     * @return void
     */
    public function update(UpdateUserRequest $request, $user_id)
    {
        try {
            $user = User::findOrFail($user_id);

            $this->isAble('update', $user);

            $user->update($request->mappedAttributes());

            return new UserResource($user);
        } catch (ModelNotFoundException $exception) {
            return $this->error('User cannot be found.', 404);
        } catch (AuthorizationException $authorizationException) {
            return $this->error('You are not authorized to update this resource', 401);
        }
    }

    /**
     * replace
     *
     * Update the specified resource in storage.
     *
     * @group User Management
     *
     * @param ReplaceUserRequest $request
     * @param [type] $user_id
     * @return void
     */
    public function replace(ReplaceUserRequest $request, $user_id)
    {
        //PUT method
        try {
            $user = User::findOrFail($user_id);

            $this->isAble('replace', $user);

            $user->update($request->mappedAttributes());

            return new UserResource($user);
        } catch (ModelNotFoundException $exception) {
            return $this->error('User cannot be found.', 404);
        } catch (AuthorizationException $authorizationException) {
            return $this->error('You are not authorized to update this resource', 401);
        }
    }

    /**
     * destroy
     *
     * Remove the specified user from storage.
     *
     * @group User Management
     *
     * @param [type] $user_id
     * @return void
     */
    public function destroy($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $this->isAble('delete', $user);
            $user->delete();
            return $this->ok('User deleted successfully');
        } catch (ModelNotFoundException $exception) {
            return $this->error('User cannot be found.', 404);
        } catch (AuthorizationException $authorizationException) {
            return $this->error('You are not authorized to delete this resource', 401);
        }
    }
}
