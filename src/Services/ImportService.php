<?php

namespace Knowfox\Entangle\Services;

use Illuminate\Support\Facades\Auth;
use Knowfox\Entangle\Models\Event;
use Knowfox\Models\Concept;
use Knowfox\User;

class ImportService
{
    public function savePerson($data)
    {
        $people_root = Concept::whereIsRoot()->firstOrCreate([
            'title' => 'People',
            'owner_id' => Auth::id(),
        ]);

        $name_parts = preg_split('/\s+/', $data['name'], 2);
        if (count($name_parts) > 1) {
            $person_name = $name_parts[1] . ', ' . $name_parts[0];
        }
        else {
            $person_name = $name_parts[0];
        }
        $person = Concept::firstOrCreate([
            'title' => $person_name,
            'owner_id' => Auth::id(),
            'parent_id' => $people_root->id,
        ], [
            'type' => 'person',
            'config' => [
                'email' => $data['email']
            ],
        ]);

        $root = Concept::whereIsRoot()->firstOrCreate([
            'title' => 'Timelines',
            'owner_id' => Auth::id(),
        ]);

        foreach ($data['timelines'] as $timeline_data) {
            $title = $person_name . ': ' . $timeline_data['title'];
            $timeline = Concept::firstOrCreate([
                'owner_id' => Auth::id(),
                'parent_id' => $root->id,
                'type' => 'entangle:timeline',
                'slug' =>  str_slug($title),
                'title' =>  $title,
            ], [
                'config' => ['timelines' => !empty($timeline_data['timelines']) ? $timeline_data['timelines'] : ''],
            ]);

            $person->related()->attach($timeline, ['type' => 'timeline']);
        }

        return $person;
    }

    public function saveEvent($data)
    {
        $person = Concept::find($data['person_id']);

        $root = Concept::whereIsRoot()
            ->where('title', 'Timelines')
            ->where('owner_id', Auth::id())
            ->firstOrFail();

        $title = $person->title . ': ' . $data['timeline_title'];
        $timeline = Concept::where('parent_id', $root->id)
            ->where('title', $title)
            ->where('owner_id', Auth::id())
            ->firstOrFail();

        $event = Event::firstOrCreate([
            'parent_id' => $timeline->id,
            'owner_id' => Auth::id(),
            'title' => $data['title'],
        ], [
            'type' => 'entangle:event',
            'public' => $data['public'],
            'body' => !empty($data['description']) ? $data['description'] : null,
            'created_at' => !empty($data['created']) ? $data['created'] : null,

            'config' => [
                'source_id' => !empty($data['source_id']) ? $data['source_id'] : null,
                'replicated' => !empty($data['replicated']) ? $data['replicated'] : null,
            ],
        ]);

        foreach (['date_from', 'date_to'] as $date_field) {
            $date = null;
            if (!empty($data[$date_field]) && preg_match('/^(\d{4})(-\d{2})?(-\d{2})?/', $data[$date_field], $matches)) {
                if (count($matches) > 3) {
                    $date = $matches[1] . $matches[2] . $matches[3];
                }
                else
                if (count($matches) > 2) {
                    $date = $matches[1] . $matches[2] . '-01';
                }
                else {
                    $date = $matches[1] . '-01-01';
                }
            }
            $data[$date_field] = $date;
        }

        $event->event()->create([
            'location_id' => !empty($data['location_id']) ? $data['location_id'] : null,
            'date_from' => $data['date_from'],
            'duration' => !empty($data['duration']) ? $data['duration'] : null,
            'duration_unit' => !empty($data['duration_unit']) ? $data['duration_unit'] : null,
            'date_to' => !empty($data['date_to']) ? $data['date_to'] : null,
            'anniversary' => !empty($data['anniversary']) ? $data['anniversary'] : null,
        ]);

        return $event;
    }
}