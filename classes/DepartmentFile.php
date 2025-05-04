<?php

class DepartmentFile extends Department
{
  /**
   * @param int $id
   * @return DepartmentFile
   */
  public function getObjectById(int $id): DepartmentFile
  {
    $department = new DepartmentFile();
    $departments = $this->getAllAsObjects();
    foreach ($departments as $e) {
      if ($id === $e->getId()) {
        $department = $e;
      }
    }
    return $department;
  }

  /**
   * @return array
   */
  public function getAllAsObjects(): array
  {
    try {

      if (!is_file(CSV_PATH_DEPARTMENT)) {
        fopen(CSV_PATH_DEPARTMENT, 'w');
      }

      $handle = fopen(CSV_PATH_DEPARTMENT, 'r');
      $departments = [];
      while ($content = fgetcsv($handle)) {
        $departments[] = new DepartmentFile((int)$content[0], $content[1]);
      }
      fclose($handle);
    } catch (Error $e) {
      // wird im view error.php ausgegeben
      throw new Error('Etwas ist nicht ok: ' . $e->getMessage() . ' ' . implode('-', $e->getTrace()) . ' ' . $e->getCode() . ' ' . $e->getLine());
    }
    return $departments;
  }

  /**
   * @param string $name
   * @return DepartmentFile
   */
  public function createNewObject(string $name): DepartmentFile
  {
    if (!is_file(CSV_PATH_DEPARTMENT_ID_COUNTER)) {
      file_put_contents(CSV_PATH_DEPARTMENT_ID_COUNTER, 1);
    }
    $id = file_get_contents(CSV_PATH_DEPARTMENT_ID_COUNTER);

    $department = new DepartmentFile($id, $name);
    $departments = $this->getAllAsObjects();
    $departments[] = $department;
    $this->storeInFile($departments);

    file_put_contents(CSV_PATH_DEPARTMENT_ID_COUNTER, $id + 1);
    return $department;
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
//      $departments = $this->getAllAsObjects();
//      $employees = (new EmployeeFile())->getAllAsObjects();
//      foreach ($departments as $key => $department) {
//        if ($department->getId() === $id) {
//          unset($departments[$key]);
//          foreach ($employees as $ekey => $employee) {
//            if ($employee->getDepartmentId() === $id){
//              unset($employees[$ekey]);
//            }
//          }
//          break;
//        }
//      }
//      $this->storeInFile($departments);
//      (new EmployeeFile())->storeInFile($employees);
//    } catch (Error $e) {
//      throw new Exception('Fehler in delete: ' . $e->getMessage());
//    }
//  }
//

  //info Version lösche Abteilung nur wenn keine Mitarbeiter
  /**
   * @param int $id
   * @return void
   * @throws Exception
   */
  public function delete(int $id): void
  {
    $employeesLeft = (new EmployeeFile())->getAllEmployeesByDepartment((new DepartmentFile())->getObjectById($id));
    if (!$employeesLeft) {
      try {
        $departments = $this->getAllAsObjects();
        foreach ($departments as $key => $department) {
          echo $department->getId() . " : $id" . "<br/>";
          if ($department->getId() === $id) {
            unset($departments[$key]);
            break;
          }
        }
        $this->storeInFile($departments);
      } catch (Error $e) {
        throw new Exception('Fehler in delete: ' . $e->getMessage());
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
      $departments = $this->getAllAsObjects();
      foreach ($departments as $key => $department) {
        if ($department->getId() === $this->getId()) {
          $departments[$key] = $this;
          break;
        }
      }
      $this->storeInFile($departments);
    } catch (Error $e) {
      throw new Exception('Fehler in store(): ' . $e->getMessage());
    }
  }

  /**
   * @param array $departments
   * @return void
   */
  private function storeInFile(array $departments): void
  {
    try {
      unlink(CSV_PATH_DEPARTMENT);
      $handle = fopen(CSV_PATH_DEPARTMENT, 'w', FILE_APPEND);
      foreach ($departments as $department) {
        $depNumArr = array_values((array)$department);
        fputcsv($handle, $depNumArr, ',');
      }
      fclose($handle);
    } catch (Error $e) {
      throw new Error('Fehler in store in file: ' . $e->getMessage() . ' ' . implode('-', $e->getTrace()));
    }
  }
}