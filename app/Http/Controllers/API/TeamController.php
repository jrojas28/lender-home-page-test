<?php

namespace App\Http\Controllers\API;

use App\Player;
use App\Team;
use App\Http\Resources\Team as TeamResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data' => TeamResource::collection(Team::orderBy('created_at', 'desc')->get())]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response 
     */
    public function store(Request $request)
    {
        $team = Team::create($request->all());
        if($request->has('players') && count($request['players'])) {
            $ids = [];
            foreach($request['players'] as $player) {
                $ids[] = $player['id'];
            }
            Player::whereIn('id', $ids)->update(['team_id' => $team->id]);
        }
        return response()->json(['data' => $team]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $team = Team::find($id);
        return response()->json(['data' => $team]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $team = Team::find($id);
        $team->update($request->all());
        if($request->has('players')) {
            $ids = [];
            foreach($request['players'] as $player) {
                $ids[] = $player['id'];
            }
            $team->players()->whereNotIn('id', $ids)->update(['team_id' => null]);
            Player::whereIn('id', $ids)->update(['team_id' => $team->id]);
        };
        return response()->json(['data' => $team]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $team = Team::find($id);
        $team->delete();
        return response()->json(['data' => 'success']);
    }

    public function players($id) 
    {
        $team = Team::find($id);
        return response()->json([
            'data' => [
                'team' => $team,
                'players' => $team->players()->orderBy('updated_at', 'desc')->get(),
            ],
        ]);
    }
}
