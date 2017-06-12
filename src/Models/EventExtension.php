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
        else {
            if ($this->duration_unit) {
                $duration = " ({$this->duration}{$this->duration_unit})";
                switch ($this->duration_unit) {
                    case 'd':
                        if ($this->duration == 1) {
                            $until = '';
                        }
                        else {
                            $until = (new Carbon($this->date_from))
                                    ->addDays($this->duration)->toDateString()
                                . $duration;
                        }
                        break;
                    case 'm':
                        $until = (new Carbon($this->date_from))
                                ->addMonths($this->duration)->toDateString()
                            . $duration;
                        break;
                    case 'y':
                        $until = (new Carbon($this->date_from))
                                ->addYears($this->duration)->toDateString()
                            . $duration;
                        break;
                }
                return $until;
            }
            return '';
        }
    }
}
