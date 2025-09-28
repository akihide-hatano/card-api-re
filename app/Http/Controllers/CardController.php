<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Models\Card;
use Illuminate\Http\Request;
use App\Http\Resources\CardResource;
use PhpParser\Node\Stmt\TryCatch;

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
        $card->update($request->validated());
        return new CardResource($card->refresh());
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
