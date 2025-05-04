<?php

interface Saveable
{
  public function getAllAsObjects();
  public function getObjectById(int $id);
  public function updateObject();
  public function delete(int $id);
}