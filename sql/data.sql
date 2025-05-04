DROP DATABASE IF EXISTS company;
CREATE DATABASE company;
USE company;
DROP TABLE IF EXISTS department;
DROP TABLE IF EXISTS employee;
USE company;

CREATE TABLE department(id INT PRIMARY KEY  AUTO_INCREMENT, name VARCHAR(45));
CREATE TABLE employee(id INT PRIMARY KEY AUTO_INCREMENT,
                      firstName VARCHAR(45), lastName VARCHAR(45), departmentId INT);

INSERT department VALUES
                      (NULL, 'Verkauf'),
                      (NULL, 'Schwund'),
                      (NULL, 'Unfug'),
                      (NULL, 'Personal'),
                      (NULL, 'Marketing');

INSERT INTO employee VALUES
                      (NULL, 'Denis', 'Struck',2),
                      (NULL, 'Aaliyah', 'Gebert',3),
                      (NULL, 'Grit', 'Kühl',4),
                      (NULL, 'Hamid', 'Joseph',3),
                      (NULL, 'Ricardo', 'Schüle',1),
                      (NULL, 'Liliane', 'Holler',5),
                      (NULL, 'Mikka', 'Borgmann',2),
                      (NULL, 'Valja', 'Ecker',2),
                      (NULL, 'Orhan', 'Horn',1),
                      (NULL, 'Vasili', 'Küpper',4);

ALTER TABLE employee
    ADD FOREIGN KEY (departmentId) REFERENCES department(id) ON DELETE CASCADE ;
