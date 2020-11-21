<?php
    class Sistema
    {
        // database connection and table name

        private $conn;
     
        // object properties

        public $idsistema;
        public $descricao;
        public $monitor;
     
        // constructor with $db as database connection

        public function __construct($db)
        {
            $this->conn = $db;
        }

        // read all records

        public function readAll()
        {
            $this->monitor = 'T';

            $sql = $this->conn->prepare("SELECT idsistema,descricao AS sistema FROM sistema WHERE monitor = :monitor ORDER BY descricao");
            $sql->bindParam(':monitor', $this->monitor, PDO::PARAM_STR);
            $sql->execute();

            return $sql;
        }

        // check by same record on database

        public function sistemaInsertExist()
        {
            $sql = $this->conn->prepare("SELECT idsistema FROM sistema WHERE descricao = :descricao");
            $sql->bindParam(':descricao', $this->descricao, PDO::PARAM_STR);
            $sql->execute();

                if ($sql->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }

            return $sql;
        }

        // check by same record on database

        public function sistemaUpdateExist()
        {
            $sql = $this->conn->prepare("SELECT idsistema FROM sistema WHERE descricao = :descricao AND idsistema <> :idsistema");
            $sql->bindParam(':descricao', $this->descricao, PDO::PARAM_STR);
            $sql->bindParam(':idsistema', $this->idsistema, PDO::PARAM_INT);
            $sql->execute();

                if ($sql->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }

            return $sql;
        }

        // insert record

        public function insertSystemBefore()
        {
            if ($this->sistemaInsertExist()) {
                die('Esse sistema j&aacute; est&aacute; cadastrado.');
            } else {
                $this->monitor = 'T';

                $sql = $this->conn->prepare("INSERT INTO sistema (descricao,monitor) VALUES (:descricao,:monitor)");
                $sql->bindParam(':descricao', $this->descricao, PDO::PARAM_STR);
                $sql->bindParam(':monitor', $this->monitor, PDO::PARAM_STR);
                
                    if ($sql->execute()) {
                        $this->idsistema = $this->conn->lastInsertId();
                        return $this->idsistema;
                    }
            }
        }

        // insert record

        public function insert()
        {
            if ($this->sistemaInsertExist()) {
                die('Esse sistema j&aacute; est&aacute; cadastrado.');
            } else {
                $this->monitor = 'T';

                $sql = $this->conn->prepare("INSERT INTO sistema (descricao,monitor) VALUES (:descricao,:monitor)");
                $sql->bindParam(':descricao', $this->descricao, PDO::PARAM_STR);
                $sql->bindParam(':monitor', $this->monitor, PDO::PARAM_STR);
                $sql->execute();

                return $sql;
            }
        }

        // update record

        public function update()
        {
            if ($this->sistemaUpdateExist()) {
                die('Esse sistema j&aacute; est&aacute; cadastrado.');
            } else {
                $sql = $this->conn->prepare("UPDATE sistema SET descricao = :descricao WHERE idsistema = :idsistema");
                $sql->bindParam(':descricao', $this->descricao, PDO::PARAM_STR);
                $sql->bindParam(':idsistema', $this->idsistema, PDO::PARAM_INT);
                $sql->execute();

                return $sql;
            }
        }

        // delete record

        public function delete()
        {
            $this->monitor = 'F';

            $sql = $this->conn->prepare("UPDATE sistema SET monitor = :monitor WHERE idsistema = :idsistema");
            $sql->bindParam(':monitor', $this->monitor, PDO::PARAM_STR);
            $sql->bindParam(':idsistema', $this->idsistema, PDO::PARAM_INT);
            $sql->execute();

            return $sql;
        }
    }
