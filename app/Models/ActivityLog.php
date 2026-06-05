<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';
    
    protected $fillable = [
        'user_id',
        'action',
        'details',
        'ip_address',
        'user_agent'
    ];
    
    protected $casts = [
        'details' => 'array'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}