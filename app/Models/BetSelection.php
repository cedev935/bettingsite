<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BetSelection extends Model
{
    use HasFactory;

    protected $fillable = ['bet_id', 'stake_amount', 'selection_id', 'odds'];
}
