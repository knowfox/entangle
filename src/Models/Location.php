<?php

namespace Knowfox\Entangle\Models;

use Illuminate\Database\Eloquent\Model;
use Knowfox\Models\Concept;

class Location extends Concept
{
    protected $table = 'concepts';

    public function event()
    {
        return $this->hasOne(LocationExtension::class, 'concept_id');
    }
}