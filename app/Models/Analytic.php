<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Analytic
 *
 * @property int $id
 * @property string|null $session
 * @property int|null $vcard_id
 * @property string|null $uri
 * @property string|null $source
 * @property string|null $country
 * @property string|null $browser
 * @property string|null $device
 * @property string|null $operating_system
 * @property string|null $ip
 * @property string|null $language
 * @property string|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Vcard|null $vcard
 * @method static \Illuminate\Database\Eloquent\Builder|Analytic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Analytic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Analytic query()
 * @method static \Illuminate\Database\Eloquent\Builder|Analytic whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytic whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytic whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytic whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytic whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytic whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytic whereOperatingSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytic whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytic whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytic whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytic whereUri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analytic whereVcardId($value)
 * @mixin \Eloquent
 */
class Analytic extends Model
{
    use HasFactory;

    protected $table = 'analytics';

    protected $fillable = [
        'session',
        'vcard_id',
        'uri',
        'source',
        'country',
        'state',
        'browser',
        'device',
        'operating_system',
        'ip',
        'language',
        'meta'
    ];
    
    public function vcard(): BelongsTo
    {
        return $this->belongsTo(Vcard::class, 'vcard_id', 'id');
    }
}
