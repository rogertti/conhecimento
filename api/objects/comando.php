<?php
    class Comando
    {
        // database connection and table name

        private $conn;
     
        // object properties

        public $idcomando;
        public $idsistema;
        public $descricao;
        public $instrucao;
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

            $sql = $this->conn->prepare("SELECT comando.idcomando,sistema.descricao AS sistema,comando.descricao,comando.instrucao FROM comando INNER JOIN sistema ON comando.sistema_idsistema = sistema.idsistema WHERE comando.monitor = :monitor ORDER BY sistema.descricao,comando.descricao,comando.instrucao");
            $sql->bindParam(':monitor', $this->monitor, PDO::PARAM_STR);
            $sql->execute();

            return $sql;
        }

        // read record

        public function readSingle($idcomando)
        {
            $this->monitor = 'T';

            $sql = $this->conn->prepare("SELECT comando.idcomando,sistema.idsistema,sistema.descricao AS sistema,comando.descricao,comando.instrucao FROM comando INNER JOIN sistema ON comando.sistema_idsistema = sistema.idsistema WHERE comando.idcomando = :idcomando AND comando.monitor = :monitor ORDER BY sistema.descricao,comando.descricao,comando.instrucao");
            $sql->bindParam(':idcomando', $idcomando, PDO::PARAM_INT);
            $sql->bindParam(':monitor', $this->monitor, PDO::PARAM_STR);
            $sql->execute();

            return $sql;
        }

        // check by same record on database

        public function comandoInsertExist()
        {
            $sql = $this->conn->prepare("SELECT idcomando FROM comando WHERE sistema_idsistema = :idsistema AND descricao = :descricao");
            $sql->bindParam(':idsistema', $this->idsistema, PDO::PARAM_INT);
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

        public function comandoUpdateExist()
        {
            $sql = $this->conn->prepare("SELECT idcomando FROM comando WHERE sistema_idsistema = :idsistema AND descricao = :descricao AND idcomando <> :idcomando");
            $sql->bindParam(':idsistema', $this->idistema, PDO::PARAM_INT);
            $sql->bindParam(':descricao', $this->descricao, PDO::PARAM_STR);
            $sql->bindParam(':idcomando', $this->idcomando, PDO::PARAM_INT);
            $sql->execute();

                if ($sql->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }

            return $sql;
        }

        // insert record

        public function insert()
        {
            if ($this->comandoInsertExist()) {
                die('Esse comando j&aacute; est&aacute; cadastrado.');
            } else {
                $this->monitor = 'T';

                $sql = $this->conn->prepare("INSERT INTO comando (sistema_idsistema,descricao,instrucao,monitor) VALUES (:idsistema,:descricao,:instrucao,:monitor)");
                $sql->bindParam(':idsistema', $this->idsistema, PDO::PARAM_INT);
                $sql->bindParam(':descricao', $this->descricao, PDO::PARAM_STR);
                $sql->bindParam(':instrucao', $this->instrucao, PDO::PARAM_STR);
                $sql->bindParam(':monitor', $this->monitor, PDO::PARAM_STR);
                #$sql->execute();
                    if ($sql->execute()) {
                        $this->idcomando = $this->conn->lastInsertId();

                        if ($this->anexo) {
                            if (self::upload()) {
                                return $sql;
                            }
                        } else {
                            return $sql;
                        }
                    }
                #return $sql;
            }
        }

        // upload files

        public function upload()
        {
            $count = count($this->anexo['name']);

                if ($count > 0) {
                    $a = 1;
                    $dir = '../../anexo/';

                    for ($i = 0; $i < $count; $i++) {
                        $remete = $this->anexo['tmp_name'][$i];
                        $destino = $dir . $this->anexo['name'][$i];
        
                            if (move_uploaded_file($remete, $destino)) {
                                // rename the file
            
                                $py = md5($this->anexo['tmp_name'][$i]);
                                $ext = strrchr($this->anexo['name'][$i], '.');
            
                                    if (rename($destino, $dir . $py . $ext)) {
                                        if (($this->anexo['type'][$i] == 'image/jpg') or ($this->anexo['type'][$i] == 'image/jpeg') or ($this->anexo['type'][$i] == 'image/png')) {
                                            // change the size of image and insert on db
                
                                            $this->link = $py . $ext;
                                            #$th = self::thumbnail($dir, $this->link, 800, 600);
                                            #print_r($th);

                                            if (self::thumbnail($dir, $this->link, 800, 600)) {
                                                $sql = $this->conn->prepare("INSERT INTO arquivo (comando_idcomando,link) VALUES (:idcomando,:link)");
                                                $sql->bindParam(':idcomando', $this->idcomando, PDO::PARAM_INT);
                                                $sql->bindParam(':link', $this->link, PDO::PARAM_STR);
                    
                                                    if ($sql->execute()) {
                                                        if ($a == $count) {
                                                            return $sql;
                                                        }
                                                    }

                                                #$sql->closeCursor();
                                            }
                                        } else {
                                            $this->link = $py . $ext;
                
                                            $sql = $this->conn->prepare("INSERT INTO arquivo (comando_idcomando,link) VALUES (:idcomando,:link)");
                                            $sql->bindParam(':idcomando', $this->idcomando, PDO::PARAM_INT);
                                            $sql->bindParam(':link', $this->link, PDO::PARAM_STR);
                                                        
                                                if ($sql->execute()) {
                                                    if ($a == $count) {
                                                        return $sql;
                                                    }
                                                }

                                            #$sql->closeCursor();
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

            //background color
            //imagecolorallocate($img_final, 255, 255, 255);
            //imagefilter($img_final, IMG_FILTER_COLORIZE, 255, 255, 255);

            //Copio a imagem original para a imagem do thumbnail utilizando os dados que foram calculados

            imagecopyresized($img_final, $img_origem, $f_x, $f_y, 0, 0, $final_x, $final_y, $origem_x, $origem_y);

                //Salvo o novo thumbnail

                /*if ($ext == 'jpg' || $ext == 'jpeg'): imagejpeg($img_final, $thumbnail, 50);
                elseif ($ext == 'png'): imagepng($img_final, $thumbnail);
                elseif ($ext == 'gif'): imagegif($img_final, $thumbnail);
                endif;*/

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

            /*//progressive
            
            imageinterlace($img_final, 1);

            //Destruo as imagens que foram utilizadas
            
            imagedestroy($img_origem);
            imagedestroy($img_final);*/
        }

        // update record

        public function update()
        {
            if ($this->comandoUpdateExist()) {
                die('Esse comando j&aacute; est&aacute; cadastrado.');
            } else {
                $sql = $this->conn->prepare("UPDATE comando SET sistema_idsistema = :idsistema,descricao = :descricao,instrucao = :instrucao WHERE idcomando = :idcomando");
                $sql->bindParam(':idsistema', $this->idsistema, PDO::PARAM_INT);
                $sql->bindParam(':descricao', $this->descricao, PDO::PARAM_STR);
                $sql->bindParam(':instrucao', $this->instrucao, PDO::PARAM_STR);
                $sql->bindParam(':idcomando', $this->idcomando, PDO::PARAM_INT);
                #$sql->execute();
                    if ($sql->execute()) {
                        if ($this->anexo) {
                            if (self::upload()) {
                                return $sql;
                            }
                        } else {
                            return $sql;
                        }
                    }
                #return $sql;
            }
        }

        // delete record

        public function delete()
        {
            $this->monitor = 'F';

            $sql = $this->conn->prepare("UPDATE comando SET monitor = :monitor WHERE idcomando = :idcomando");
            $sql->bindParam(':monitor', $this->monitor, PDO::PARAM_STR);
            $sql->bindParam(':idcomando', $this->idcomando, PDO::PARAM_INT);
            $sql->execute();
                
            return $sql;
        }
    }
