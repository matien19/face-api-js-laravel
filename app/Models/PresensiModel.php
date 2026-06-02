<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiModel extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'user_id',
        'waktu_masuk',
        'status'
    ];

    protected $casts = [
        'waktu_masuk' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
