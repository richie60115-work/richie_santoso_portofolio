<?php
// FILE: app/Models/User.php
// GANTI file User.php yang lama dengan ini

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
    'name', 'email', 'phone', 'address', 'password', 'role',
    'is_active', 'is_suspended', 'suspend_reason', 'suspended_at', 'suspended_by',
    'is_flagged', 'flag_count',
];
    protected $hidden = ['password', 'remember_token'];

   protected $casts = [
    'email_verified_at' => 'datetime',
    'password'          => 'hashed',
    'is_active'         => 'boolean',
    'is_suspended'      => 'boolean',
    'is_flagged'        => 'boolean',
    'suspended_at'      => 'datetime',
];

    // ── Role helpers ──────────────────────────────────────────────
    public function isManajer(): bool   { return $this->role === 'manajer'; }
    public function isOperator(): bool  { return $this->role === 'operator'; }
    public function isPelanggan(): bool { return $this->role === 'pelanggan'; }
    public function isStaff(): bool     { return in_array($this->role, ['operator', 'manajer']); }

    // ── Status badge helpers ───────────────────────────────────────
    public function getStatusBadgeAttribute(): array
    {
        if ($this->is_suspended) {
            return ['label' => 'Suspended', 'color' => '#dc2626', 'bg' => '#fee2e2', 'icon' => 'bi-slash-circle-fill'];
        }
        if ($this->is_flagged) {
            return ['label' => 'Flagged', 'color' => '#d97706', 'bg' => '#fef3c7', 'icon' => 'bi-flag-fill'];
        }
        if (!$this->is_active) {
            return ['label' => 'Nonaktif', 'color' => '#64748b', 'bg' => '#f3f4f6', 'icon' => 'bi-pause-circle'];
        }
        return ['label' => 'Aktif', 'color' => '#16a34a', 'bg' => '#dcfce7', 'icon' => 'bi-check-circle-fill'];
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'manajer'   => 'Manajer',
            'operator'  => 'Operator',
            'pelanggan' => 'Pelanggan',
            default     => ucfirst($this->role),
        };
    }

    // ── Relationships ─────────────────────────────────────────────
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function handledOrders()
    {
        return $this->hasMany(Order::class, 'handled_by');
    }

    public function flags()
    {
        return $this->hasMany(UserFlag::class, 'user_id')->latest();
    }

    public function suspendedBy()
    {
        return $this->belongsTo(User::class, 'suspended_by');
    }

    // ── Scopes ────────────────────────────────────────────────────
    public function scopePelanggan($q) { return $q->where('role', 'pelanggan'); }
    public function scopeStaff($q)     { return $q->whereIn('role', ['operator','manajer']); }
    public function scopeFlagged($q)   { return $q->where('is_flagged', true); }
    public function scopeSuspended($q) { return $q->where('is_suspended', true); }
    public function scopeAktif($q)     { return $q->where('is_active', true)->where('is_suspended', false); }
}
