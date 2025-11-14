<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vpn extends Model
{ 
    protected $table = 'vpn_accounts';
    protected $fillable = [
        'username',
        'email',       // make sure 'email' is in fillable
        'password',
        'status',
        'send_status',
    ];


}
