<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSched extends Model
{
    use HasFactory;

    protected $table = "doctor_sched";
    protected $fillable = [
        'doctorsId',
        'day',
        'allotedTime',
        'maxPatient',
        'status'
    ];
}
