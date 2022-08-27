<?php

namespace App\Services;

use App\Http\Requests\PlaceBetApiRequest;

class OddsCalculator
{
    public function count(PlaceBetApiRequest $request)
    {
        $selections = $request->selections;

        $odds = [];
        foreach ($selections as $selection) {
            $odds[] = $selection['odds'];
        }
        $totalOddsValue = 1;
        foreach ($odds as $key => $value) {
            $totalOddsValue *= $value;
        }

        $winAmount = $totalOddsValue * floatval($request->stake_amount);

        return $winAmount;
    }
}
