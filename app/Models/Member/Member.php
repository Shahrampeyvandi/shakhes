<?php

namespace App\Models\Member;

use Illuminate\Database\Eloquent\Model;
use App\Models\Member\Subscribe;
use App\Models\Accounting\Transaction;
use App\Models\Namad\Namad;

class Member extends Model
{
    public function transactions()
    {
        return $this->hasMany(Subscribe::class);
    }

    public function subscribes()
    {
        return $this->hasMany(Transaction::class);
    }

    public function namads()
    {
        return $this->blongsToMany(Namad::class)->withPivot(['amount','profit_loss_percent','price']);
    }
}
