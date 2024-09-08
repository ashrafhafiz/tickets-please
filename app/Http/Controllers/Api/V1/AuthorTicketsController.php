<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Ticket;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Filters\Api\V1\TicketFilter;
use App\Http\Resources\Api\V1\TicketResource;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthorTicketsController extends ApiController
{
    // use ApiResponses;
    protected $policyClass = TicketPolicy::class;

    public function index($author_id, TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::where('user_id', $author_id)->filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request, $author_id)
    {
        // $model = [
        //     'title' => $request->input('data.attributes.title'),
        //     'description' => $request->input('data.attributes.description'),
        //     'status' => $request->input('data.attributes.status'),
        //     'user_id' => $author_id,
        // ];

        // return new TicketResource(Ticket::create($request->mappedAttributes()));

        try {
            $this->isAble('store', Ticket::class);

            return new TicketResource(Ticket::create($request->mappedAttributes([
                'author' => 'user_id',
            ])));
        } catch (AuthorizationException $authorizationException) {
            return $this->error('You are not authorized to update this resource', 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $author_id)
                ->firstOrFail();

            if ($this->include('author')) {
                return new TicketResource($ticket->load('user'));
            }

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, $author_id, $ticket_id)
    {
        //PATCH method
        try {
            // $ticket = Ticket::findOrFail($ticket_id);

            // if ($ticket->user_id == $author_id) {

            //     $ticket->update($request->mappedAttributes());
            //     return new TicketResource($ticket);
            // }
            // // TODO: Ticket does not  belong to the user

            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $author_id)
                ->firstOrFail();

            $this->isAble('update', $ticket);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        } catch (AuthorizationException $authorizationException) {
            return $this->error('You are not authorized to update this resource', 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function replace(ReplaceTicketRequest $request, $author_id, $ticket_id)
    {
        //PUT method
        try {
            // $ticket = Ticket::findOrFail($ticket_id);

            // if ($ticket->user_id == $author_id) {

            //     // $model = [
            //     //     'title' => $request->input('data.attributes.title'),
            //     //     'description' => $request->input('data.attributes.description'),
            //     //     'status' => $request->input('data.attributes.status'),
            //     //     'user_id' => $request->input('data.relationship.author.data.id'),
            //     // ];
            //     $ticket->update($request->mappedAttributes());
            //     return new TicketResource($ticket);
            // }

            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $author_id)
                ->firstOrFail();

            $this->isAble('replace', $ticket);

            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        } catch (AuthorizationException $authorizationException) {
            return $this->error('You are not authorized to update this resource', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $author_id)
                ->firstOrFail();

            $this->isAble('update', $ticket);
            $ticket->delete();
            return $this->ok('Ticket deleted successfully');
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        } catch (AuthorizationException $authorizationException) {
            return $this->error('You are not authorized to delete this resource', 401);
        }
    }
}
