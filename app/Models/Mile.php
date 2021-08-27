<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mile extends Model
{
    use HasFactory;

    protected $table = 'pkg_miles';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';
    
    protected $casts = [
        'enable'  => 'boolean',
        'created' => 'datetime:Y-m-d H:i:s'
    ];

    protected $fillable = ['enable', 'venue_id', 'user_id', 'player_id', 'point', 'user_commission', 'comment'];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function user()
    {
        return $this->belongsTo(PkgUser::class);
    }

    public function venue()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('player_id', 'like', '%' . $search . '%')
                    ->orWhereHas('player', function ($q) use ($search) {
                        return $q->where('nickname', 'like', '%' . $search . '%');
                    });
            });
        });
    }
}
