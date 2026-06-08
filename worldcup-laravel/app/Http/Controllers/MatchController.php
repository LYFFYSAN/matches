<?php

namespace App\Http\Controllers;

use App\Models\Match;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    /**
     * Home page — list all matches, scores hidden.
     */
    public function index(Request $request)
    {
        $query = Match::withCount('videos')->orderByDesc('match_date');

        // Filter by status if provided
        if ($request->has('status') && in_array($request->status, ['upcoming', 'live', 'finished'])) {
            $query->where('status', $request->status);
        }

        // Filter by stage
        if ($request->has('stage') && $request->stage !== '') {
            $query->where('stage', $request->stage);
        }

        $matches = $query->get();

        $stages = Match::select('stage')->distinct()->pluck('stage');

        return view('matches.index', compact('matches', 'stages'));
    }

    /**
     * Match detail page — highlights + spoiler shield.
     */
    public function show($id)
    {
        $match = Match::with('videos')->findOrFail($id);

        // Collect distinct languages available for this match
        $languages = $match->videos->pluck('language')->unique()->values();

        return view('matches.show', compact('match', 'languages'));
    }
}
