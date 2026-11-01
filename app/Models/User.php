<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as ModelUser;
use Illuminate\Notifications\Notifiable;

// use Illuminate\Foundation\Auth\User as ModelUser;
class User extends ModelUser
{
    use HasFactory;
    use Notifiable;
    
    public function detail(): HasOne
    {
        return $this->hasOne(UserDetail::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
