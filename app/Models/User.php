<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; 

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'DNI',
        'number',
        'password',
        'adress',
        'last_buy',
        'fav_food',
        'fav_drink',
    ];

    public function tokens()
    {
        return $this->hasMany(UserToken::class);
    }

    public function generateToken()
    {
        $plain = Str::random(60);

        $this->tokens()->create([
            'token' => hash('sha256', $plain),
        ]);

        return $plain;
    }
}
