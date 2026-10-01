<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAudit extends Model
{
    protected $fillable = ['user_id', 'action', 'auditable_type', 'auditable_id', 'changes', 'ip_address'];
    protected function casts(): array { return ['changes' => 'array']; }

    public function user() { return $this->belongsTo(User::class); }

    public static function record(string $action, ?Model $model = null, array $changes = []): self
    {
        return static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'auditable_type' => $model ? $model::class : null,
            'auditable_id' => $model?->getKey(),
            'changes' => $changes,
            'ip_address' => request()->ip(),
        ]);
    }
}
