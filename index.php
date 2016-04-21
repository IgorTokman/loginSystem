<?php

require_once 'core/init.php';

Config::get('mysql/db');

$db = DB::getInstance();
$db->get('users', array('username', '=', 'alex'));

$db->first()->username;

$db->update('users', 2,  array('username' => 'vasya', 'salt' => 'salt',
    "password" => 1234));

