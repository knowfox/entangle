<?php

namespace Knowfox\Entangle\Models;

use Knowfox\Models\Concept;
use Carbon\Carbon;

class Event extends Concept
{
    const WEEKDAYS = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    protected $table = 'concepts';

    public function event()
    {
        return $this->hasOne(EventExtension::class, 'concept_id');
    }

    public function fill(array $attributes)
    {
        parent::fill($attributes);

        if (!empty($attributes['event'])) {
            $event_id = $this->event ? $this->event->concept_id : null;
            $this->event()->updateOrCreate(['concept_id' => $event_id], $attributes['event']);
        }

        return $this;
    }

    public function getDateFromAttribute()
    {
        return $this->event->date_from;
    }

    public function getDateToAttribute()
    {
        return $this->event->date_to;
    }

    public function getWeekdayFromAttribute()
    {
        return self::WEEKDAYS[(new Carbon($this->date_from))->dayOfWeek];
    }

    public function getWeekdayToAttribute()
    {
        return self::WEEKDAYS[(new Carbon($this->date_to))->dayOfWeek];
    }
}
