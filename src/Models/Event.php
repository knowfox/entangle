<?php

namespace Knowfox\Entangle\Models;

use Illuminate\Database\Eloquent\Model;
use Knowfox\Models\Concept;

class Event extends Model
{
    protected $table = 'entangle_events';

    public function concept()
    {
        return $this->belongsTo(Concept::class);
    }

    public function timeline()
    {
        return $this->belongsTo(Timeline::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
