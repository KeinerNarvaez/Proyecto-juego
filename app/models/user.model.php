<?php
require_once "./app/config/connection.php";
class user extends connection
{    public static function showData(){
        try{
            $sql = "SELECT * FROM user";
            $stmt = connection::getConnection()->prepare($sql);
            $stmt = $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        }catch(PDOException $th){
            echo $th->getMessage();
            }
}
    public static function getData($userID){
        try{
            $sql = "SELECT * FROM user WHERE userID = :userID";
            $stmt = connection::getConnection()->prepare($sql);
            $stmt->bindParam(':userID', $userID);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result;
        }catch(PDOException $th){
            echo $th->getMessage();
            }
}
    public static function saveData($data){
        try{
            $sql = "INSERT INTO user (avatarID, accountActivationID, name, lastName, gamerTag) VALUES (:avatarID, :accountActivationID, :name, :lastName, :gamerTag)";
            $stmt = connection::getConnection()->prepare($sql);
            $stmt->bindParam(':accountActivationID', $data['accountActivationID']);
            $stmt->bindParam(':avatarID', $data ['avatarID']);
            $stmt->bindParam(':name', $data ['name']);
            $stmt->bindParam(':lastName', $data ['lastName']);
            $stmt->bindParam(':gamerTag', $data['gamerTag']);
            $stmt->execute();
            return true;
        }catch(PDOException $th){
            echo $th->getMessage();
            }
    }
    public static function updateData($data){
        try{
            $sql = "UPDATE user SET 
            avatarID = :avatarID, 
            accountActivationID = :accountActivationID, 
            name = :name, 
            lastName = :lastName, 
            gamerTag = :gamerTag WHERE userID = :userID";
            $stmt = connection::getConnection()->prepare($sql);
            $stmt->bindParam(':userID', $data['userID']);
            $stmt->bindParam(':avatarID', $data['avatarID']);
            $stmt->bindParam(':accountActivationID', $data['accountActivationID']);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':lastName', $data['lastName']);
            $stmt->bindParam(':gamerTag', $data['gamerTag']);
            $stmt->execute();
            return true;
}catch(PDOException $th){
    echo $th->getMessage();
}
}
    public static function deleteData($userID){
        try{
            $sql = "DELETE FROM user WHERE userID = :userID";
            $stmt = connection::getConnection()->prepare($sql);
            $stmt->bindParam(':userID', $userID);
            $stmt->execute();
            return true;
        }catch(PDOException $th){
            echo $th->getMessage();
        }
    }

}
