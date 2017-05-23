<?php

namespace Knowfox\Entangle\Models;

use Knowfox\Models\Concept;

class Event extends Concept
{
    protected $table = 'concepts';

    public function event()
    {
        return $this->hasOne(EventExtension::class, 'concept_id');
    }
}
