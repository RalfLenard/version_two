<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdoptionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id',
        'user_id',
        'admin_id',  // Ensure admin_id is fillable if you're assigning it
        'animal_name',
        'first_name',
        'last_name',
        'gender',
        'phone_number',
        'address',
        'salary',
        'question1',
        'question2',
        'question3',
        'valid_id',
        'valid_id_with_owner',
        'reason',  // Add reason for rejection if needed
        'status',  // Add status if you're tracking the request status
    ];

    // Relationship with the AnimalProfile model
    public function animalProfile()
    {
        return $this->belongsTo(AnimalProfile::class, 'animal_id');
    }

    // Relationship with the User model (for the user who made the request)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with the Meeting model
    public function meeting()
    {
        return $this->hasOne(Meeting::class, 'adoption_request_id');
    }

    // Relationship with the User model (for the admin who handled the request)
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id')->where('usertype', 'admin');
    }

    // In AdoptionRequest.php
    public function animal() {
        return $this->belongsTo(AnimalProfile::class);
    }

    
}
