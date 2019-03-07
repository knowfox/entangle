<?php

namespace Knowfox\Entangle\Models;

use Illuminate\Database\Eloquent\Model;
use Knowfox\Models\Concept;
use Carbon\Carbon;

class EventExtension extends Model
{
    protected $table = 'entangle_events';
    protected $primaryKey = 'concept_id';
    protected $fillable = ['location_id', 'date_from', 'duration', 'duration_unit', 'date_to', 'anniversary'];
    public $timestamps = false;

    public function concept()
    {
        return $this->belongsTo(Concept::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function getDateToAttribute($value)
    {
        if ($value) {
            return $value;
        }
        if (empty($this->duration) || empty($this->duration_unit)) {
            return $this->date_from;
        }

        $date = new Carbon($this->date_from);
        switch ($this->duration_unit) {
            case 'd':
                return $date->addDays($this->duration - 1)->toDateString();
            case 'm':
                $date->addMonths($this->duration);
                return $date->subDay()->toDateString();
            case 'y':
                $date->addYears($this->duration);
                return $date->subDay()->toDateString();
            default:
                return $this->date_from;
        }
    }

    public function getDateToDisplayAttribute($value)
    {
        $date_to = $this->date_to;

        if ($this->duration_unit) {
            $duration = " ({$this->duration}{$this->duration_unit})";
            $date_to .= $duration;
        }
        return $date_to;
    }
}
