<?php

namespace App\Http\Controllers;

use App\Models\Match;
use App\Models\Video;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Admin dashboard — list matches.
     */
    public function index()
    {
        $matches = Match::withCount('videos')->orderByDesc('match_date')->get();
        return view('admin.index', compact('matches'));
    }

    /**
     * Form to create a new match.
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Store a new match.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'team1'      => 'required|string|max:100',
            'team2'      => 'required|string|max:100',
            'team1_flag' => 'nullable|url|max:500',
            'team2_flag' => 'nullable|url|max:500',
            'score1'     => 'nullable|integer|min:0',
            'score2'     => 'nullable|integer|min:0',
            'match_date' => 'required|date',
            'stage'      => 'required|string|max:100',
            'group_name' => 'nullable|string|max:50',
            'status'     => 'required|in:upcoming,live,finished',
        ]);

        $match = Match::create($data);

        return redirect()->route('admin.matches.show', $match->id)
                         ->with('success', 'Match created successfully!');
    }

    /**
     * Show a single match in admin with its videos.
     */
    public function showMatch($id)
    {
        $match = Match::with('videos')->findOrFail($id);
        return view('admin.show', compact('match'));
    }

    /**
     * Form to edit an existing match.
     */
    public function edit($id)
    {
        $match = Match::findOrFail($id);
        return view('admin.edit', compact('match'));
    }

    /**
     * Update a match.
     */
    public function update(Request $request, $id)
    {
        $match = Match::findOrFail($id);

        $data = $request->validate([
            'team1'      => 'required|string|max:100',
            'team2'      => 'required|string|max:100',
            'team1_flag' => 'nullable|url|max:500',
            'team2_flag' => 'nullable|url|max:500',
            'score1'     => 'nullable|integer|min:0',
            'score2'     => 'nullable|integer|min:0',
            'match_date' => 'required|date',
            'stage'      => 'required|string|max:100',
            'group_name' => 'nullable|string|max:50',
            'status'     => 'required|in:upcoming,live,finished',
        ]);

        $match->update($data);

        return redirect()->route('admin.matches.show', $match->id)
                         ->with('success', 'Match updated!');
    }

    /**
     * Delete a match (cascades to videos).
     */
    public function destroy($id)
    {
        Match::findOrFail($id)->delete();
        return redirect()->route('admin.index')->with('success', 'Match deleted.');
    }

    /**
     * Store a new video for a match.
     */
    public function storeVideo(Request $request, $matchId)
    {
        $match = Match::findOrFail($matchId);

        $data = $request->validate([
            'title'      => 'required|string|max:200',
            'embed_url'  => 'required|url|max:500',
            'platform'   => 'required|in:youtube,dailymotion,twitter,other',
            'language'   => 'required|in:ar,en,fr,es,pt',
            'duration_s' => 'nullable|integer|min:0',
        ]);

        $data['match_id'] = $match->id;
        Video::create($data);

        return redirect()->route('admin.matches.show', $match->id)
                         ->with('success', 'Video added!');
    }

    /**
     * Delete a video.
     */
    public function destroyVideo($matchId, $videoId)
    {
        Video::where('match_id', $matchId)->findOrFail($videoId)->delete();
        return redirect()->route('admin.matches.show', $matchId)
                         ->with('success', 'Video removed.');
    }
}
