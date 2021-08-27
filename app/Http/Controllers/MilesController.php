<?php

namespace App\Http\Controllers;

use App\Models\Mile;
use App\Models\Player;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class MilesController extends Controller
{
    public function index()
    {
        return Inertia::render('Miles/Index', [
            'filters' => Request::all('search'),
            'miles'   => Mile::with(['player', 'user'])->orderBy('created', 'DESC')
                ->where('venue_id', auth()->id())
                ->filter(Request::only('search'))
                ->paginate(20)
                ->withQueryString()
                ->through(function ($mile) {
                    return [
                        'id'         => $mile->id,
                        'created'    => Carbon::parse($mile->created)->format('Y-m-d H:i:s'),
                        'user'       => $mile->user,
                        'player'     => $mile->player,
                        'point'      => $mile->point,
                        'commission' => $mile->venue_commission,
                        'comment'    => $mile->comment,
                    ];
                }),
        ]);
    }

    public function create()
    {
        return Inertia::render('Miles/Create');
    }

    public function store()
    {
        $player = Player::where('id', request()->get('player_id'))->first();

        if (!$player->user_id) {
            return Redirect::route('miles.create')->with('error', 'No any user has assigned to the selected player!.');
        }

        Mile::create(array_merge(Request::validate([
            'player_id' => ['required', 'integer', 'exists:pkg_players,id'],
            'point'     => ['required', 'integer', 'min:1'],
            'comment'   => ['nullable', 'max:200'],
        ]), ['venue_id' => auth()->id(), 'user_id' => $player->user_id ? $player->user_id : 0]));

        return Redirect::route('miles.create')->with('success', 'Mile created successfully!.');
    }

    public function destroy(Mile $mile)
    {
        $mile->delete();

        return Redirect::back()->with('success', 'Mile deleted.');
    }
}
