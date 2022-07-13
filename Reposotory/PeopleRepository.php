<?php
interface PeopleRepository{
    public function findById();

    public function save();

    public function delete();

    public function findAll();

    public function update();
}