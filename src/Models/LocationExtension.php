<?php

namespace Knowfox\Entangle\Models;

use Illuminate\Database\Eloquent\Model;
use Knowfox\Models\Concept;

class LocationExtension extends Model
{
    protected $table = 'entangle_locations';
    protected $primaryKey = 'concept_id';
    protected $fillable = ['longitude', 'latitude'];
    public $timestamps = false;

    public function concept()
    {
        return $this->belongsTo(Concept::class);
    }
}