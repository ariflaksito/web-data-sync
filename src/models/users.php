<?php

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    public static $val;

    protected $table = 'users';

    public $timestamps = false;

    public function setUpdatedAt($value){ }

    public function setCreatedAt($value){ }

    public function login($usr, $pwd)
    {
        $login = Users::where('nid', $usr)->where('pass', $pwd)->first();
        return $login;
    }

    public function addToken($uid, $token, $token_exp)
    {
        return Users::where('uid', $uid)
        ->update([
            'token' => $token,
            'token_expire' => $token_exp
        ]);
    }

    public function validateToken($token)
    {
        $validate = Users::where('token', $token)->first();
        Users::$val = $validate;

        if($validate!=null) return true;
        else return false;
    }

    public function getAuth()
    {
        return Users::$val;
    }

}