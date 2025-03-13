<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'capacity',
        'location',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
