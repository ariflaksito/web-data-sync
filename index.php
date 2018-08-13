<?php

use \Illuminate\Database\Capsule\Manager as Manager;

require 'vendor/autoload.php';
require 'src/models/postings.php';
require 'src/models/version.php';
require 'src/models/users.php';

require 'src/middleware/authenticate.php';
require 'src/handlers/exception.php';

$config = include('src/config.php');

$app = new \Slim\App(['settings' => $config]);

$app->add(new Authenticate());

$container = $app->getContainer();

$capsule = new Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$capsule->getContainer()->singleton(
    IlluminateContractsDebugExceptionHandler::class,
    AppExceptionsHandler::class
);


$app->get('/', function ($req, $res, $args) {
    $data = array(1=>"Hello", 2=>"World");
    return $res->withStatus(200)->withJson($data);
});


$app->get('/ver/{id}', function($req, $res, $args){
	$route = $req->getAttribute('route');
	$id = $route->getArgument('id');

	$result = Postings::getByVersion($id);
	return $res->withStatus(200)->withJson($result);
});

$app->post('/msg', function($req, $res, $args){

	if($req->isPost()){
		$post = $req->getParsedBody();
		$msg = $post['msg'];
		$out = array();
		$status = 500;

		try{
            $user = $req->getAttribute('user');

            Postings::addPosting($user['uid'], $msg);
            $out['msg'] = "Kirim pesan berhasil";
            $status = 200;
		}catch(Exception $e){
            $out['msg'] = $e;
		}

		return $res->withStatus($status)->withJson($out);
	}

});

$app->delete('/msg/{id}', function($req, $res, $args){
	if($req->isDelete()){
		$route = $req->getAttribute('route');
		$id = $route->getArgument('id');
		$out = array();
		$status = 500;

        try{
            Postings::deletePosting($id);
            $out['msg'] = "Hapus pesan berhasil";
            $status = 200;
        }catch(Exception $e){
            $out['msg'] = $e;
        }

        return $res->withStatus($status)->withJson($out);
	}	
});	

$app->put('/msg/{id}', function($req, $res, $args){
	if($req->isPut()){
		$route = $req->getAttribute('route');
		$id = $route->getArgument('id');
		$out = array();
		$status = 500;

		$put = $req->getParsedBody();
		$msg = $put['msg'];

        try{
            Postings::editPosting($id, $msg);
            $out['msg'] = "Edit pesan berhasil";
            $status = 200;
        }catch(Exception $e){
            $out['msg'] = $e;
        }

        return $res->withStatus($status)->withJson($out);
	}	
});	


$app->post('/auth', function($req, $res, $args) use($config){

	if($req->isPost()){
		$post = $req->getParsedBody();
		$nid = $post['nid'];
		$pwd = md5($post['pwd']);
		$out = array();
		$status = 500;

		$out['data'] = Users::login($nid, $pwd);
		unset($out['data']['pass']);
		if($out['data']!=null) {
		    $status = 200;
            $out['msg'] = "Login berhasil";

            // save token + token_expired
            $token = bin2hex(openssl_random_pseudo_bytes(16));
            $tokenExpired = date('Y-m-d H:i:s', strtotime($config['tokenExpired']));

            Users::addToken($out['data']['uid'], $token, $tokenExpired);

            $out['data']['token'] = $token;
            $out['data']['token_expire'] = $tokenExpired;

		}else{
		    unset($out['data']);
            $out['msg'] = "NIP atau Password tidak sesuai";
        }

		return $res->withStatus($status)->withJson($out);
	}	

});

$app->get('/old', function($req, $res, $args){

    $result = Postings::getAllData();
	return $res->withStatus(200)->withJson($result);
});

$app->run();

