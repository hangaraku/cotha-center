<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $shop_url
 * @property integer $price
 * @property string $img_url
 * @property integer $stock
 * @property integer $order_number
 * @property boolean $is_pinned
 * @property string $created_at
 * @property string $updated_at
 * @property UserReward[] $userRewards
 */
class Reward extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'shop_url', 'price', 'img_url', 'stock', 'order_number', 'is_pinned', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userRewards(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\UserReward');
    }
}
