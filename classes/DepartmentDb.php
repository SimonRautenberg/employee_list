<?php

class DepartmentDb extends Department
{
    private array $employees = [];

    /**
     * @return Employee[]
     */
    public function getEmployees(): array
    {
        return $this->employees;
    }


    /**
     * @return void
     * @throws Exception
     */
    public function buildEmployees(): void
    {
        $this->employees = ((new EmployeeDb())->getAllEmployeesByDepartment($this));
    }
  /**
   * @param int $id
   * @return DepartmentDb
   */
  public function getObjectById(int $id): DepartmentDb
  {
      try {
          $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
          $sql = "SELECT * FROM department WHERE id = :id";
          $stmt = $dbh->prepare($sql);
          $stmt->bindParam('id', $id, PDO::PARAM_INT);
          $stmt->execute();
          // $employees füllen
          $department = $stmt->fetchObject(__CLASS__);
          $department->buildEmployees();
          return $department;
      }catch (PDOException $e) {
          throw new PDOException('Datenbank sagt nein: ' . $e->getMessage());
      }



  }

  /**
   * @return DepartmentDb[]
   */
  public function getAllAsObjects(): array|null
  {
    try {
      $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
      $sql = "SELECT * FROM department";
      $stmt = $dbh->query($sql);
      $departments = [];
        while ($dep = $stmt->fetchObject(__CLASS__)) {
            $dep->buildEmployees();
            $departments[] = $dep;
        }
        $dbh = null;
    } catch (PDOException $e) {
      throw new PDOException('Datenbank sagt nein: ' . $e->getMessage());
    }
    return $departments;
  }

  /**
   * @param string $name
   * @return DepartmentDb
   */
  public function createNewObject(string $name): DepartmentDb
  {
    $dbh = new PDO (DB_DSN, DB_USER, DB_PASSWD);
    $sql = "INSERT INTO department (id, name) VALUES (NULL, :name)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam('name', $name);
    $stmt->execute();
    $id = $dbh->lastInsertId();
    return new DepartmentDb($id, $name);
  }

  //info Version lösche Abteilung incl. Mitarbeiter
//  /**
//   * @param int $id
//   * @return void
//   * @throws Exception
//   */
//  public function delete(int $id): void
//  {
//    try {
//      $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
//      $sql = "DELETE FROM department WHERE id = :id";
//      $stmt = $dbh->prepare($sql);
//      $stmt->bindParam('id', $id, PDO::PARAM_INT);
//      $stmt->execute();
//    } catch (PDOException $e) {
//      throw new Exception('Fehler in delete Department: ' . $e->getMessage());
//    }
//  }

  //info Version lösche Abteilung nur wenn keine Mitarbeiter
  /**
   * @param int $id
   * @return void
   * @throws Exception
   */
  public function delete(int $id): void
  {
    $employeesLeft = (new EmployeeDb())->getAllEmployeesByDepartment((new DepartmentDb())->getObjectById($id));
    if (count($employeesLeft) === 0) {
      try {
        $dbh = new PDO (DB_DSN, DB_USER, DB_PASSWD);
        $sql = "DELETE FROM department WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam('id', $id, PDO::PARAM_INT);
        $stmt->execute();
      } catch (PDOException $e) {
        throw new Exception('Fehler in delete Department: ' . $e->getMessage());
      }
    }
  }

  /**
   * @return void
   * @throws Exception
   */
  public function updateObject(): void
  {
    try {
      $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
      $sql = "UPDATE department SET name = :name WHERE id = :id";
      $stmt = $dbh->prepare($sql);
      $stmt->bindParam('name', $this->name);
      $stmt->bindParam('id', $this->id);
      $stmt->execute();
      $dbh = null;
    } catch (PDOException $e) {
      throw new Exception($e->getMessage());
    }
  }

}