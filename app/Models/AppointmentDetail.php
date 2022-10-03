<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class AppointmentDetail extends Model
{

    use InteractsWithMedia,HasFactory;

    protected $table = 'appointment_details';

    /**
     * @var array
     */
    protected $fillable = [
        'vcard_id',
        'is_paid',
        'price',
    ];

}
