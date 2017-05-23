<?php

namespace Knowfox\Entangle\Models;

use Illuminate\Database\Eloquent\Model;
use Knowfox\Models\Concept;

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
}
