<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceBetApiRequest;
use App\Models\Bet;
use App\Models\BetSelection;
use App\Models\Player;
use App\Services\CustomValidator;
use App\Services\OddsCalculator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class BetApiController extends Controller
{
    public function __construct(
        OddsCalculator $oddsCalculator,
    )
    {
        $this->OddsCalculator = $oddsCalculator;
    }

    public function placeBet(PlaceBetApiRequest $request)
    {

//        if (Player::find($request->player_id) === null) {
//            Player::create([
//                'name' => 'Random',
//                'email' => 'Random',
//                'password' => Random,
//                'balance' => "1000"
//
//            ]);
//        }

        $stake = Bet::create(["stake_amount" => $request->stake_amount]);

        $odds = $this->OddsCalculator->count($request);

        if ($odds * floatval($request->stake_amount) > 20000) {
            return response()->json(['message' => 'stake amount is too large']);
        }

        $validator = Validator::make($request->all(), [
            'selections.*.odds' => ['required', 'numeric', 'between:1,10000'],
            'selections.*.id' => 'distinct',
        ], ['selections.*.id.distinct' => 'Duplicate selection found']);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'selection_errors' => $errors
            ], 201);
        }

        $selections = $request->selections;

        foreach ($selections as $selection) {
            BetSelection::create([
                "selection_id" => $selection['id'],
                "bet_id" => $stake->id,
                "odds" => $selection['odds'],
            ]);
        }

        return response()->json(['message' => 'Your bet is placed'], 201);
    }
}
