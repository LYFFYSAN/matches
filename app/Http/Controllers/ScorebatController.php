<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScorebatController extends Controller
{
    private const SCOREBAT_FEED = 'https://www.scorebat.com/video-api/v3/feed/?token=';

    public function search(int $id)
    {
        $match = FootballMatch::findOrFail($id);
        $results = [];
        $source  = null;

        // 1. Try Scorebat feed
        try {
            $token = env('SCOREBAT_TOKEN', '');
            $response = Http::timeout(10)
                ->withHeaders(['Accept' => 'application/json'])
                ->get(self::SCOREBAT_FEED . $token);

            if ($response->successful()) {
                $feed    = $response->json()['response'] ?? $response->json() ?? [];
                $results = $this->filterFeed($feed, $match->team1, $match->team2);
                $source  = 'scorebat';
            }
        } catch (\Exception $e) {
            Log::warning('Scorebat failed: ' . $e->getMessage());
        }

        // 2. YouTube fallback
        if (empty($results)) {
            $results = $this->searchYoutube($match->team1, $match->team2);
            $source  = 'youtube';
        }

        return response()->json([
            'success' => true,
            'source'  => $source,
            'match'   => ['id' => $match->id, 'team1' => $match->team1, 'team2' => $match->team2],
            'found'   => count($results),
            'videos'  => $results,
        ]);
    }

    public function save(Request $request, int $id)
    {
        $match = FootballMatch::findOrFail($id);

        $data = $request->validate([
            'videos'             => 'required|array|min:1',
            'videos.*.title'     => 'required|string|max:200',
            'videos.*.embed_url' => 'required|string|max:1000',
            'videos.*.language'  => 'required|in:ar,en,fr,es,pt,other',
            'videos.*.platform'  => 'nullable|string',
        ]);

        $saved = 0;
        foreach ($data['videos'] as $v) {
            $exists = Video::where('match_id', $match->id)
                           ->where('embed_url', $v['embed_url'])
                           ->exists();
            if ($exists) continue;

            Video::create([
                'match_id'   => $match->id,
                'title'      => $v['title'],
                'embed_url'  => $v['embed_url'],
                'platform'   => $v['platform'] ?? $this->guessPlatform($v['embed_url']),
                'language'   => $v['language'] === 'other' ? 'en' : $v['language'],
                'duration_s' => null,
            ]);
            $saved++;
        }

        return response()->json([
            'success' => true,
            'saved'   => $saved,
            'message' => $saved > 0
                ? "{$saved} video(s) saved successfully!"
                : "All selected videos were already saved.",
        ]);
    }

    // ── Private helpers ───────────────────────────────────────

    private function filterFeed(array $feed, string $team1, string $team2): array
    {
        $results = [];
        $t1 = strtolower(trim($team1));
        $t2 = strtolower(trim($team2));

        foreach ($feed as $item) {
            $title    = strtolower($item['title'] ?? '');
            $homeSlug = strtolower($item['homeTeam']['slug'] ?? $item['homeTeam']['name'] ?? '');
            $awaySlug = strtolower($item['awayTeam']['slug'] ?? $item['awayTeam']['name'] ?? '');

            $t1slug = str_replace(' ', '-', $t1);
            $t2slug = str_replace(' ', '-', $t2);

            // Match by slug — both orders (home/away may be reversed)
            $slugMatch = (
                (str_contains($homeSlug, $t1) || str_contains($homeSlug, $t1slug)) &&
                (str_contains($awaySlug, $t2) || str_contains($awaySlug, $t2slug))
            ) || (
                (str_contains($homeSlug, $t2) || str_contains($homeSlug, $t2slug)) &&
                (str_contains($awaySlug, $t1) || str_contains($awaySlug, $t1slug))
            );

            // Match by title — both orders
            $titleMatch = (
                str_contains($title, substr($t1, 0, 5)) && str_contains($title, substr($t2, 0, 5))
            );

            if (!$slugMatch && !$titleMatch) continue;

            $sources = $item['videos'] ?? [];

            if (empty($sources)) {
                $embed = $this->extractEmbed($item['embed'] ?? '');
                if ($embed) {
                    $results[] = $this->buildEntry($item['title'] ?? "$team1 vs $team2", $embed, 'scorebat');
                }
                continue;
            }

            foreach ($sources as $src) {
                $embed = $this->extractEmbed($src['embed'] ?? '');
                if (!$embed) continue;
                $results[] = $this->buildEntry(
                    $src['title'] ?? $item['title'] ?? "$team1 vs $team2",
                    $embed,
                    'scorebat'
                );
            }
        }

        return $results;
    }

    private function searchYoutube(string $team1, string $team2): array
    {
        $results = [];

        $queries = [
            "$team1 $team2 highlights",
            "$team2 $team1 highlights",
            "$team1 $team2 goals",
        ];

        foreach ($queries as $q) {
            try {
                $res = Http::timeout(8)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                    ])
                    ->get('https://www.youtube.com/results?search_query=' . urlencode($q));

                if (!$res->successful()) continue;

                preg_match_all('/"videoId":"([a-zA-Z0-9_-]{11})"/', $res->body(), $idMatches);
                $ids = array_unique($idMatches[1] ?? []);

                foreach (array_slice($ids, 0, 3) as $videoId) {
                    $results[] = [
                        'title'     => "$team1 vs $team2 | Highlights",
                        'embed_url' => 'https://www.youtube.com/embed/' . $videoId,
                        'platform'  => 'youtube',
                        'language'  => 'en',
                        'lang_flag' => '🇬🇧',
                    ];
                    if (count($results) >= 3) break 2;
                }

            } catch (\Exception $e) {
                Log::warning('YouTube search failed: ' . $e->getMessage());
            }
        }

        return $results;
    }

    private function extractEmbed(string $raw): ?string
    {
        if (empty($raw)) return null;
        if (filter_var($raw, FILTER_VALIDATE_URL)) return $raw;
        if (preg_match("/src='([^']+)'/", $raw, $m)) return $m[1];
        if (preg_match('/src="([^"]+)"/', $raw, $m)) return $m[1];
        return null;
    }

    private function buildEntry(string $title, string $embedUrl, string $platform): array
    {
        return [
            'title'     => $this->cleanTitle($title),
            'embed_url' => $embedUrl,
            'platform'  => $platform,
            'language'  => $this->guessLanguage($title),
            'lang_flag' => $this->langFlag($this->guessLanguage($title)),
        ];
    }

    private function guessLanguage(string $title): string
    {
        $t = strtolower($title);
        $map = [
            'ar' => ['arabic', 'arabe', 'arab', 'bein arabic', 'ssc', 'mbc'],
            'fr' => ['french', 'francais', 'bein sport fr', 'canal', 'rmc'],
            'es' => ['spanish', 'espanol', 'castellano', 'gol'],
            'pt' => ['portuguese', 'portugues', 'globo', 'sportv'],
        ];
        foreach ($map as $code => $keywords) {
            foreach ($keywords as $kw) {
                if (str_contains($t, $kw)) return $code;
            }
        }
        return 'en';
    }

    private function guessPlatform(string $url): string
    {
        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) return 'youtube';
        if (str_contains($url, 'dailymotion.com')) return 'dailymotion';
        if (str_contains($url, 'scorebat.com')) return 'scorebat';
        if (str_contains($url, 'vimeo.com')) return 'vimeo';
        return 'other';
    }

    private function langFlag(string $lang): string
    {
        return match($lang) {
            'ar' => '🇩🇿', 'fr' => '🇫🇷', 'es' => '🇪🇸', 'pt' => '🇧🇷', default => '🇬🇧',
        };
    }

    private function cleanTitle(string $title): string
    {
        $title = preg_replace('/\s*[-–|]\s*Scorebat.*$/i', '', $title);
        $title = preg_replace('/Scorebat\s*/i', '', $title);
        return trim(html_entity_decode($title));
    }
}
