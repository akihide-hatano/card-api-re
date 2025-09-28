<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Models\Card;
use Illuminate\Http\Request;
use App\Http\Resources\CardResource;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Card::latest('id')->get(),200
        );
    }

    /** POST /api/v1/cards */
    public function store(StoreCardRequest $request)
    {
        $card = Card::create($request->validated());

        return (new CardResource($card))
            ->response()
            ->setStatusCode(201)
            ->header('Location', route('cards.show', $card));
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        return new CardResource($card);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCardRequest $request, Card $card)
    {
        $card->update($request->validate());
        return new CardResource($card);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        $card->delete();
        return response()->noContent();
    }
}
