<?php

class PdoHold
{
    public static $pdo;
    public static $tablename;
    public static $keys = array();
    
    public function getPdo() {
        if(isset(self::$pdo)) {
            return self::$pdo;
        } else {
            return null;
        }
    }
    
    public function getTablename() {
        if(isset(self::$tablename)) {
            return self::$tablename;
        } else {
            return null;
        }
    }
    
    public function getKeys() {
        if(isset(self::$keys['key'])) {
            return self::$keys['key'];
        } else {
            return null;
        }
    }

    public function getParams() {
        if(isset(self::$keys['param'])) {
            return self::$keys['param'];
        } else {
            return null;
        }
    }

}

class DataBox
{
    protected $data = array();

    public function __set($name,$value) {
        $this->data[$name] = $value;
    }

    public function __get($name) {
        if(array_key_exists($name,$this->data)) {
            return $this->data[$name];
        } else {
            return null;
        }
    }

    public function __isset($name) {
        return isset($this->data[$name]);
    }
}

class ActiveRecord extends DataBox
{
    public function connectPdo($dbname,$tablename,$username,$password) {
        try {
            PdoHold::$pdo = new PDO(
                sprintf("mysql:dbname=%s;host=localhost;charset=utf8",$dbname),
                $username,
                $password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false,
                )
            );
            PdoHold::$tablename = $tablename;
            $pdo = new PdoHold();
            $sql = "SHOW COLUMNS FROM {$pdo->getTablename()}";
            $stmt = $pdo->getPdo()->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            PdoHold::$keys['key'] = array();
            PdoHold::$keys['param'] = array();
            foreach($stmt as $row) {
                PdoHold::$keys['key'][] = $row['Field'];
                PdoHold::$keys['param'][] = $this -> varToPdo($row['Type']);
            }
            $data = array();
        }
        catch(Exception $e) {
            $error = $e->getMessage();
            echo($error);
        }
    }

    private function varToPdo($vartype) {
        if (strpos($vartype,'int') !== false) {
            return PDO::PARAM_INT;
        } else {
            return PDO::PARAM_STR;
        }
    }
    
    private function keyToParam($key) {
        $pdo = new PdoHold();
        return $pdo->getParams()[array_search($key,$pdo->getKeys())];
    }

    public function isValid() {
        $pdo = new PdoHold();
        foreach ($pdo -> getKeys() as $key) {
            if(!isset($this -> data[$key])) {
                return false;
            }
        }
        return true;
    }

    public function isEmpty() {
        $pdo = new PdoHold();
        foreach ($pdo -> getKeys() as $key) {
            if(empty($this -> data[$key])) {
                return true;
            }
        }
        return false;
    }

    public function save() {
        if (!$this -> isValid()) {
            throw new Exception('不正です');
        }
        if ($this -> isEmpty()) {
            throw new Exception('いずれかのメンバが空です');
        }
        try{
            $pdo = new PdoHold();
            $sql = "INSERT INTO {$pdo->getTablename()} VALUES (";
            foreach ($pdo -> getKeys() as $key) {
                $sql = $sql.":{$key},";
            }
            $sql = substr($sql,0,strlen($sql)-1).')';
            $stmt = $pdo -> getPdo() -> prepare($sql);
            foreach ($pdo -> getKeys() as $key) {
                $stmt -> bindValue(":{$key}",($this -> keyToParam($key) === PDO::PARAM_INT)?(int)$this -> data[$key]:$this -> data[$key],$this -> keyToParam($key));
            }
            $stmt -> execute();
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function find($value) {
        $pdo = new PdoHold();
        $sql = "SELECT * FROM {$pdo->getTablename()} WHERE {$pdo->getKeys()[0]} = :value";
        $stmt = $pdo->getPdo()->prepare($sql);
        $stmt->bindValue(':value',$value,$pdo -> getParams()[0]);
        $stmt->execute();
        $blog = $stmt->fetch(PDO::FETCH_ASSOC);
        $result = new ActiveRecord();
        foreach($pdo->getKeys() as $key) {
            $result->$key = $blog[$key];
        }
        return $result;
    }
    
    public function pickout($begin,$take) {
        $pdo = new PdoHold();
        $sql = "SELECT * FROM {$pdo->getTablename()} WHERE {$pdo->getKeys()[0]} > :begin ORDER BY {$pdo->getKeys()[0]} LIMIT :take";
        $stmt = $pdo->getPdo()->prepare($sql);
        $stmt->bindValue(':begin',$begin,PDO::PARAM_INT);
        $stmt->bindValue(':take',$take,PDO::PARAM_INT);
        $stmt->execute();
        $result = array();
        for ($i=0; $i < $take; $i++) {
            $blog = $stmt->fetch(PDO::FETCH_ASSOC);
            $result[$i] = new ActiveRecord();
            foreach($pdo->getKeys() as $key) {
                $result[$i]->$key = $blog[$key];
            }
        }
        return $result;
    }
    
    public function findFromKey($inputkey,$value) {
        $pdo = new PdoHold();
        if(!in_array($inputkey,$pdo->getKeys())) {
            throw new Exception('キーがないよ');
        }
        $sql = "SELECT * FROM {$pdo->getTablename()} WHERE {$inputkey} = :value";
        $stmt = $pdo->getPdo()->prepare($sql);
        $stmt->bindValue(':value',$value,$this -> keyToParam($inputkey));
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = array();
        $i = 0;
        foreach ($stmt as $row) {
            $result[$i] = new ActiveRecord();
            foreach($pdo->getKeys() as $key) {
                $result[$i]->$key = $row[$key];
            }
            $i++;
        }
        return $result;
    }

    public function findLike($inputkey,$value) {
        $pdo = new PdoHold();
        if(!in_array($inputkey,$pdo->getKeys())) {
            throw new Exception('キーがないよ');
        }
        $sql = "SELECT * FROM {$pdo->getTablename()} WHERE {$inputkey} LIKE :value";
        $stmt = $pdo->getPdo()->prepare($sql);
        $stmt->bindValue(':value',$value,$this -> keyToParam($inputkey));
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = array();
        $i = 0;
        foreach ($stmt as $row) {
            $result[$i] = new ActiveRecord();
            foreach($pdo->getKeys() as $key) {
                $result[$i]->$key = $row[$key];
            }
            $i++;
        }
        return $result;
    }
    
    public function getValueList($inputkey) {
        $pdo = new PdoHold();
        if(!in_array($inputkey,$pdo->getKeys())) {
            throw new Exception('キーがないよ');
        }
        $sql = "SELECT DISTINCT {$inputkey} FROM {$pdo->getTablename()}";
        $stmt = $pdo->getPdo()->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = array();
        foreach($stmt as $row) {
            $result[] = $row[$inputkey];
        }
        return $result;
    }
    
    public function size() {
        $pdo = new PdoHold();
        $sql = "SELECT COUNT(*) FROM {$pdo->getTablename()}";
        $stmt = $pdo->getPdo()->prepare($sql);
        $stmt->execute();
        $size = $stmt->fetchColumn();
        return $size;
    }

    public function delete($value) {
        $pdo = new PdoHold();
        $sql = "DELETE FROM {$pdo->getTablename()} WHERE {$pdo->getKeys()[0]} = :value";
        $stmt = $pdo->getPdo()->prepare($sql);
        $stmt->bindValue(':value',$value,$pdo -> getParams()[0]);
        $stmt->execute();
    }
}
