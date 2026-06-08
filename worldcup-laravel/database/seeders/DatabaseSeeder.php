<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FootballMatch;
use App\Models\Video;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        FootballMatch::truncate();
        Video::truncate();

        // FLAG CODES (flagcdn.com ISO 3166-1 alpha-2)
        $flags = [
            'Mexico'                 => 'https://flagcdn.com/w80/mx.png',
            'South Africa'           => 'https://flagcdn.com/w80/za.png',
            'South Korea'            => 'https://flagcdn.com/w80/kr.png',
            'Czechia'                => 'https://flagcdn.com/w80/cz.png',
            'Canada'                 => 'https://flagcdn.com/w80/ca.png',
            'Bosnia and Herzegovina' => 'https://flagcdn.com/w80/ba.png',
            'Qatar'                  => 'https://flagcdn.com/w80/qa.png',
            'Switzerland'            => 'https://flagcdn.com/w80/ch.png',
            'Brazil'                 => 'https://flagcdn.com/w80/br.png',
            'Morocco'                => 'https://flagcdn.com/w80/ma.png',
            'Haiti'                  => 'https://flagcdn.com/w80/ht.png',
            'Scotland'               => 'https://flagcdn.com/w80/gb-sct.png',
            'United States'          => 'https://flagcdn.com/w80/us.png',
            'Paraguay'               => 'https://flagcdn.com/w80/py.png',
            'Australia'              => 'https://flagcdn.com/w80/au.png',
            'Turkiye'                => 'https://flagcdn.com/w80/tr.png',
            'Germany'                => 'https://flagcdn.com/w80/de.png',
            'Curacao'                => 'https://flagcdn.com/w80/cw.png',
            'Ivory Coast'            => 'https://flagcdn.com/w80/ci.png',
            'Ecuador'                => 'https://flagcdn.com/w80/ec.png',
            'Netherlands'            => 'https://flagcdn.com/w80/nl.png',
            'Japan'                  => 'https://flagcdn.com/w80/jp.png',
            'Sweden'                 => 'https://flagcdn.com/w80/se.png',
            'Tunisia'                => 'https://flagcdn.com/w80/tn.png',
            'Belgium'                => 'https://flagcdn.com/w80/be.png',
            'Egypt'                  => 'https://flagcdn.com/w80/eg.png',
            'Iran'                   => 'https://flagcdn.com/w80/ir.png',
            'New Zealand'            => 'https://flagcdn.com/w80/nz.png',
            'Spain'                  => 'https://flagcdn.com/w80/es.png',
            'Cape Verde'             => 'https://flagcdn.com/w80/cv.png',
            'Saudi Arabia'           => 'https://flagcdn.com/w80/sa.png',
            'Uruguay'                => 'https://flagcdn.com/w80/uy.png',
            'France'                 => 'https://flagcdn.com/w80/fr.png',
            'Senegal'                => 'https://flagcdn.com/w80/sn.png',
            'Iraq'                   => 'https://flagcdn.com/w80/iq.png',
            'Norway'                 => 'https://flagcdn.com/w80/no.png',
            'Argentina'              => 'https://flagcdn.com/w80/ar.png',
            'Algeria'                => 'https://flagcdn.com/w80/dz.png',
            'Austria'                => 'https://flagcdn.com/w80/at.png',
            'Jordan'                 => 'https://flagcdn.com/w80/jo.png',
            'Portugal'               => 'https://flagcdn.com/w80/pt.png',
            'DR Congo'               => 'https://flagcdn.com/w80/cd.png',
            'Uzbekistan'             => 'https://flagcdn.com/w80/uz.png',
            'Colombia'               => 'https://flagcdn.com/w80/co.png',
            'England'                => 'https://flagcdn.com/w80/gb-eng.png',
            'Croatia'                => 'https://flagcdn.com/w80/hr.png',
            'Ghana'                  => 'https://flagcdn.com/w80/gh.png',
            'Panama'                 => 'https://flagcdn.com/w80/pa.png',
        ];

        // All 48 group-stage fixtures
        // Dates/times in UTC. Status: 'finished', 'live', 'upcoming'
        $matches = [

            // ── GROUP A ──────────────────────────────────────────
            ['team1' => 'Mexico',       'team2' => 'South Africa', 'date' => '2026-06-11 19:00:00', 'group' => 'Group A', 'status' => 'finished', 's1' => 1, 's2' => 0],
            ['team1' => 'South Korea',  'team2' => 'Czechia',      'date' => '2026-06-12 02:00:00', 'group' => 'Group A', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Mexico',       'team2' => 'South Korea',  'date' => '2026-06-18 01:00:00', 'group' => 'Group A', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Czechia',      'team2' => 'South Africa', 'date' => '2026-06-18 17:00:00', 'group' => 'Group A', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Czechia',      'team2' => 'Mexico',       'date' => '2026-06-24 01:00:00', 'group' => 'Group A', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'South Africa', 'team2' => 'South Korea',  'date' => '2026-06-24 01:00:00', 'group' => 'Group A', 'status' => 'upcoming', 's1' => null, 's2' => null],

            // ── GROUP B ──────────────────────────────────────────
            ['team1' => 'Canada',   'team2' => 'Bosnia and Herzegovina', 'date' => '2026-06-12 23:00:00', 'group' => 'Group B', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Qatar',    'team2' => 'Switzerland',            'date' => '2026-06-13 02:00:00', 'group' => 'Group B', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Canada',   'team2' => 'Qatar',                  'date' => '2026-06-17 23:00:00', 'group' => 'Group B', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Switzerland', 'team2' => 'Bosnia and Herzegovina', 'date' => '2026-06-18 20:00:00', 'group' => 'Group B', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Switzerland', 'team2' => 'Canada',              'date' => '2026-06-22 23:00:00', 'group' => 'Group B', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Bosnia and Herzegovina', 'team2' => 'Qatar',   'date' => '2026-06-22 23:00:00', 'group' => 'Group B', 'status' => 'upcoming', 's1' => null, 's2' => null],

            // ── GROUP C ──────────────────────────────────────────
            ['team1' => 'Brazil',   'team2' => 'Morocco',  'date' => '2026-06-13 22:00:00', 'group' => 'Group C', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Haiti',    'team2' => 'Scotland', 'date' => '2026-06-14 01:00:00', 'group' => 'Group C', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Brazil',   'team2' => 'Haiti',    'date' => '2026-06-18 22:00:00', 'group' => 'Group C', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Scotland', 'team2' => 'Morocco',  'date' => '2026-06-19 01:00:00', 'group' => 'Group C', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Morocco',  'team2' => 'Haiti',    'date' => '2026-06-24 20:00:00', 'group' => 'Group C', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Scotland', 'team2' => 'Brazil',   'date' => '2026-06-24 20:00:00', 'group' => 'Group C', 'status' => 'upcoming', 's1' => null, 's2' => null],

            // ── GROUP D ──────────────────────────────────────────
            ['team1' => 'United States', 'team2' => 'Paraguay',  'date' => '2026-06-12 01:00:00', 'group' => 'Group D', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Australia',     'team2' => 'Turkiye',   'date' => '2026-06-12 22:00:00', 'group' => 'Group D', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'United States', 'team2' => 'Australia', 'date' => '2026-06-17 00:00:00', 'group' => 'Group D', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Turkiye',       'team2' => 'Paraguay',  'date' => '2026-06-17 20:00:00', 'group' => 'Group D', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Turkiye',       'team2' => 'United States', 'date' => '2026-06-21 20:00:00', 'group' => 'Group D', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Paraguay',      'team2' => 'Australia', 'date' => '2026-06-21 20:00:00', 'group' => 'Group D', 'status' => 'upcoming', 's1' => null, 's2' => null],

            // ── GROUP E ──────────────────────────────────────────
            ['team1' => 'Germany',      'team2' => 'Curacao',     'date' => '2026-06-14 22:00:00', 'group' => 'Group E', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Ivory Coast',  'team2' => 'Ecuador',     'date' => '2026-06-15 01:00:00', 'group' => 'Group E', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Germany',      'team2' => 'Ivory Coast', 'date' => '2026-06-19 01:00:00', 'group' => 'Group E', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Ecuador',      'team2' => 'Curacao',     'date' => '2026-06-19 22:00:00', 'group' => 'Group E', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Ecuador',      'team2' => 'Germany',     'date' => '2026-06-25 20:00:00', 'group' => 'Group E', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Curacao',      'team2' => 'Ivory Coast', 'date' => '2026-06-25 20:00:00', 'group' => 'Group E', 'status' => 'upcoming', 's1' => null, 's2' => null],

            // ── GROUP F ──────────────────────────────────────────
            ['team1' => 'Netherlands', 'team2' => 'Japan',   'date' => '2026-06-14 22:00:00', 'group' => 'Group F', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Sweden',      'team2' => 'Tunisia', 'date' => '2026-06-15 01:00:00', 'group' => 'Group F', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Netherlands', 'team2' => 'Sweden',  'date' => '2026-06-19 22:00:00', 'group' => 'Group F', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Tunisia',     'team2' => 'Japan',   'date' => '2026-06-20 01:00:00', 'group' => 'Group F', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Tunisia',     'team2' => 'Netherlands', 'date' => '2026-06-25 23:00:00', 'group' => 'Group F', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Japan',       'team2' => 'Sweden',  'date' => '2026-06-25 23:00:00', 'group' => 'Group F', 'status' => 'upcoming', 's1' => null, 's2' => null],

            // ── GROUP G ──────────────────────────────────────────
            ['team1' => 'Belgium',     'team2' => 'Egypt',       'date' => '2026-06-15 22:00:00', 'group' => 'Group G', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Iran',        'team2' => 'New Zealand', 'date' => '2026-06-16 01:00:00', 'group' => 'Group G', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Belgium',     'team2' => 'Iran',        'date' => '2026-06-20 22:00:00', 'group' => 'Group G', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'New Zealand', 'team2' => 'Egypt',       'date' => '2026-06-21 01:00:00', 'group' => 'Group G', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'New Zealand', 'team2' => 'Belgium',     'date' => '2026-06-26 20:00:00', 'group' => 'Group G', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Egypt',       'team2' => 'Iran',        'date' => '2026-06-26 20:00:00', 'group' => 'Group G', 'status' => 'upcoming', 's1' => null, 's2' => null],

            // ── GROUP H ──────────────────────────────────────────
            ['team1' => 'Spain',      'team2' => 'Cape Verde',  'date' => '2026-06-15 17:00:00', 'group' => 'Group H', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Saudi Arabia', 'team2' => 'Uruguay',   'date' => '2026-06-16 01:00:00', 'group' => 'Group H', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Spain',      'team2' => 'Saudi Arabia','date' => '2026-06-21 17:00:00', 'group' => 'Group H', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Uruguay',    'team2' => 'Cape Verde',  'date' => '2026-06-21 23:00:00', 'group' => 'Group H', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Uruguay',    'team2' => 'Spain',       'date' => '2026-06-26 23:00:00', 'group' => 'Group H', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Cape Verde', 'team2' => 'Saudi Arabia','date' => '2026-06-26 23:00:00', 'group' => 'Group H', 'status' => 'upcoming', 's1' => null, 's2' => null],

            // ── GROUP I ──────────────────────────────────────────
            ['team1' => 'France',   'team2' => 'Senegal', 'date' => '2026-06-16 20:00:00', 'group' => 'Group I', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Iraq',     'team2' => 'Norway',  'date' => '2026-06-17 01:00:00', 'group' => 'Group I', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'France',   'team2' => 'Iraq',    'date' => '2026-06-22 01:00:00', 'group' => 'Group I', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Norway',   'team2' => 'Senegal', 'date' => '2026-06-22 20:00:00', 'group' => 'Group I', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Norway',   'team2' => 'France',  'date' => '2026-06-27 00:00:00', 'group' => 'Group I', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Senegal',  'team2' => 'Iraq',    'date' => '2026-06-27 00:00:00', 'group' => 'Group I', 'status' => 'upcoming', 's1' => null, 's2' => null],

            // ── GROUP J ──────────────────────────────────────────
            ['team1' => 'Argentina', 'team2' => 'Algeria', 'date' => '2026-06-16 02:00:00', 'group' => 'Group J', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Austria',   'team2' => 'Jordan',  'date' => '2026-06-17 02:00:00', 'group' => 'Group J', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Argentina', 'team2' => 'Austria', 'date' => '2026-06-22 00:00:00', 'group' => 'Group J', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Jordan',    'team2' => 'Algeria', 'date' => '2026-06-22 22:00:00', 'group' => 'Group J', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Jordan',    'team2' => 'Argentina','date' => '2026-06-27 20:00:00', 'group' => 'Group J', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Algeria',   'team2' => 'Austria', 'date' => '2026-06-27 20:00:00', 'group' => 'Group J', 'status' => 'upcoming', 's1' => null, 's2' => null],

            // ── GROUP K ──────────────────────────────────────────
            ['team1' => 'Portugal',   'team2' => 'DR Congo',   'date' => '2026-06-16 22:00:00', 'group' => 'Group K', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Uzbekistan', 'team2' => 'Colombia',   'date' => '2026-06-17 01:00:00', 'group' => 'Group K', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Portugal',   'team2' => 'Uzbekistan', 'date' => '2026-06-21 22:00:00', 'group' => 'Group K', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Colombia',   'team2' => 'DR Congo',   'date' => '2026-06-22 01:00:00', 'group' => 'Group K', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Colombia',   'team2' => 'Portugal',   'date' => '2026-06-27 23:00:00', 'group' => 'Group K', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'DR Congo',   'team2' => 'Uzbekistan', 'date' => '2026-06-27 23:00:00', 'group' => 'Group K', 'status' => 'upcoming', 's1' => null, 's2' => null],

            // ── GROUP L ──────────────────────────────────────────
            ['team1' => 'England',  'team2' => 'Croatia', 'date' => '2026-06-17 01:00:00', 'group' => 'Group L', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Ghana',    'team2' => 'Panama',  'date' => '2026-06-17 20:00:00', 'group' => 'Group L', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'England',  'team2' => 'Ghana',   'date' => '2026-06-21 23:00:00', 'group' => 'Group L', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Panama',   'team2' => 'Croatia', 'date' => '2026-06-22 01:00:00', 'group' => 'Group L', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Panama',   'team2' => 'England', 'date' => '2026-06-27 01:00:00', 'group' => 'Group L', 'status' => 'upcoming', 's1' => null, 's2' => null],
            ['team1' => 'Croatia',  'team2' => 'Ghana',   'date' => '2026-06-27 01:00:00', 'group' => 'Group L', 'status' => 'upcoming', 's1' => null, 's2' => null],
        ];

        foreach ($matches as $m) {
            FootballMatch::create([
                'team1'      => $m['team1'],
                'team2'      => $m['team2'],
                'team1_flag' => $flags[$m['team1']] ?? null,
                'team2_flag' => $flags[$m['team2']] ?? null,
                'score1'     => $m['s1'],
                'score2'     => $m['s2'],
                'match_date' => $m['date'],
                'stage'      => 'Group Stage',
                'group_name' => $m['group'],
                'status'     => $m['status'],
            ]);
        }
    }
}
