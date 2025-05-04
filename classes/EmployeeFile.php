<?php

class EmployeeFile extends Employee
{
  /**
   * @return EmployeeFile[]|null
   * @throws Exception
   */
  public function getAllAsObjects(): array|null
  {
      try {

        if (!is_file(CSV_PATH_EMPLOYEE)) {
          fopen(CSV_PATH_EMPLOYEE, 'w');
        }
        $handle = fopen(CSV_PATH_EMPLOYEE, 'r');
        $employees = [];
        while ($content = fgetcsv($handle)) {
          $employees[] = new EmployeeFile($content[0], $content[1], $content[2], $content[3]);
        }
        fclose($handle);
      } catch (Error $e) {
        throw new Exception($e->getMessage() . ' ' . implode('-', $e->getTrace()) . ' ' . $e->getCode() . ' ' . $e->getLine());
      }
    return $employees;
  }

  /**
   * @param $department
   * @return array|null
   * @throws Exception
   */
  public function getAllEmployeesByDepartment($department) :array|null
  {
    try{
      $employees = (new EmployeeFile())->getAllAsObjects();
      $empByDepartment = [];
      foreach($employees as $employee) {
        if($department->getId() === $employee->getDepartmentId()){
          $empByDepartment[] = $employee;
        }
      }
    } catch (Error $e) {
      throw new Exception($e->getMessage());
    }
    return $empByDepartment;
  }


  /**
   * @param int $id
   * @return EmployeeFile|false
   * @throws Exception
   */
  public function getObjectById(int $id): EmployeeFile|false
  {
      $employee = new EmployeeFile();
      $employees = $this->getAllAsObjects();
      foreach ($employees as $e) {
        if ($id === $e->getId()) {
          $employee = $e;
        }
      }
      return $employee;
  }

  /**
   * @param string $firstName
   * @param string $lastName
   * @param int $departmentId
   * @return EmployeeFile
   * @throws Exception
   */
  public function createNewObject(string $firstName, string $lastName, int $departmentId): EmployeeFile
  {
      if (!is_file(CSV_PATH_EMPLOYEE_ID_COUNTER)) {
        file_put_contents(CSV_PATH_EMPLOYEE_ID_COUNTER, 1);
      }
      // nächste freie id auslesen
      $id = file_get_contents(CSV_PATH_EMPLOYEE_ID_COUNTER);

      $employee = new EmployeeFile($id, $firstName, $lastName, $departmentId);
      $employees = $this->getAllAsObjects();
      $employees[] = $employee; // neuen employee zu Liste ($employees) hinzufügen
      $this->storeInFile($employees);

      file_put_contents(CSV_PATH_EMPLOYEE_ID_COUNTER, $id + 1);
      return new EmployeeFile($id, $firstName, $lastName, $departmentId);
    }

  /**
   * @return void
   * @throws Exception
   */
  public function updateObject(): void
  {
      try {
        $employees = $this->getAllAsObjects();
        foreach ($employees as $key => $employee) {
          if ($employee->getId() === $this->getId()) {
            $employees[$key] = $this;
            break;
          }
        }
        $this->storeInFile($employees);
      } catch (Error $e) {
        throw new Exception('Fehler in store(): ' . $e->getMessage());
      }
  }

  /**
   * @param array $employees
   * @return void
   * @throws Exception
   */
  public function storeInFile(array $employees): void
  {
    try {
      unlink(CSV_PATH_EMPLOYEE);
      $handle = fopen(CSV_PATH_EMPLOYEE, 'w', FILE_APPEND);
      foreach ($employees as $employee) {
        $empNumArr = array_values((array)$employee);
        fputcsv($handle, $empNumArr, ',');
      }
      fclose($handle);
    } catch (Error $e) {
      throw new Exception('Fehler in store in file: ' . $e->getMessage() . ' ' . implode('-', $e->getTrace()));
    }
  }

  /**
   * @param int $id
   * @return void
   * @throws Exception
   */
  public function delete(int $id): void
  {
    try {
      $employees = $this->getAllAsObjects();
      foreach ($employees as $key => $employee) {
        if ($employee->getId() === $id) {
          unset($employees[$key]);
          break;
        }
      }
      $this->storeInFile($employees);
    } catch (Error $e) {
      throw new Exception('Fehler in delete: ' . $e->getMessage());
  }
  }

  public function getDepartmentName(): string
  {
    return ((new DepartmentFile())->getObjectById($this->departmentId))->getName();
  }

}