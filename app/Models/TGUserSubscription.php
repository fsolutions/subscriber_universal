<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TGUserSubscription extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'tg_user_subscribtions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tg_user_id',
        'tg_bot_channel_subscription_id'
    ];

    /**
     * Customize format
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
        'updated_at' => 'datetime:d.m.Y H:i'
    ];

    /**
     * Relationships One to One
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */

    public function info()
    {
        return $this->hasOne(TGBotChannelSubscribtion::class, 'tg_channel_id', 'tg_bot_channel_subscription_id');
    }
}
