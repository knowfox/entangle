<?php

namespace Knowfox\Entangle\Models;

use Illuminate\Database\Eloquent\Model;
use Knowfox\User;

class EntangledTimeline extends Model
{
    protected $table = 'entangle_entangled_timelines';

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}