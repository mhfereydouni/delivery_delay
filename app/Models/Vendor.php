<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';

    protected $fillable = ['name', 'created_at', 'updated_at'];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function trips(): HasManyThrough
    {
        return $this->hasManyThrough(Trip::class, Order::class);
    }
}
