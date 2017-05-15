<?php

namespace Knowfox\Entangle\Models;

use Illuminate\Database\Eloquent\Model;
use Knowfox\User;

class Entangle extends Model
{
    protected $table = 'entangle_entangles';

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}