<?php

class EmployeeDb extends Employee
{
    /**
     * @var Employee[]
     */
    private array $employees;

    /**
     * @throws Exception
     */
    public function getAllAsObjects(Department $department = null): array|null
    {
        try {
            $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
            if (!isset($department)) {
                $sql = 'SELECT * from employee';
                $result = $dbh->query($sql);
            } else {
                $sql = "SELECT * from employee WHERE departmentId = :departmentId";
                $stmt = $dbh->prepare($sql);
                $id = $department->getId();
                $stmt->bindParam('departmentId', $id);
                $stmt->execute();
                $result = $stmt; // technisch zum Abfragen der while-Schleife
            }
            $employees = [];
            while ($row = $result->fetchObject(__CLASS__)) {
                $employees[] = $row;
            }
            $dbh = null;
        } catch (Exception $e) {
            throw new Exception($e->getMessage() . ' ' . implode('-', $e->getTrace()) . ' ' . $e->getCode() . ' ' . $e->getLine());
        }
        return $employees;
    }

    /**
     * @param Department $department
     * @return Employee[]
     * @throws Exception
     */
    public function getAllEmployeesByDepartment(Department $department): array
    {
        return $this->getAllAsObjects($department);
    }

    /**
     * @param int $id
     * @return EmployeeDb|false
     * @throws Exception
     */
    public function getObjectById(int $id): EmployeeDb|false
    {
        try {
            $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
            //info Version bisher
            /*
             * $sql = "SELECT * from employee WHERE id = :id";
             * $result = $dbh->query($sql);
             * $employee = $result-fetch_object(__CLASS__);
            */
            //info Version mit prepared Statement

            //info nur variable Werte werden mit :... gekennzeichnet
            $sql = "SELECT * from employee WHERE id = :id";

            //info sql wird an die SQL-Datenbank geschickt und es wird eine Syntaxprüfung durchgeführt
            $stmt = $dbh->prepare($sql);

            //info die Werte für die Platzhalter :... werden auf Datentyp überprüft und dann in das sql-Statement eingesetzt
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            //info das vollständige Statement wird in der Datenbank ausgeführt
            $stmt->execute();

            //info die zurückgegebenen Daten werden ausgelesen
            $employee = $stmt->fetchObject(__CLASS__);

            $dbh = null;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage() . ' ' . implode('-', $e->getTrace()) . ' ' . $e->getCode() . ' ' . $e->getLine());
        }
        return $employee;
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param int $departmentId
     * @return EmployeeDb
     * @throws Exception
     */
    public
    function createNewObject(string $firstName, string $lastName, int $departmentId): EmployeeDb
    {
        try {
            $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
            // in programmiersprachen, bei denen alle Objekte im RAM sind
            // um sicher zu gehen, das das der employee auch einem department zugewiesen sein muss,
            // können wir hier eine entsprechende methode aufrufen. dies hat den nachteil
            // dass wir die klasse EmployeeDb nie ohne die klasse department Db benutzen können.
            // dies ist im Allgemeinen eine ungewollte abhängigkeit.
            $sql = "INSERT INTO employee (id, firstName, lastName, departmentId) VALUES (NULL, :firstName, :lastName, :departmentId)";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam('firstName', $firstName, PDO::PARAM_STR);
            $stmt->bindParam('lastName', $lastName, PDO::PARAM_STR);
            $stmt->bindParam('departmentId', $departmentId, PDO::PARAM_INT);
            $stmt->execute();
            $id = $dbh->lastInsertId();
            $employee = new EmployeeDb($id, $firstName, $lastName, $departmentId);
            // in programmiersprachen, bei denen alle Objekte im RAM sind
            // um sicher zu gehen, das das der employee auch einem department zugewiesen sein muss,
            // können wir hier eine entsprechende methode aufrufen. dies hat den nachteil
            // dass wir die klasse EmployeeDb nie ohne die klasse department Db benutzen können.
            // dies ist im Allgemeinen eine ungewollte abhängigkeit.
            $dbh = null;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
        return $employee;
    }

    /**
     * @return void
     * @throws Exception
     */
    public
    function updateObject(): void
    {
        try {
            $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
            $sql = "UPDATE employee SET 
                    firstName = :firstName,
                    lastName = :lastName, 
                    departmentId = :departmentId 
                WHERE id = :id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam('firstName', $this->firstName, PDO::PARAM_STR);
            $stmt->bindParam('lastName', $this->lastName, PDO::PARAM_STR);
            $stmt->bindParam('departmentId', $this->departmentId, PDO::PARAM_INT);
            $stmt->bindParam('id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
            $dbh = null;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return void
     * @throws Exception
     */
    public
    function delete(int $id): void
    {
        try {
            $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWD);
            $sql = "DELETE FROM employee WHERE id = :id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam('id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $dbh = null;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public
    function getDepartmentName(): string
    {
        return ((new DepartmentDb())->getObjectById($this->departmentId))->getName();
    }

}