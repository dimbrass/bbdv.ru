<?php

namespace App\Models\tz\tek;

use Illuminate\Database\Eloquent\Model;

class Stone extends Model
{
    // Связанная с моделью таблица
    protected $table = 'tz_tek_stones';
  
    public $timestamps = false;
    
    // The attributes that aren't mass assignable.
    protected $guarded = [];
}
