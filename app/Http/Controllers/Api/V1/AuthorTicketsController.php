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
use App\Traits\ApiResponses;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AuthorTicketsController extends ApiController
{
    use ApiResponses;
    public function index($author_id, TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::where('user_id', $author_id)->filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($author_id, StoreTicketRequest $request)
    {
        // $model = [
        //     'title' => $request->input('data.attributes.title'),
        //     'description' => $request->input('data.attributes.description'),
        //     'status' => $request->input('data.attributes.status'),
        //     'user_id' => $author_id,
        // ];

        return new TicketResource(Ticket::create($request->mappedAttributes()));
    }

    /**
     * Display the specified resource.
     */
    public function show($author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if ($ticket->user_id == $author_id) {
                if ($this->include('author')) {
                    return new TicketResource($ticket->load('user'));
                }

                return new TicketResource($ticket);
            }

            return $this->error('Ticket is not recognized.', 404);
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
            $ticket = Ticket::findOrFail($ticket_id);

            if ($ticket->user_id == $author_id) {

                $ticket->update($request->mappedAttributes());
                return new TicketResource($ticket);
            }
            // TODO: Ticket does not  belong to the user

        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function replace(ReplaceTicketRequest $request, $author_id, $ticket_id)
    {
        //PUT method
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if ($ticket->user_id == $author_id) {

                // $model = [
                //     'title' => $request->input('data.attributes.title'),
                //     'description' => $request->input('data.attributes.description'),
                //     'status' => $request->input('data.attributes.status'),
                //     'user_id' => $request->input('data.relationship.author.data.id'),
                // ];
                $ticket->update($request->mappedAttributes());
                return new TicketResource($ticket);
            }
            // TODO: Ticket does not  belong to the user

        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if ($ticket->user_id == $author_id) {
                $ticket->delete();
                return $this->ok('Ticket deleted successfully');
            }

            return $this->error('Ticket is not recognized.', 404);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        }
    }
}
