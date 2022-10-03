<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class VcardBlog
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $vcard_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $blog_icon
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Vcard $vcard
 * @method static \Illuminate\Database\Eloquent\Builder|VcardBlog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VcardBlog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VcardBlog query()
 * @method static \Illuminate\Database\Eloquent\Builder|VcardBlog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VcardBlog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VcardBlog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VcardBlog whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VcardBlog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VcardBlog whereVcardId($value)
 * @mixin \Eloquent
 */

class VcardBlog extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const BLOG_PATH = 'vcards/blogs';
    /**
     * Validation rules
     * @var array
     */
    public static $rules = [
        'title'       => 'required|string|min:2|max:50',
        'description' => 'string|required',
        'blog_icon'   => 'required|mimes:jpg,jpeg,png',
    ];
    protected $table = 'vcard_blog';
    protected $appends = ['blog_icon'];
    protected $with = ['media'];
    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'vcard_id',
    ];

    /**
     * @return mixed
     */
    public function getBlogIconAttribute()
    {
        /** @var Media $media */
        $media = $this->getMedia(self::BLOG_PATH)->first();
        if (!empty($media)) {
            return $media->getFullUrl();
        }

        return asset('assets/images/default_service.png');
    }

    /**
     *
     * @return BelongsTo
     */
    public function vcard()
    {
        return $this->belongsTo(Vcard::class, 'vcard_id');
    }
}
