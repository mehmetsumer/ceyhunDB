<?php
  class sqlStatements{

    private $sql;

    function __construct(){
      $this -> sql = "";
    }

    function select($column){
      $this -> sql .= 'SELECT '.$column.' FROM ';
      return $this;
    }

    function from($table){
      $this -> sql .= $table;
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

    function select_dml($table, $size, $extra = NULL){

      global $db;

      if($size == 'fetch')
        return $select = $db -> query(htmlspecialchars("SELECT * FROM {$table} {$extra}")) -> fetch(PDO::FETCH_ASSOC);
      if($size == 'fetchall')
       return $select = $db -> query(htmlspecialchars("SELECT * FROM {$table} {$extra}")) -> fetchAll(PDO::FETCH_ASSOC);

    }

    function insert_dml($table, $data){

      global $db;

      foreach ($data as $key => $value) {
        $column .= $key.',';
        $values .= '?,';
      }

      $column = trim($column, ',');
      $values = trim($values, ',');

      $prepare = $db -> prepare(htmlspecialchars('INSERT INTO '.$table.'('.$column.') VALUES('.$values.')'));

      $datas = [];
      foreach ($data as $key => $value) array_push($datas, $value);

      for ($i=0; $i < count($datas); $i++)$prepare -> bindParam($i + 1, $datas[$i], PDO::PARAM_STR);

      $prepare -> execute();

    }

    function update_dml($table, $data, $extra = NULL){

      global $db;

      foreach ($data as $key => $value) $column .= $key.' = ?, ';

      $column = trim($column, ', ');

      $prepare = $db -> prepare(htmlspecialchars('UPDATE '.$table.' SET '.$column.' '.$extra));

      $datas = [];
      foreach ($data as $key => $value) array_push($datas, $value);

      for ($i=0; $i < count($datas); $i++) $prepare -> bindParam($i + 1, $datas[$i], PDO::PARAM_STR);

      $prepare -> execute();

    }

    function delete_dml($table, $extra){

      global $db;

      $query = $db -> query(htmlspecialchars('DELETE FROM '.$table.' '.$extra));

      $query -> execute();

    }

    function clear(){
      $this -> sql = "";
      return $this -> sql;
    }

    function all(){
      return $this -> sql;
    }

    function database($host, $name, $user, $pass){
      try {
        $db = new PDO(
            'mysql:host='.$host.
            ';dbname='.$name,
            $user,
            $pass
          );
        $db -> exec("SET NAMES 'utf8'; SET CHARSET 'utf8'");
        return $db;
      } catch (PDOException $e) {
        echo 'SQL Exception';
      }
    }

  }
?>
