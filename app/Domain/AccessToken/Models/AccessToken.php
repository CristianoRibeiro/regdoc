<?php

namespace App\Domain\AccessToken\Models;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    protected $fillable = [
        'api',
        'type',
        'access_token',
        'expires_in',
        'used',
        'date_last_use',
        'url',
        'payload_send',
        'payload_returned'
    ];
}
