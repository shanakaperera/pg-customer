<?php

namespace App\Http\Controllers;

use App\Models\Mile;
use App\Models\Player;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PlayersController extends Controller
{
    public function index()
    {
        return Inertia::render('Players/Index', [
            'filters' => Request::all('search'),
            'players' => Player::orderBy('id', 'DESC')
                ->where('venue_id', auth()->id())
                ->filter(Request::only('search'))
                ->paginate(20)
                ->withQueryString()
                ->through(function ($player) {
                    return [
                        'id'       => $player->id,
                        'nickname' => $player->nickname,
                        'email'    => $player->mail,
                        'comment'  => $player->comment,
                    ];
                }),
        ]);
    }

    public function players()
    {
        return response()->json([
            'players' => Player::orderBy('id')
                ->where('venue_id', auth()->id())
                ->filter(Request::only('search'))
                ->take(20)
                ->get()
                ->map(function ($player) {
                    return [
                        'id'   => $player->id,
                        'name' => request()->get('type') == 'nickname' ? $player->nickname : $player->mail,
                    ];
                })->toArray()
        ]);

    }

    public function show()
    {
        $query = Player::query()->where('venue_id', auth()->id());

        $player_id = request()->get('player_id');
        $player_mail = request()->get('player_mail');
        $player_nickname = request()->get('player_nickname');

        if ($player_id == null && $player_mail == null && $player_nickname == null) {
            return response()->json([
                'player'       => null,
                'player_miles' => null,
            ]);
        }

        $query->when($player_id != null, function ($q) use ($player_id) {
            return $q->where('id', $player_id);
        })->when($player_mail != null, function ($q) use ($player_mail) {
            return $q->where('mail', 'like', '%' . $player_mail . '%%');
        })->when($player_nickname != null, function ($q) use ($player_nickname) {
            return $q->where('nickname', 'like', '%' . $player_nickname . '%%');
        });

        $player = $query->first();

        $mileQuery = Mile::orderBy('created', 'DESC')
            ->where('venue_id', auth()->id())
            ->where('player_id', $player->id);

        if ($player) {
            $player->setAttribute('sum_points', $mileQuery->sum('point'));
        }

        return response()->json([
            'player'       => $player,
            'player_miles' => $player != null ?
                $mileQuery->paginate(20)
                    ->withQueryString()
                    ->through(function ($mile) {
                        return [
                            'id'      => $mile->id,
                            'created' => Carbon::parse($mile->created)->format('Y-m-d H:i:s'),
                            'point'   => $mile->point,
                            'comment' => $mile->comment,
                        ];
                    }) : null
        ]);
    }

    public function create()
    {
        return Inertia::render('Players/Create');
    }

    public function store()
    {
        $email = request()->get('mail');

        Player::create(array_merge(Request::validate([
            'mail'          => [
                'required', 'max:50', 'email',
                Rule::unique('pkg_players')->where(function ($query) use ($email) {
                    return $query->where('mail', $email)->where('venue_id', auth()->id());
                })
            ],
            'nickname'      => ['required', 'max:50'],
            'user_id'       => ['nullable', 'integer', 'exists:pkg_users,id'],
//            'chip_balance'  => ['nullable', 'integer'],
//            'point_balance' => ['nullable', 'integer'],
            'comment'       => ['nullable', 'max:200']
        ]), ['venue_id' => auth()->id()]));

        return Redirect::route('players')->with('success', 'Player created successfully!.');
    }

    public function edit(Player $player)
    {
        return Inertia::render('Players/Edit', [
            'player' => [
                'id'            => $player->id,
                'mail'          => $player->mail,
                'nickname'      => $player->nickname,
                'user_id'       => $player->user_id,
                'chip_balance'  => $player->chip_balance . '',
                'point_balance' => $player->point_balance . '',
                'comment'       => $player->comment,
            ],
        ]);
    }

    public function update(Player $player)
    {
        $player->update(
            Request::validate([
                'mail'          => [
                    'required', 'max:50', 'email',
                    Rule::unique('pkg_players')->where(function ($query) use ($player) {
                        return $query->where('mail', $player->email)->where('venue_id', auth()->id());
                    })->ignore($player->id)
                ],
                'nickname'      => ['required', 'max:50'],
                'user_id'       => ['nullable', 'integer', 'exists:pkg_users,id'],
                'chip_balance'  => ['nullable', 'integer'],
                'point_balance' => ['nullable', 'integer'],
                'comment'       => ['nullable', 'max:200']
            ])
        );

        return Redirect::back()->with('success', 'Player updated successfully.');
    }

    public function destroy(Player $player)
    {
        $player->delete();

        return Redirect::back()->with('success', 'Player deleted.');
    }
}
