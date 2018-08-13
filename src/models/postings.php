<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Postings extends Model
{
    protected $table = 'postings';

    public $timestamps = false;

    // disable field updated_at
    public function setUpdatedAt($value){ }

    // disable field created_at
    public function setCreatedAt($value){ }

    public function getAllData()
    {
        $result = DB::select("select p.id, p.msg, p.postdate, u.uid, u.nid, u.fullname, u.status 
            from postings p 
            left join users u on u.uid = p.uid
            Order by p.postdate desc");
        return $result;
    }

    public function getByVersion($id)
    {
        $result = DB::select("select v.*, p.msg, p.postdate, u.uid, u.nid, u.fullname, u.status 
            from version v 
            left join postings p on v.id = p.id
            left join users u on u.uid = p.uid
            where ver > ".$id." Order by ver");
        return $result;
    }

    public function addPosting($uid, $msg)
    {
        $postdate = date('c');
        $ipaddr = $_SERVER['REMOTE_ADDR'];

        try{
            DB::beginTransaction();

            $posting = new Postings();
            $posting->uid = $uid;
            $posting->msg = $msg;
            $posting->postdate = $postdate;
            $posting->ipaddr = $ipaddr;
            $posting->save();

            $id = $posting->id;

            $version = new Version();
            $version->id = $id;
            $version->type = 'add';
            $version->changedate = $postdate;
            $version->ipaddr = $ipaddr;
            $version->save();

            DB::commit();
            return true;

        }catch(\Exception $e){
            DB::rollback();
            return false;
        }

    }

    public function deletePosting($id)
    {
        $postdate = date('c');
        $ipaddr = $_SERVER['REMOTE_ADDR'];

        try{
            DB::beginTransaction();

            $posting = Postings::find($id);
            $posting->delete();

            $version = new Version();
            $version->id = $id;
            $version->type = 'delete';
            $version->changedate = $postdate;
            $version->ipaddr = $ipaddr;
            $version->save();

            DB::commit();
            return true;

        }catch(\Exception $e){
            DB::rollback();
            return false;
        }

    }

    public function editPosting($id, $msg)
    {
        $postdate = date('c');
        $ipaddr = $_SERVER['REMOTE_ADDR'];

        try{
            DB::beginTransaction();

            $posting = Postings::find($id);
            $posting->msg = $msg;
            $posting->save();

            $version = new Version();
            $version->id = $id;
            $version->type = 'update';
            $version->changedate = $postdate;
            $version->ipaddr = $ipaddr;
            $version->save();

            DB::commit();
            return true;

        }catch(\Exception $e){
            DB::rollback();
            return false;
        }

    }

}