<?php
    class Arquivo
    {
        // database connection and table name

        private $conn;
     
        // object properties

        public $idarquivo;
        public $idcomando;
        public $link;
     
        // constructor with $db as database connection

        public function __construct($db)
        {
            $this->conn = $db;
        }

        // read all records

        public function readAll()
        {
            $sql = $this->conn->prepare("SELECT idarquivo,link FROM arquivo");
            #$sql->bindParam(':idarquivo', $idarquivo, PDO::PARAM_INT);
            $sql->execute();

            return $sql;
        }

        // read single record

        public function readSingle($idarquivo)
        {
            $sql = $this->conn->prepare("SELECT idarquivo,link FROM arquivo WHERE idarquivo = :idarquivo");
            $sql->bindParam(':idarquivo', $idarquivo, PDO::PARAM_INT);
            $sql->execute();

            return $sql;
        }

        // read record for command

        public function readForCommand($idcomando)
        {
            $this->monitor = 'T';

            $sql = $this->conn->prepare("SELECT arquivo.idarquivo,arquivo.link FROM arquivo INNER JOIN comando ON arquivo.comando_idcomando = comando.idcomando  WHERE comando.idcomando = :idcomando");
            $sql->bindParam(':idcomando', $idcomando, PDO::PARAM_INT);
            $sql->execute();

            return $sql;
        }

        // delete record

        public function delete()
        {
            $sql = $this->conn->prepare("DELETE FROM arquivo WHERE idarquivo = :idarquivo");
            $sql->bindParam(':idarquivo', $this->idarquivo, PDO::PARAM_INT);
            $sql->execute();
                
            return $sql;
        }

        // truncate table

        public function truncate()
        {
            $sql = $this->conn->prepare("TRUNCATE TABLE arquivo");
            $sql->execute();
                
            return $sql;
        }
    }