<?php

class ConnectDB
{
  private ?string $host = null;
  private ?string $user = null;
  private ?string $pass = null;
  private ?string $dbname = null;

  public function connect(): object
  {
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    ];

    try {
      $this->setupParams();

      // DSN = Data Source Name, beinhaltet: Art der Datenbank (mysql:),
      // hostname und Datenbankname

      $dsn = "mysql:host=$this->host; dbname=$this->dbname";
      $conn = new PDO($dsn, $this->user, $this->pass, $options);
    } catch (PDOException $e) {
      echo "Kacke is am dampfen: " . $e->getMessage();
      die;
    }
    return $conn;
  }

  private function setupParams(): void
  {
    $conf = parse_ini_file(INI_FILE);
    $this->host = $conf['host'];
    $this->user = $conf['user'];
    $this->pass = $conf['pass'];
    $this->dbname = $conf['dbname'];
  }
}
