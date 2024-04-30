<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agent extends Model
{
    use HasFactory;

    protected $table = 'agents';

    protected $fillable = ['name', 'created_at', 'updated_at'];

    public function delayReports(): HasMany
    {
        return $this->hasMany(DelayReport::class);
    }
}
