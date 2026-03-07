<?php

use Src\Models\Emp;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';

// $users = Emp::query()
//     ->whereIn("first_name", ["Hayden", "Isaiah"])
//     ->get();
// var_dump($users);

// $user = Emp::createMany([
//     [
//     "first_name" => "mostafa",
//     "last_name"=> "last_name",
//     "email"=> "email2",
//     "country" => "country",
//     "address" => "address",
//     "gender"=> "Male",
//     "user_name" => "user_name",
//     "password" => "pass",
//     "department" => "department"
//     ],
//     [
//     "first_name" => "mostafa",
//     "last_name"=> "last_name",
//     "email"=> "email3",
//     "country" => "country",
//     "address" => "address",
//     "gender"=> "Male",
//     "user_name" => "user_name",
//     "password" => "pass",
//     "department" => "department"
//     ],
// ]);

// var_dump($user);

// Emp::update(15, [
//     "first_name" => "mostafa",
//     "last_name"=> "last_name",
//     "email"=> "email3",
//     "country" => "country",
//     "address" => "address",
//     "gender"=> "Male",
//     "user_name" => "user_name",
//     "password" => "pass",
//     "department" => "department"
//     ]);

Emp::delete(15);