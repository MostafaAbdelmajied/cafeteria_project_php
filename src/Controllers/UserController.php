<?php

namespace Src\Controllers;

use Src\Models\User;

class UserController {

public function index(){
    
    $user = ['users'];

    // first way 
    //return view('users.php', ['user' => $user]);

    // second way
    view('users.php', compact('user'));
}
}