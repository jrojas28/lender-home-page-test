<?php

use Illuminate\Database\Seeder;
use App\Player;
use App\Team;
class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $players = [
            [
                "first_name" => "Alex",
                "last_name" => "Rodriguez",
                "team" => "New York Yankees",
            ],
            [
                "first_name" => "Derek",
                "last_name" => "Jeter",
                "team" => "New York Yankees",
            ],
            [
                "first_name" => "David",
                "last_name" => "Ortiz",
                "team" => "Boston Red Sox",
            ],
            [
                "first_name" => "Sammy",
                "last_name" => "Sosa",
                "team" => "Chicago Cubs",
            ],
            //Free Agents
            [
                "first_name" => "Pedro",
                "last_name" => "Martinez",
            ],
            [
                "first_name" => "Babe",
                "last_name" => "Ruth",
            ],
            [
                "first_name" => "Cy",
                "last_name" => "Young",
            ],
            [
                "first_name" => "Hank",
                "last_name" => "Aaron",
            ],
            [
                "first_name" => "Barry",
                "last_name" => "Bonds",
            ], 
        ];

        foreach($players as $player) {
            $newPlayer = Player::firstOrCreate([
                'first_name' => $player['first_name'],
                'last_name' => $player['last_name'],
            ]);
            if(isset($player['team'])) {
                $team = Team::firstOrCreate(['name' => $player['team']]);
                $newPlayer->team()->associate($team);
                $newPlayer->save();
            }
        }
    }
}
