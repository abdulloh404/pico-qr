<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallWaiter extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class)->withDefault();
    }
    public function table(){
        return $this->belongsTo(Table::class,'table_id', 'id')->withDefault();
    }
}
