<?php

namespace Knowfox\Entangle\Models;

use Illuminate\Database\Eloquent\Model;

class ImportedLocation extends Model
{
    protected $connection = 'sqlite';
    protected $table = 'location';
}
