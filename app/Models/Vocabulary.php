<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $unit_id
 * @property string $word
 * @property string $meaning
 * @property string $created_at
 * @property string $updated_at
 * @property Unit $unit
 */
class Vocabulary extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['unit_id', 'word', 'meaning', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Unit');
    }
}
