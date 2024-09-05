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

class TicketController extends ApiController
{
    use ApiResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(TicketFilter $filters)
    {
//        if ($this->include('author')) {
//            return TicketResource::collection(Ticket::with('user')->paginate());
//        }
//        return TicketResource::collection(Ticket::paginate());
//        dd($filters);

        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        try {
            $user = User::findOrFail($request->input('data.relationship.author.data.id'));
        } catch (ModelNotFoundException $exception) {
            return $this->ok('User not found.', [
                'error' => 'The provided user id does not exist.'
            ]);
        }

        $model = [
            'title' => $request->input('data.attributes.title'),
            'description' => $request->input('data.attributes.description'),
            'status' => $request->input('data.attributes.status'),
            'user_id' => $request->input('data.relationship.author.data.id'),
        ];

        return new TicketResource(Ticket::create($model));
    }

    /**
     * Display the specified resource.
     */
    public function show($ticket_id)
    {
//        if ($this->include('author')) {
//            return new TicketResource(Ticket::with('user')->where('id',$ticket->id)->first());
//        }
//        return new TicketResource(Ticket::where('id',$ticket->id)->first());

        try {
            $ticket = Ticket::findOrFail($ticket_id);

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
    public function update(UpdateTicketRequest $request, $ticket_id)
    {
        // PATCH method
    }

    /**
     * Update the specified resource in storage.
     */
    public function replace(ReplaceTicketRequest $request, $ticket_id)
    {
        //PUT method
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            $model = [
                'title' => $request->input('data.attributes.title'),
                'description' => $request->input('data.attributes.description'),
                'status' => $request->input('data.attributes.status'),
                'user_id' => $request->input('data.relationship.author.data.id'),
            ];

            $ticket->update($model);

            return new TicketResource($ticket);

        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            $ticket->delete();
            return $this->ok('Ticket deleted successfully');

        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        }
    }
}
