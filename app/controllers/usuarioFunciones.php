<?php

        function generateCode(){
            return md5(uniqid(mt_rand(), false));
        }

        function registerUser(array $dato, $connect){
            $sql = $connect->prepare("INSERT INTO user (name, lastName) VALUES (?,?)");
            if ($sql->execute($dato)){
                return $connect->lastInsertId();
            }
            return 0;
            
        }
        function registerLogin(array $dato, $connect){
            $sql = $connect->prepare("INSERT INTO login (userId, password, email) VALUES (?,?,?)");
            if ($sql->execute($dato)){
                return true;
            }
            return false;
        }

        function registerCode(array $dato, $connect){
            $sql = $connect->prepare("INSERT INTO accountactivation (activationcode) VALUES (?)");
            if ($sql->execute($dato)){
                return true;
            }
            return false;
        }



?>