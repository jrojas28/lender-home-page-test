<?php

use Illuminate\Database\Seeder;
use App\Team;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teams = [
            'New York Yankees',
            'Boston Red Sox',
            'Chicago Cubs',
            'Los Angeles Dodgers',
            'San Francisco Giants',
        ];

        foreach($teams as $team) {
            $team = Team::firstOrCreate(['name' => $team]);
        }
    }
}
