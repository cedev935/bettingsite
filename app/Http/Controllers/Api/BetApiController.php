<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceBetApiRequest;
use App\Models\Bet;
use App\Models\BetSelection;
use Illuminate\Support\Facades\Validator;


class BetApiController extends Controller
{
    public function placeBet(PlaceBetApiRequest $request)
    {
        $stake = Bet::create(["stake_amount" => $request->stake_amount]);

        $selections = $request->selections;
    // need to make service calculate max_win_amount
        $odds = [];
        foreach ($selections as $selection) {
            $odds[] = $selection['odds'] ;
        }
        $product = 1;
        foreach ($odds as $key => $value) {
            $product *= $value;
        }
        dd($product);

        foreach ($selections as $selection) {
            BetSelection::create([
                "selection_id" => $selection['id'],
                "bet_id" => $stake->id,
                "odds" => $selection['odds'],
            ]);
        }

        $validator = Validator::make($request->all(), [
            'selections.*.odds' => ['required', 'numeric', 'between:1,10000'],
            'selections.*.id' => 'distinct',
        ], ['selections.*.id.distinct' => 'Duplicate selection found']);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['message' => $errors], 201);
        }

        return response()->json(['message' => 'Your bet is placed'], 201);
    }
}
