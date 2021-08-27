<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $table = 'pkg_players';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    protected $casts = [
        'enable' => 'boolean',
    ];

    protected $fillable = ['pg_name', 'nickname', 'enable', 'mail', 'user_id', 'venue_id', 'pokerstars', 'tel', 'birthday', 'chip_balance', 'point_balance', 'comment'];


    public function miles()
    {
        return $this->hasMany(Mile::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('nickname', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%')
                    ->orWhere('mail', 'like', '%' . $search . '%');
            });
        });
    }
}
