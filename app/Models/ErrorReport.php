<?php

namespace MaaximOne\LaAdmin\Models;

use MaaximOne\LaAdmin\Casts\JsonDateCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ErrorReport extends Model
{
    const CREATED_AT = 'report_created_at';
    const UPDATED_AT = null;
    protected $primaryKey = 'report_id';
    protected $casts = [
        'report_fixed_at' => 'datetime:H:i d.m.Y',
        'report_created_at' => 'datetime:H:i d.m.Y',
        'report_read_at' => 'datetime:H:i d.m.Y',
        'report_events' => JsonDateCast::class,
    ];

    public function setReadStatus(): static
    {
        if ($this->report_read_at == null) {
            $this->report_read_at = now()->format($this->getDateFormat());
            $this->save();

            $this->newEvent('Прочтено', Auth::user(), 'mdi-read', 'warning');
        }

        return $this;
    }

    public function newEvent($text, User|array $user, string $icon = null, $color = 'info'): static
    {
        $events = collect($this->report_events)->add([
            'text' => $text,
            'color' => $color,
            'icon' => $icon,
            'user' => $user,
            'date' => now()->format($this->getDateFormat()),
        ]);

        $this->report_events = $events;
        $this->save();

        return $this;
    }

    public function setFixedStatus(): static
    {
        if ($this->report_fixed_at == null) {
            $this->report_fixed_at = now()->format($this->getDateFormat());
            $this->report_fixed_user = Auth::user()->id;
            $this->save();

            $this->newEvent('Ошибка исправлена', Auth::user(), 'mdi-bug-check-outline', 'success');
        }

        return $this;
    }
}
