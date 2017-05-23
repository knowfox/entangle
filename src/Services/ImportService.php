<?php

namespace Knowfox\Entangle\Services;

use Knowfox\Entangle\Models\Event;
use Knowfox\Models\Concept;
use Knowfox\User;

class ImportService
{
    public function saveUser($data)
    {
        $user = User::firstOrCreate(['email' => $data['email']], ['name' => $data['name']]);

        $root = Concept::whereIsRoot()->firstOrCreate([
            'title' => 'Timelines',
            'owner_id' => $user->id,
        ]);

        foreach ($data['timelines'] as $timeline_data) {
            Concept::firstOrCreate([
                'parent_id' => $root->id,
                'type' => 'timeline',
                'owner_id' => $user->id,
                'slug' =>  $timeline_data['name'],
                'title' =>  $timeline_data['title'],
            ], [
                'config' => ['timelines' => !empty($timeline_data['timelines']) ? $timeline_data['timelines'] : ''],
            ]);
        }

        return $user;
    }

    public function saveEvent($data)
    {
        $root = Concept::whereIsRoot()
            ->where('title', 'Timelines')
            ->where('owner_id', $data['owner_id'])
            ->firstOrFail();

        $timeline = Concept::where('parent_id', $root->id)
            ->where('slug', $data['timeline_slug'])
            ->where('owner_id', $data['owner_id'])
            ->firstOrFail();

        $event = Event::firstOrCreate([
            'parent_id' => $timeline->id,
            'owner_id' => $data['owner_id'],
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