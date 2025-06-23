<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bron extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'venue_id',
        'service_id',
        'event_date',
        'event_time',
        'guests_count',
        'total_price',
        'status',
        'notes',
    ];

    protected $casts = [
        'event_date'   => 'date',
        'event_time'   => 'datetime',
        'total_price'  => 'decimal:2',
        'guests_count' => 'integer',
    ];

    // Status constants
    const STATUS_PENDING   = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString());
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}

