<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chip extends Model
{
    use HasFactory;

    protected $table = 'pkg_chips';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $dates = ['created', 'modified'];

    protected $casts = [
        'created' => 'datetime:Y-m-d H:i:s'
    ];

    protected $fillable = ['player_id', 'admin_id', 'in_out', 'balance', 'comment'];

    public function player()
    {
        return $this->belongsTo(Player::class);
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
