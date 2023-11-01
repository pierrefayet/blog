<?php

namespace App\Model;

class User
{
    private $userId;
    private $username;
    private $role;
    public function __construct()
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->role = $role;
    }

    public function hasPermission():bool
{

}
}