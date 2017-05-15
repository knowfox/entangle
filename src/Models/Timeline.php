<?php

namespace Knowfox\Entangle\Models;

use Illuminate\Database\Eloquent\Model;
use Knowfox\User;

class Timeline extends Model
{
    protected $table = 'entangle_timelines';

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}