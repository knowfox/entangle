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

    public function fill(array $attributes)
    {
        parent::fill($attributes);

        if (!empty($attributes['event'])) {
            $event_id = $this->event->concept_id;
            $this->event()->updateOrCreate(['concept_id' => $event_id], $attributes['event']);
        }

        return $this;
    }
}
