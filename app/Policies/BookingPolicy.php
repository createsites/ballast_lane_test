<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookingPolicy
{
    /**
     * Determine whether the user owns the model.
     */
    public function owner(User $user, Booking $booking): bool
    {
        return $booking->user_id === $user->id;
    }
}
