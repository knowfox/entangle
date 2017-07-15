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
        if ($this->event->date_to) {
            return $this->event->date_to;
        }
        if (empty($this->event->duration) || empty($this->event->duration_unit)) {
            $this->event->date_from;
        }

        $date = new Carbon($this->event->date_from);
        switch ($this->event->duration_unit) {
            case 'd':
                return $date->addDays($this->event->duration - 1)->toDateString();
            case 'm':
                $date->addMonths($this->event->duration);
                return $date->subDay()->toDateString();
            case 'y':
                $date->addYears($this->event->duration);
                return $date->subDay()->toDateString();
            default:
                return $this->event->date_from;
        }
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
