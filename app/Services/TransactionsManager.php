<?php

namespace App\Services;

use App\Http\Requests\PlaceBetApiRequest;
use App\Models\BalanceTransaction;
use App\Models\Player;

class TransactionsManager
{
    public function make(PlaceBetApiRequest $request)
    {
        $player = Player::find($request->player_id);

        $transaction = BalanceTransaction::create([
            'player_id' => $request->player_id,
            'amount' => $request->stake_amount,
            'amount_before' => $player->balance
        ]);

        $deductedBalance = floatval($player->balance) - floatval($request->stake_amount);

        $player->update(['balance' => $deductedBalance]);

    }
}
