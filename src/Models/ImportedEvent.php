<?php

namespace Knowfox\Entangle\Models;

use Illuminate\Database\Eloquent\Model;

class ImportedEvent extends Model
{
    protected $connection = 'sqlite';
    protected $table = 'event';

    public function timeline()
    {
        return $this->belongsTo(ImportedTimeline::class, 'timeline_id');
    }
}
