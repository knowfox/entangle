<?php

namespace Knowfox\Entangle\Models;

use Knowfox\Models\Concept;

class Timeline extends Concept
{
    protected $table = 'concepts';

    public function children()
    {
        return $this->hasMany(Event::class, 'parent_id')
            ->leftJoin('entangle_events', 'entangle_events.concept_id', '=', 'concepts.id')
            ->selectRaw('DISTINCT(concepts.id), concepts.*, entangle_events.*')
            ->orderBy('entangle_events.date_from', 'desc');
    }
}
