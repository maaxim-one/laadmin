<?php

namespace MaaximOne\LaAdmin\Models;

use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use BroadcastsEvents;

    public $timestamps = false;
    protected $primaryKey = 'role_id';
    protected $table = 'roles';

    protected function rules(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value),
            set: fn($value) => json_encode($value)
        );
    }

    public function broadcastOn(string $event): array
    {
        return [$this];
    }
}
