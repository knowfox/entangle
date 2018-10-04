<?php

namespace Knowfox\Entangle\Models;

use Illuminate\Database\Eloquent\Model;

class ImportedUser extends Model
{
    protected $connection = 'sqlite';
    protected $table = 'user';

    public function timelines()
    {
        return $this->hasMany(ImportedTimeline::class, 'user_id');
    }
}
