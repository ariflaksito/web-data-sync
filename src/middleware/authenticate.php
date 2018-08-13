<?php

class Authenticate{

    var $whiteList = array('\/auth','\/');

    public function __invoke($request, $response, $next)
    {
        $path = $request->getUri();
        if($this->isPublicUrl($path->getPath())){
            $response = $next($request, $response);
            return $response;
        }else{

            $token = $request->getHeader('Authorization');
            if($token==null){
                $data['msg'] = "Token harus diisikan";
                return $response->withStatus(500)->withJson($data);

            }else if(Users::validateToken($token)){
                if($this->isTokenAlive()){
                    $response = $next($request, $response);
                    return $response;
                }else{
                    $data['msg'] = "Token sudah kadaluarsa";
                    return $response->withStatus(500)->withJson($data);
                }

            }else{
                $data['msg'] = "Token tidak sesuai";
                return $response->withStatus(500)->withJson($data);
            }
        }
    }

    private function isPublicUrl($url) {
        $patterns_flattened = implode('|', $this->whiteList);
        $matches = null;
        preg_match('/' . $patterns_flattened . '/', $url, $matches);
        return (count($matches) > 0);
    }

    public function isTokenAlive()
    {
        $user = Users::$val;
        if(strtotime($user['token_expire'])< time()){
            return false;
        }else return true;

    }

}