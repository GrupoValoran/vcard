<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentTransaction extends Model
{
    use HasFactory;

    protected $table = 'appointment_transactions';

    /**
     * @var array
     */
    protected $fillable = [
        'vcard_id',
        'transaction_id',
        'currency_id',
        'amount',
        'tenant_id',
        'status',
        'type',
        'meta',
    ];

    protected $appends = ['currency_symbol'];

    /**
     *
     *
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    /**
     *
     *
     * @return mixed
     */
    public function getCurrencySymbolAttribute()
    {
        return $this->currency->currency_icon;
    }
}
