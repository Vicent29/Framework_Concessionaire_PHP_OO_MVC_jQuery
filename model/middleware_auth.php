<?php
include($_SERVER['DOCUMENT_ROOT'] . "/Framework_Concesionaire/model/JWT.php");
function descode_token($token)
{

    $jwt = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/Framework_Concesionaire/model/jwt.ini');
    $secret = $jwt['secret'];

    $JWT = new JWT;
    $token_dec = $JWT->decode($token, $secret);
    $rt_token = json_decode($token_dec, TRUE);
    return $rt_token;
}

function create_token($username)
{
    $jwt = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/Framework_Concesionaire/model/jwt.ini');
    $header = $jwt['header'];
    $secret = $jwt['secret'];
    $payload = '{"iat":"' . time() . '","exp":"' . time() + (600) . '","username":"' . $username . '"}';

    $JWT = new JWT;
    $token = $JWT->encode($header, $payload, $secret);

    return $token;
}
