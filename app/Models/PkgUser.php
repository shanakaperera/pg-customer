<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PkgUser extends Model
{
    use HasFactory;

    protected $table = 'pkg_users';

    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('username', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%')
                    ->orWhere('mail', 'like', '%' . $search . '%');
            });
        });
    }
}
