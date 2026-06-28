<?php

namespace Modules\Chat\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LiveChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'provider_id',
        'admin_id',
    ];

    protected $casts = [
        'client_id' => 'integer',
        'provider_id' => 'integer',
        'admin_id' => 'integer',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, "client_id","id");
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class,"provider_id","id");
    }

    public function livechatMessage(): HasMany
    {
        return $this->hasMany(LiveChatMessage::class,"live_chat_id","id");
    }

    public function provider_unseen_msg(): HasMany
    {
        return $this->hasMany(LiveChatMessage::class,"live_chat_id","id")
            ->where("live_chat_messages.from_user", 1)
            ->where("live_chat_messages.is_seen", 0);
    }

    public function client_unseen_msg(): HasMany
    {
        return $this->hasMany(LiveChatMessage::class,"live_chat_id","id")
            ->where("live_chat_messages.from_user", 2)->where("live_chat_messages.is_seen", 0);
    }

}
