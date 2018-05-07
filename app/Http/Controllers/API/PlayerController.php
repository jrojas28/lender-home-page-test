<?php

namespace App\Http\Controllers\API;

use App\Player;
use App\Http\Resources\Player as PlayerResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['data' => PlayerResource::collection(Player::orderBy('created_at', 'desc')->get())]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $player = Player::create($request->all());
        if($request->has('team')) {
            $player->team()->associate($request['team']['id']);
            $player->save();
        }
        return response()->json($player);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $player = Player::find($id);
        return response()->json(['data' => $player]);
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
        $player = Player::find($id);
        $player->update($request->all());
        if($request->has('team')) {
            $player->team()->associate($request['team']['id']);
        }
        else {
            $player->update(['team_id' => null]);
        }
        $player->save();
        return response()->json(['data' => $player]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $player = Player::find($id);
        $player->delete();
        return response()->json(['data' => 'success']);
    }

    public function freeAgents() 
    {
        return response()->json(['data' => Player::whereNull('team_id')->orderBy('created_at', 'desc')->get()]);
    }
}
