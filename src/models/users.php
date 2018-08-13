<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Users extends Model
{
    protected $table = 'users';

    public $timestamps = false;

    public function setUpdatedAt($value){ }

    public function setCreatedAt($value){ }

    public function login($usr, $pwd)
    {
        $login = Users::where('nid', $usr)->where('pass', $pwd)->first();
        return $login;
    }
}