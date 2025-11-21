<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    protected $fillable = ['name', 'surname', 'phone'];

    public function client_emails() {
        return $this->hasMany('App\Models\Client_emails', 'client_id', 'id');
    }

}
