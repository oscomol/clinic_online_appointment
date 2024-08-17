<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    use HasFactory;
    protected $table = "email_verification";
    protected $fillable = [
        'email', 'token', 'expires_at', 'userType'
    ];

    public $timestamps = true;
    protected $dates = ['expires_at'];
}
