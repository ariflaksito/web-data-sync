<?php

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $table = 'version';

    public $timestamps = false;

    public function setUpdatedAt($value){ }

    public function setCreatedAt($value){ }
}