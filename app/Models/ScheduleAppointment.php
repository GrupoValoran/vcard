<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * App\Models\ScheduleAppointment
 *
 * @property int $id
 * @property int $vcard_id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string $date
 * @property string $from_time
 * @property string $to_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Vcard $vcard
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleAppointment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleAppointment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleAppointment query()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleAppointment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleAppointment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleAppointment whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleAppointment whereFromTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleAppointment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleAppointment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleAppointment wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleAppointment whereToTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleAppointment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleAppointment whereVcardId($value)
 * @mixin \Eloquent
 */
class ScheduleAppointment extends Model 
{
    use HasFactory;

    protected $table = 'schedule_appointments';

    protected $fillable = [
        'name',
        'email',
        'date',
        'phone',
        'from_time',
        'to_time',
        'vcard_id',
        'appointment_tran_id',
        'status'
    ];

    public static $rules = [
        'name'    => 'required|min:2',
        'email'   => 'required|email:filter',
        'phone'   => 'nullable|numeric|min:0',
    ];
    
    const FREE = 0;
    const PAID = 1;
    const ALL = 3;
    
    const TYPES = [
        self::ALL => 'All',
        self::FREE => 'Free',
        self::PAID => 'Paid',
    ];
    const PENDING = 0;
    const COMPLETED = 1;
    const STATUS_ALL = 3;
    
    
    const STATUS =[
        self::STATUS_ALL => 'All',
        self::PENDING =>'Pending',
        self::COMPLETED =>'Completed'
    ];
    
    protected $appends = ['paid_amount'];
    
    /**
     * @return string
     */
    public function getPaidAmountAttribute(): string
    {
        $transaction = $this->appointmentTransaction;
        if($transaction){
            return $transaction->currency_symbol.''.$transaction->amount;
        }
        
        return '';
    }
    
    /**
     *
     *
     * @return BelongsTo
     */
    public function vcard(): BelongsTo
    {
        return $this->belongsTo(Vcard::class);
    }

    /**
     *
     *
     * @return BelongsTo
     */
    public function appointmentTransaction(): BelongsTo
    {
        return $this->belongsTo(AppointmentTransaction::class, 'appointment_tran_id');
    }
    
}
