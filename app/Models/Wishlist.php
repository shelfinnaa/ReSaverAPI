<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'waiting_period',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
