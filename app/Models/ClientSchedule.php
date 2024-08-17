<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSchedule extends Model
{
    use HasFactory;
    protected $table = "client_schedule";
    protected $fillable = [
        'doctorsId',
        'userId',
        'date',
        'expectedTime',
        'number',
        'age',
        'patientName',
        'gender',
        'address',
        'concern',
        'severity',
        'status'
    ];
}
