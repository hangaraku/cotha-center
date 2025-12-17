<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $unit_id
 * @property string $name
 * @property string $content
 * @property string $media_url
 * @property string $created_at
 * @property string $updated_at
 * @property Unit $unit
 */
class Topic extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['unit_id', 'name', 'content', 'media_url', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Unit');
    }
}
