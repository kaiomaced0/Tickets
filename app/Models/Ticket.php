<?php

namespace App\Models;

use App\Models\TicketStatusLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'solicitante_id',
        'responsavel_id',
        'titulo',
        'descricao',
        'status',
        'prioridade',
        'resolved_at',
        'active',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'active' => 'boolean',
    ];

    protected static function booted(): void
    {
        // Default all queries to active tickets
        static::addGlobalScope('active', fn (Builder $query) => $query->where('active', true));
    }

    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(TicketStatusLog::class);
    }

    public function scopeWithInactive(Builder $query): Builder
    {
        return $query->withoutGlobalScope('active');
    }

    public function delete(): bool
    {
        if (! $this->exists) {
            return false;
        }

        $this->active = false;

        return $this->saveQuietly();
    }
}
