<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Meta
 *
 * @property int $id
 * @property string|null $site_title
 * @property string|null $home_title
 * @property string|null $meta_keyword
 * @property string|null $meta_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Meta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Meta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Meta query()
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereHomeTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereMetaKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereSiteTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Meta extends Model
{
    use HasFactory;

    protected $table = 'metas';

    protected $fillable = [
        'site_title',
        'home_title',
        'meta_keyword',
        'meta_description',
        'google_analytics'
    ];
}
