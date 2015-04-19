<?php

namespace app\Service;
use vendor\Core\App\Service\Service;

/**
 * @Service(name="acme.dao")
 */
class Dao {

    /**
     * @var \MongoCollection
     */
    private $users;

    function __construct() {

        $client = new \MongoClient();
        $this->users = $client->selectDB('community')->selectCollection('users');

    }

    /**
     * @return \MongoCollection
     */
    public function getUsers()
    {
        return $this->users;
    }



}