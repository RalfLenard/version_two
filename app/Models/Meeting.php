<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'adoption_request_id',
        'meeting_date',
        'status',
        'user_id', // Ensure this is in $fillable
    ];

    public function adoptionRequest()
    {
        return $this->belongsTo(AdoptionRequest::class, 'adoption_request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function animalAbuseReport()
    {
        return $this->belongsTo(AnimalAbuseReport::class, 'animal_abuse_report_id');
    }
}
