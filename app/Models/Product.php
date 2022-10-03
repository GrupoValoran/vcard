<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property int|null $currency_id
 * @property string|null $price
 * @property string $description
 * @property int $vcard_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Currency|null $currency
 * @property-read string $product_icon
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Vcard $vcard
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereVcardId($value)
 * @mixin \Eloquent
 */
class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'products';

    protected $appends = ['product_icon'];
    protected $with = ['media'];

    protected $fillable = [
        'name',
        'currency_id',
        'price',
        'description',
        'vcard_id',
    ];

    public static $rules = [
        'name'         => 'required|string|min:2',
        'price'        => 'nullable|numeric|min:0',
        'description'  => 'string|min:2|max:200',
        'product_icon' => 'required|mimes:jpg,jpeg,png',
    ];

    const PRODUCT_PATH = 'vcards/products';

    /**
     *
     *
     * @return string
     */
    public function getProductIconAttribute()
    {
        /** @var Media $media */
        $media = $this->getMedia(self::PRODUCT_PATH)->first();
        if (!empty($media)) {
            return $media->getFullUrl();
        }
        return asset('assets/images/default_service.png');
    }

    /**
     *
     *
     * @return BelongsTo
     */
    public function vcard()
    {
        return $this->belongsTo(Vcard::class, 'vcard_id');
    }

    /**
     *
     *
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
