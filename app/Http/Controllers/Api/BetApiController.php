<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceBetApiRequest;
use App\Models\Bet;
use App\Models\BetSelection;
use Illuminate\Http\Request;


class BetApiController extends Controller
{
    public function placeBet(PlaceBetApiRequest $request)
    {
        $stake = Bet::create(["stake_amount" => $request->stake_amount]);

        $selections = $request->selections;

        foreach ($selections as $selection) {
            BetSelection::create([
                "selection_id" => $selection['id'],
                "bet_id" => $stake->id,
                "odds" => $selection['odds']
            ]);
        }

        return response()->json(['message' => 'Your bet is placed'], 201);
    }
}
