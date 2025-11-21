<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client_emails extends Model
{
    //
    protected $fillable = ['client_id', 'email'];

    public function client() {
        return $this->belognsTo('App\Models\Clients');
    }
}
