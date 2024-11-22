<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $table = 'routes';
    protected $fillable = ['name', 'direction'];

    public function stops()
    {
        return $this->belongsToMany(Stop::class, 'route_stops', 'route_id', 'stop_id')
                    ->withPivot('order');
    }
}
