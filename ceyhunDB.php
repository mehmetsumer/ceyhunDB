<?php
  class sqlStatements{

    function __construct(){
      $this -> sql = "";
    }

    function host($host){
      $this -> sql .= 'mysql:host='.$host;
      return $this;
    }

    function dbname($dbname){
      $this -> sql .= ';dbname='.$dbname;
      return $this;
    }

    function database($user, $pass){
      try {
        $db = new PDO($this -> sql, $user, $pass);
        $db -> exec("SET NAMES 'utf8'; SET CHARSET 'utf8'");

        $this -> sql = "";

        return $db;
      } catch (PDOException $e) {
        echo 'SQL Exception -> '.$e;
      }
    }

    function select($column){
      $this -> sql .= 'SELECT '.$column.' ';
      return $this;
    }

    function as($title){
      $this -> sql.= 'as '.$title.' ';
    }

    function from($table){
      $this -> sql .= 'FROM '.$table;
      return $this;
    }

    function where($key, $mark, $val){
      $this -> sql.=' WHERE '.$key.' '.$mark.' '.$val;
      return $this;
    }

    function _where($key, $mark, $val){
      $this -> sql.=' AND '.$key.' '.$mark.' '.$val;
      return $this;
    }

    function order($column,$sort){
      $this -> sql .= ' ORDER BY '.$column.' '.$sort;
      return $this;
    }

    function limit($start, $finish){
      $this -> sql .= ' LIMIT '.$start.', '.$finish;
      return $this;
    }

    function fetch(){
      global $db;

      $sql = $this -> sql;
      $this -> sql = "";

      return $db -> query(htmlspecialchars($sql)) -> fetch(PDO::FETCH_ASSOC);
    }

    function fetchAll(){
      global $db;

      $sql = $this -> sql;
      $this -> sql = "";

      return $db -> query(htmlspecialchars($sql)) -> fetchAll(PDO::FETCH_ASSOC);
    }

    function insert($table, $data){

      global $db;

      $datas = [];

      foreach ($data as $key => $value) {
        $column .= $key.',';
        $values .= '?,';

        array_push($datas, $value);
      }

      $column = trim($column, ',');
      $values = trim($values, ',');

      $prepare = $db -> prepare(htmlspecialchars('INSERT INTO '.$table.'('.$column.') VALUES('.$values.')'));

      for ($i=0; $i < count($datas); $i++)$prepare -> bindParam($i + 1, $datas[$i], PDO::PARAM_STR);

      $prepare -> execute();

    }

    function delete($table){
      $this -> sql .= 'DELETE FROM '.$table;
      return $this;
    }

    function query(){
      global $db;

      $sql = $this -> sql;
      $this -> sql = "";

      $query = $db -> query(htmlspecialchars($sql));

      $query -> execute();
    }

    function update($table, $set){
      global $db;

      $datas = [];

      foreach ($set as $key => $value) {
        $column .= $key.' = ?, ';
        array_push($datas, $value);
      }

      $column = trim($column, ', ');

      $sql = 'UPDATE '.$table.' SET '.$column.($this -> sql);
      $this -> sql = "";

      $prepare = $db -> prepare(htmlspecialchars($sql));

      for ($i=0; $i < count($datas); $i++)
        $prepare -> bindParam($i + 1, $datas[$i], PDO::PARAM_STR);

      $prepare -> execute();
    }

    function count($as){
      global $db;

      $sql = $this -> sql;
      $this -> sql = "";

      return $db -> query(htmlspecialchars($sql)) -> fetch(PDO::FETCH_ASSOC)[$as];
    }

    function all(){
      return $this -> sql;
    }

  }

 ?>
