<?php

namespace App\Http\Controllers;

use App\Models\Chip;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class ChipsController extends Controller
{
    public function index()
    {
        return Inertia::render('Chips/Index', [
            'filters' => Request::all('search'),
            'chips'   => Chip::with(['player'])->orderBy('created', 'DESC')
                ->filter(Request::only('search'))
                ->paginate(20)
                ->withQueryString()
                ->through(function ($chip) {
                    return [
                        'id'      => $chip->id,
                        'created' => Carbon::parse($chip->created)->format('Y-m-d H:i:s'),
                        'player'  => $chip->player,
                        'in_out'  => $chip->in_out . '',
                        'balance' => $chip->balance . '',
                        'comment' => $chip->comment,
                    ];
                }),
        ]);
    }

    public function create()
    {
        return Inertia::render('Chips/Create');
    }

    public function store()
    {
        Chip::create(array_merge(Request::validate([
            'player_id' => ['required', 'integer', 'exists:pkg_players,id'],
            'in_out'    => ['required', 'integer'],
            'balance'   => ['required', 'integer'],
            'comment'   => ['nullable', 'max:100'],
        ]), ['admin_id' => 0]));

        return Redirect::route('chips')->with('success', 'Chip created successfully!.');
    }

    public function edit(Chip $chip)
    {
        $player = null;
        if ($chip->player) {
            $player = ['id' => $chip->player->id, 'name' => $chip->player->nickname];
        }
        return Inertia::render('Chips/Edit', [
            'chip' => [
                'id'      => $chip->id,
                'player'  => $player,
                'in_out'  => $chip->in_out,
                'balance' => $chip->balance,
                'comment' => $chip->comment,
            ],
        ]);
    }

    public function update(Chip $chip)
    {
        $chip->update(Request::validate([
            'player_id' => ['required', 'integer', 'exists:pkg_players,id'],
            'in_out'    => ['required', 'integer'],
            'balance'   => ['required', 'integer'],
        ]));

        return Redirect::back()->with('success', 'Chip updated successfully!.');
    }

    public function destroy(Chip $chip)
    {
        $chip->delete();

        return Redirect::back()->with('success', 'Chip deleted.');
    }
}
