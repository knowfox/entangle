<?php

namespace Knowfox\Entangle\Controllers;

use Illuminate\Support\Facades\Auth;
use Knowfox\Entangle\Models\Entangle;
use Knowfox\Entangle\Models\EntangledTimeline;
use Knowfox\Http\Controllers\Controller;
use Knowfox\Models\Concept;

class EntangledController extends Controller
{
    public function timelines()
    {
        /*

        $entangled = Entangle::leftJoin('concepts', 'concepts.id', '=', 'entangles.concept_id')
            ->where('concepts.owner_id', Auth::id())
            ->first();
*/
        $entangle = Entangle::where('owner_id', Auth::id())
            ->firstOrFail();

        $event_timelines = array();

        $timelines = EntangledTimeline::leftJoin(
                'timeline',
                'entangle_entangled_timeline.timeline_id', '=', 'entangle_timeline.id'
            )
            ->where('entangle_entangled_timeline.entangle_id', $entangle->id)
            ->get();

        foreach ($timelines as $timeline) {

            if (empty($timeline->timelines)) {
                $timeline->timelines = [];
            }
            else {
                $timeline->timelines = explode(',', $timeline->timelines);
            }
            $timelines[$timeline->id] = $timeline;

            if (0 == count($timeline->timelines)) {
                $event_timelines[] = $timeline->id;
            }
            else {
                $event_timelines = array_merge($event_timelines, $timeline->timelines);
            }
        }

        $events = Event::select('entangle_event.id', 'event_id')
            ->select('entangle_event.*')
            ->select('entangle_location.title', 'location_title')
            ->select('user.id')
            ->select('user.name', 'user_realname')
            ->leftJoin('concepts', 'entangle_event.concept_id', '=', 'concepts.id')
            ->leftJoin('entangle_location', 'entangle_event.location_id', '=', 'entangle_location.id')
            ->leftJoin('entangle_timeline', 'entangle_event.timeline_id', '=', 'entangle_timeline.id')
            ->leftJoin('user', 'entangle_timeline.user_id', '=', 'user.id')
            ->whereIn('timeline_id', $event_timelines)
            ->where('concepts.id', Auth::id())
            ->orderBy('date_from', 'desc')
            ->orderBy('timeline_id', 'asc');

        $vector = new TimeVector($events->get(), /*future*/true);
        $points = $vector->points();

        $named_timelines = Timeline::select_many('timeline.id', 'timeline.user_id', 'timeline.title', 'timeline.timelines')
            ->select('user.realname', 'user_realname')
            ->left_outer_join('user', array('timeline.user_id', '=', 'user.id'))
            ->order_by_asc('user_realname', 'title')
            ->fget();

        return view('timelines', array(
            'page_title' => $entangled->title,
            'timelines' => $timelines,
            'named_timelines' => $named_timelines,
            'points' => $points->points,
            'point_count' => $points->count,
        ));

    }
}