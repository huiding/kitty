<?php
namespace database;
interface IDBDriver{
    public function connect();
    public function query($sql = NULL);
    public function row();
    public function all();
    public function count();
}
