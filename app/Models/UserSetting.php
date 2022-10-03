<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class UserSetting extends Model
{
    
    use InteractsWithMedia,HasFactory;

    protected $table = 'user_settings';

    /**
     * @var array
     */
    protected $fillable = [
        'currency_id',
        'user_id',
        'key',
        'value',
    ];
    
    const HOUR_24 = 1;
    const HOUR_12 = 0;
        
    const TIME_FORMAT = [
        self::HOUR_24,
        self::HOUR_12,
       
        
    ];

}
