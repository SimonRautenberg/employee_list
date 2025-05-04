<?php

class View
{
  private string $action;
  private string $heading;

  /**
   * @return string
   */
  public function getAction(): string
  {
    return $this->action;
  }

  /**
   * @param string $action
   */
  public function setAction(string $action): void
  {
    $this->action = $action;
  }

  /**
   * @return string
   */
  public function getHeading(): string
  {
    return $this->heading;
  }

  /**
   * @param string $heading
   */
  public function setHeading(string $heading): void
  {
    $this->heading = $heading;
  }

}