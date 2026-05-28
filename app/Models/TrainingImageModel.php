<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingImageModel extends Model
{
    protected $table = 'training_images';
    protected $fillable = [
        'user_id',
        'image',
        'descriptor'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
