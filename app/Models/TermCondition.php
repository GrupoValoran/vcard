<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\TermCondition
 *
 * @method static Builder|TermCondition newModelQuery()
 * @method static Builder|TermCondition newQuery()
 * @method static Builder|TermCondition query()
 * @mixin Eloquent
 * @property int $id
 * @property string $term_condition
 * @property int $vcard_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|TermCondition whereCreatedAt($value)
 * @method static Builder|TermCondition whereId($value)
 * @method static Builder|TermCondition whereTermCondition($value)
 * @method static Builder|TermCondition whereUpdatedAt($value)
 * @method static Builder|TermCondition whereVcardId($value)
 */
class TermCondition extends Model
{
    use HasFactory;

    protected $fillable = [
        'term_condition',
        'vcard_id',
    ];
    
    public $table = 'term_conditions';

    const TERM_CONDITION = 'term-condition';

    /**
     *
     * @return BelongsTo
     */
    public function vcard()
    {
        return $this->belongsTo(Vcard::class, 'vcard_id');
    }
}
