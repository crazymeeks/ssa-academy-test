<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    use HasFactory;

    const ACTIVE = 1;
    const TYPE_DETAIL = 'detail';
    const TYPE_BIO = 'bio';

    const FN = 'Full name';
    const MI = 'Middle Initial';
    const AV = 'Avatar';
    const GR = 'Gender';

    protected $fillable = [
        'key',
        'value',
        'icon',
        'status',
        'type',
        'user_id'
    ];


    /**
     * Get user of this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
