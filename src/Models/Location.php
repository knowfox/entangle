<?php

namespace Knowfox\Entangle\Models;

use Illuminate\Database\Eloquent\Model;
use Knowfox\Models\Concept;

class Location extends Model
{
    protected $table = 'entangle_locations';

    public function concept()
    {
        return $this->belongsTo(Concept::class);
    }
}