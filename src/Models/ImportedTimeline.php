<?php

namespace Knowfox\Entangle\Models;

use Illuminate\Database\Eloquent\Model;

class ImportedTimeline extends Model
{
    protected $connection = 'sqlite';
    protected $table = 'timeline';
}
