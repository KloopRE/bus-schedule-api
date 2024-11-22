<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    use HasFactory;

    protected $table = 'stops';
    protected $fillable = ['name', 'location'];

    public function routes()
    {
        return $this->belongsToMany(Route::class, 'route_stops', 'stop_id', 'route_id')
                    ->withPivot('order');
    }
}


