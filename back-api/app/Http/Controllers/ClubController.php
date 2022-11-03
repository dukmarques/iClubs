<?php

namespace App\Http\Controllers;

use App\Models\Clubs;
use App\Models\Signatures;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClubController extends Controller
{
    /**
     * Display a listing of the clubs.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllClubs()
    {
        return Clubs::all();
    }

    public function getClubsAvailableToSign($id)
    {
        $clubs = Clubs::whereNotIn(
            'id',
            Signatures::where('user_id', '=', $id)->select('club_id')->get()->toArray()
        )->get();

        return response()->json($clubs, 200);
    }

    /**
     * Creating a new club.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|min:3',
            'description' => 'string|max:255'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $name = $request->input('name');
        $description = $request->input('description');

        $club = new Clubs();
        $club->name = $name;
        $club->description = $description;
        $club->save();

        return response()->json([$club], 201);
    }

    /**
     * Display the specified clube.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getClub($id)
    {
        $club = Clubs::find($id);

        if ($club) {
            $club['users'] = $club->users;
            return response()->json([$club], 200);
        }

        return response()->json(['error' => 'Clube não encontrado'], 404);
    }

    /**
     * Update the specified club in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'min:3',
            'description' => 'max:255'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $name = $request->input('name');
        $description = $request->input('description');

        $club = Clubs::find($id);

        if ($club) {
            if ($name) {
                $club->name = $name;
            }

            if ($description) {
                $club->description = $description;
            } else {
                $club->description = '';
            }

            $club->save();
            return response()->json([$club], 200);
        } else {
            return response()->json(['error' => 'Clube não encontrado'], 404);
        }
    }

    /**
     * Remove the specified club from database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $club = Clubs::find($id);

        if ($club) {
            $club->delete();
            return response()->json([$club], 200);
        }

        return response()->json(['error' => 'Clube não encontrado'], 404);
    }
}
