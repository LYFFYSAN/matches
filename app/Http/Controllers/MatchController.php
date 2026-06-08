<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index(Request $request)
    {
        $query = FootballMatch::withCount('videos')->orderBy('group_name')->orderBy('match_date');

        if ($request->has('status') && in_array($request->status, ['upcoming', 'live', 'finished'])) {
            $query->where('status', $request->status);
        }

        if ($request->has('stage') && $request->stage !== '') {
            $query->where('stage', $request->stage);
        }

        $matches = $query->get()->groupBy('group_name');

        $stages = FootballMatch::select('stage')->distinct()->pluck('stage');

        return view('matches.index', compact('matches', 'stages'));
    }

    public function show($id)
    {
        $match = FootballMatch::with('videos')->findOrFail($id);
        $languages = $match->videos->pluck('language')->unique()->values();
        return view('matches.show', compact('match', 'languages'));
    }
}
