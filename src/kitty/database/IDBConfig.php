<?php
namespace database;
interface IDBConfig{

    public function getHost();

    public function getUser();

    public function getPassword();

    public function getPort();

    public function getDatabase();
}
