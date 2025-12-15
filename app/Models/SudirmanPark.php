<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SudirmanPark extends Model
{
    use HasFactory;

    // Table used by this model
    protected $table = 'sudirman_parks';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'tower',
        'package',
        'status',
        'ktp',
        'note',
        'visible',
    ];

    protected $casts = [
        'visible' => 'boolean',
    ];

    public function getKtpUrlAttribute(): ?string
    {
        if (!$this->ktp) {
            return null;
        }
        $path = 'ktp/' . ltrim($this->ktp, '/');
        return \Storage::disk('public')->exists($path) ? \Storage::url($path) : null;
    }
}
