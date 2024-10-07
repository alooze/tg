<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TgUser extends Model
{
    use HasFactory;

    protected $table = 'tg_users';

    protected $guarded = [];
}
