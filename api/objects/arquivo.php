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

        // insert record

        /*public function insert()
        {
            $sql = $this->conn->prepare("INSERT INTO arquivo (comando_idcomando,link) VALUES (:idcomando,:link)");
            $sql->bindParam(':idcomando', $this->idcomando, PDO::PARAM_INT);
            $sql->bindParam(':link', $this->link, PDO::PARAM_STR);
            #$sql->execute();
                if ($sql->execute()) {
                    if ($this->link) {
                        if (self::upload()) {
                            return $sql;
                        }
                    } else {
                        return $sql;
                    }
                }
            #return $sql;
        }*/

        // upload files

        public function upload()
        {
            $count = count($this->link['name']);

                if ($count > 0) {
                    $a = 1;
                    $dir = '../../anexo/';

                    for ($i = 0; $i < $count; $i++) {
                        $remete = $this->link['tmp_name'][$i];
                        $destino = $dir . $this->link['name'][$i];
        
                            if (move_uploaded_file($remete, $destino)) {
                                // rename the file
            
                                $py = md5($this->link['tmp_name'][$i]);
                                $ext = strrchr($this->link['name'][$i], '.');
            
                                    if (rename($destino, $dir . $py . $ext)) {
                                        if (($this->link['type'][$i] == 'image/jpg') or ($this->link['type'][$i] == 'image/jpeg') or ($this->link['type'][$i] == 'image/png')) {
                                            // change the size of image and insert on db
                
                                            $link = $py . $ext;

                                            if (self::thumbnail($dir, $link, 800, 600)) {
                                                $sql = $this->conn->prepare("INSERT INTO arquivo (comando_idcomando,link) VALUES (:idcomando,:link)");
                                                $sql->bindParam(':idcomando', $this->idcomando, PDO::PARAM_INT);
                                                $sql->bindParam(':link', $link, PDO::PARAM_STR);
                    
                                                    if ($sql->execute()) {
                                                        if ($a == $count) {
                                                            return $sql;
                                                        }
                                                    }
                                            }
                                        } else {
                                            $link = $py . $ext;
                
                                            $sql = $this->conn->prepare("INSERT INTO arquivo (comando_idcomando,link) VALUES (:idcomando,:link)");
                                            $sql->bindParam(':idcomando', $this->idcomando, PDO::PARAM_INT);
                                            $sql->bindParam(':link', $link, PDO::PARAM_STR);
                                                        
                                                if ($sql->execute()) {
                                                    if ($a == $count) {
                                                        return $sql;
                                                    }
                                                }
                                        }
                                    } else {
                                        if (file_exists($destino)) {
                                            unlink($destino);
                                        }
                                    }
                            }
        
                        $a++;
                    }
                }
        }

        public function thumbnail($directory, $image, $x, $y)
        {
            $value = explode(".", $image);
            $ext = strtolower(array_pop($value));

            //Define o nome do novo thumbnail

            $thumbnail = explode('.', $image);
            
            $thumbnail = $directory."/".$thumbnail[0].".".$ext;
            $image = $directory."/".$image;

            //Cria uma nova imagem da imagem original

            if ($ext == 'jpg' || $ext == 'jpeg'): $img_origem = imagecreatefromjpeg($image);
            elseif ($ext == 'png'): $img_origem = imagecreatefrompng($image);
            elseif ($ext == 'gif'): $img_origem = imagecreatefromgif($image);
            endif;

            //Recupera as dimensoes da imagem original

            $origem_x = imagesx($img_origem);
            $origem_y = imagesy($img_origem);

                //Se a imagem nao for proporcional ao thumbnail que se vai gerar
                //Pega a maior face e calcula a outra face proporcional a imagem original

                if ($origem_x > $origem_y): // Se a largura for maior que a altura
                    $final_x = $x; //A largura sera a do thumbnail
                    $final_y = floor($x * $origem_y / $origem_x); //Calculo a altura proporcional
                    $f_x = 0; //Posiciono a imagem no x = 0
                    $f_y = round(($y / 2) - ($final_y / 2)); //Centralizo a imagem no vertice y
                else: //Se a altura for maior ou igual a largura
                    $final_y = $y; //A altura sera a do thumbnail
                    $final_x = floor($y * $origem_x / $origem_y); //Calculo a largura proporcional
                    $f_y = 0; //Posiciono a imagem no x = 0
                    $f_x = round(($x / 2) - ($final_x / 2)); //Centralizo a imagem no vertice x
                endif;

            //Gero a nova imagem do thumbnail do tamanho $x X $y

            $img_final = imagecreatetruecolor($x, $y);

            //Copio a imagem original para a imagem do thumbnail utilizando os dados que foram calculados

            imagecopyresized($img_final, $img_origem, $f_x, $f_y, 0, 0, $final_x, $final_y, $origem_x, $origem_y);

                if ($ext == 'jpg' || $ext == 'jpeg') {
                    if (imagejpeg($img_final, $thumbnail, 50)) {
                        imageinterlace($img_final, 1);
                        imagedestroy($img_origem);
                        imagedestroy($img_final);

                        return true;
                    }
                } elseif ($ext == 'png') {
                    if (imagepng($img_final, $thumbnail)) {
                        imageinterlace($img_final, 1);
                        imagedestroy($img_origem);
                        imagedestroy($img_final);

                        return true;
                    }
                } elseif($ext == 'gif') {
                    if (imagegif($img_final, $thumbnail)) {
                        imageinterlace($img_final, 1);
                        imagedestroy($img_origem);
                        imagedestroy($img_final);

                        return true;
                    }
                } else {
                    return false;
                }
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