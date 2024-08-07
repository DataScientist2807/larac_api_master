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
            if ($this->isAble('store', Ticket::class)) {
                return new TicketResource(Ticket::create($request->mappedAttributes()));
            }
            return $this->error('Your are not authorized to create that resource', 401);
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
            if ($this->isAble('update', $ticket)) {
                $ticket->update($request->mappedAttributes());
                return new TicketResource($ticket);
            }
            return $this->error('Your are not authorized to update that resource', 401);

        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        }
    }
    public function replace(ReplaceTicketRequest $request, $ticket_id)
    {
        // PUT
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            if ($this->isAble('replace', $ticket)) {
                $ticket->update($request->mappedAttributes());
                return new TicketResource($ticket);
            }
            return $this->error('Your are not authorized to update that resource', 401);
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
            if ($this->isAble('delete', $ticket)) {
                $ticket->delete();
            return $this->ok('Ticket successfully deleted');
            }
            return $this->error('Your are not authorized to delete that resource', 401);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found.', 404);
        }
    }
}
