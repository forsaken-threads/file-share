<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visibility extends Model
{
    const ANY_AUTHENTICATED_USER = 4;
    const ONLY_ME = 1;
    const PUBLIC_WITH_PASSWORD = 3;
    const PUBLIC_WITHOUT_PASSWORD = 2;

    public $timestamps = false;
}
