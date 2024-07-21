<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\API\V1\ReplaceTicketRequest;
use App\Http\Requests\API\V1\StoreTicketRequest;
use App\Http\Requests\API\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends ApiController
{
    protected $policyClass = TicketPolicy::class;

    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }

      /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        try {
            $user = User::findOrFail($request->input('data.relationships.author.data.id'));

            $this->isAble('store', null);
            // Todo create ticket
        } catch (ModelNotFoundException $exception) {
            return $this->ok('User not found', [
                'error' => 'The provided user id does not exists.'
            ]);
        }
        return new TicketResource($request->mappedAttributes());

    }

    /**
     * Display the specified resource.
     */
    public function show($ticket_id)
    {

        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if ($this->include('author')) {
                return new TicketResource($ticket->load('user'));
            }
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        }


        return new TicketResource($ticket);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, $ticket_id)
    {
        // PATCH
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            // policy
            $this->isAble('update', $ticket);
            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        } catch (AuthorizationException $ex) {
            return $this->error('Your are not authorized to update that resource', 401);
        }
    }
    public function replace(ReplaceTicketRequest $request, $ticket_id)
    {
        // PUT
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            $this->isAble('replace', $ticket);
            $ticket->update($request->mappedAttributes());
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

            $this->isAble('delete', $ticket);

            $ticket->delete();
            return $this->ok('Ticket successfully deleted');
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        }
    }
}
