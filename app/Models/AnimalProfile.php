<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalProfile extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'animal_profiles';

    // The attributes that are mass assignable
    protected $fillable = [
        'name',
        'species',
        'profile_picture',
        'description',
        'age',
        'medical_records',
        
    ];

    public function adoptionRequests()
    {
        return $this->hasMany(AdoptionRequest::class, 'animal_id');
    }
}

