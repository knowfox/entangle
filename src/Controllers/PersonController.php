<?php

namespace Knowfox\Entangle\Controllers;

use Knowfox\Http\Controllers\Controller;
use Knowfox\Models\Concept;

class PersonController extends Controller
{
    public function show($slug)
    {
        $person = Concept::where('type', 'entangle:person')
            ->where('slug', $slug)
            ->first();

        if (!$person) {
            return back()
                ->with('error', 'No person with slug <em>' . $slug . '</em>');
        }

        return response()->redirectToRoute('concept.show', [$person]);
    }
}