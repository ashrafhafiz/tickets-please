<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\Api\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\Api\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use App\Policies\Api\V1\TicketPolicy;
use App\Traits\ApiResponses;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends ApiController
{
    protected $policyClass = TicketPolicy::class;

    /**
     * index
     *
     * Get all tickets. Display a listing of the resource.
     *
     * @group Ticket Management
     *
     * @param TicketFilter $filters
     * @return void
     */
    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }

    /**
     * store
     *
     * Store a newly created resource in storage.
     *
     * @group Ticket Management
     *
     * @param StoreTicketRequest $request
     * @return void
     */
    public function store(StoreTicketRequest $request)
    {
        if ($this->isAble('store', Ticket::class)) {
            return new TicketResource(Ticket::create($request->mappedAttributes()));
        }
        return $this->notAuthorized('You are not authorized to update this resource');
    }

    /**
     * show
     *
     * Display the specified resource.
     *
     * @group Ticket Management
     *
     * @param Ticket $ticket
     * @return void
     */
    public function show(Ticket $ticket)
    {
        if ($this->include('author')) {
            return new TicketResource($ticket->load('user'));
        }

        return new TicketResource($ticket);
    }

    /**
     * update
     *
     * Update the specified resource in storage.
     *
     * @group Ticket Management
     *
     * @param UpdateTicketRequest $request
     * @param Ticket $ticket
     * @return void
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        // PATCH Method
        if ($this->isAble('update', $ticket)) {
            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);
        }
        return $this->notAuthorized('You are not authorized to update this resource');
    }

    /**
     * replace
     *
     * Replace the specified resource in storage.
     *
     * @group Ticket Management
     *
     * @param ReplaceTicketRequest $request
     * @param Ticket $ticket
     * @return void
     */
    public function replace(ReplaceTicketRequest $request, Ticket $ticket)
    {
        //PUT method
        if ($this->isAble('replace', $ticket)) {
            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);
        }
        return $this->notAuthorized('You are not authorized to update this resource');
    }

    /**
     * destroy
     *
     * Remove the specified resource from storage.
     *
     * @group Ticket Management
     *
     * @param Ticket $ticket
     * @return void
     */
    public function destroy(Ticket $ticket)
    {
        if ($this->isAble('delete', $ticket)) {
            $ticket->delete();
            return $this->ok('Ticket deleted successfully');
        }
        return $this->notAuthorized('You are not authorized to delete this resource');
    }
}