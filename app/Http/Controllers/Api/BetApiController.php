<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceBetApiRequest;
use App\Models\Bet;
use App\Models\BetSelection;
use App\Models\Player;
use App\Services\OddsCalculator;
use App\Services\TransactionsManager;
use Illuminate\Support\Facades\Validator;


class BetApiController extends Controller
{
    const MAX_WIN_AMOUNT = 20000;

    public function __construct(
        OddsCalculator      $oddsCalculator,
        TransactionsManager $transactionsManager
    )
    {
        $this->OddsCalculator = $oddsCalculator;
        $this->TransactionsManager = $transactionsManager;
    }

    public function placeBet(PlaceBetApiRequest $request)
    {
        $playerBalance = Player::find($request->player_id)->balance;

        if (floatval($playerBalance) < floatval($request->stake_amount)) {
            return response()->json(['message' => ' not enough balance to make this bet']);
        }

        $validator = Validator::make($request->all(), [
            'selections.*.odds' => ['required', 'numeric', 'between:1,10000'],
            'selections.*.id' => 'distinct',
        ], ['selections.*.id.distinct' => 'Duplicate selection found']);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'selection' => $errors
            ]);
        }

        $maxWinAmount = $this->OddsCalculator->count($request);

        if ($maxWinAmount > self::MAX_WIN_AMOUNT) {
            return response()->json([
                'message' => 'Maximum win amount is to large',
                'maximum_amount' => self::MAX_WIN_AMOUNT]);
        }

        $selections = $request->selections;

        $stake = Bet::create(['stake_amount' => $request->stake_amount]);

        foreach ($selections as $selection) {
            BetSelection::create([
                "selection_id" => $selection['id'],
                "bet_id" => $stake->id,
                "odds" => $selection['odds'],
            ]);
        }

        $this->TransactionsManager->make($request);

        return response()->json(['message' => 'Your bet is placed'], 201);
    }
}
