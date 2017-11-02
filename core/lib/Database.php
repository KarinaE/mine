<?php
defined('_ACCESS') or die;

class Database
{
    private static $instance;
    private $handle;
    private $cfg;
    private $debugMode = 2;

    const RETURN_DATA_ASSOC = 1;
    const RETURN_DATA_NUM = 2;

    const RETURN_DATA_OBJ = 3;
    const RETURN_DATA_ARR = 4;

    const ENCODE_HTML = 5;

    public static function instance($remote = false)
    {     
      if (!self::$instance || $remote != false)
      {
        self::$instance = new self($remote);
      }

      return self::$instance;
    }

    private function __construct($remote = false)
    {
      $this->cfg = Settings::instance()->getParam($remote != false ? $remote : 'db');

      if (!$this->cfg) die ('Database::Configuration not found');
      
      $charset   = isset($this->cfg['charset']) ? $this->cfg['charset'] : 'utf8';

      require_once 'lib/adodb5/adodb.inc.php';
      $ADODB_QUOTE_FIELDNAMES = true;;
      
      $this->handle = ADONewConnection($this->cfg['driver']);
      $this->handle->SetFetchMode(ADODB_FETCH_ASSOC);
      $this->handle->debug = false;

      $this->handle->Connect($this->cfg['host'], $this->cfg['user'], $this->cfg['pass'], $this->cfg['base_name']);
			
      $this->handle->Execute('set names ' . $charset);
      $this->printError();
    }
  

    /**
     * Получить массив данных в виде $key => $value
     * @param string $table
     * @param string $fields [optional]
     * @param string $condition [optional]
     * @param string $returnType [optional] тип возвращаемого массива (RETURN_DATA_ASSOC - ассоц., RETURN_DATA_NUM - нумеративный)
     * @param string $returnData [optional] тип возвращаемых данных (RETURN_DATA_OBJ - обьект, RETURN_DATA_ARR - массив)
     * @param string $encode_html [optional] заменяет html его кодовым аналогом
     * @return array
     */
    public function select($table, $fields = '*', $condition = '', $returnType = self::RETURN_DATA_NUM, $returnData = self::RETURN_DATA_OBJ, $encode_html = false)
    {
      $sql = 'SELECT ' . $fields . ($table ? ' FROM ' . $this->cfg['pref'] . $table : '') . ' ' . $condition;
      return $this->proccess($sql, $returnType, $returnData, $encode_html);
    }

    /**
     * Получить массив данных в виде $key => $value
     * @param string $sql query
     * @param string $returnType [optional] тип возвращаемого массива (RETURN_DATA_ASSOC - ассоц., RETURN_DATA_NUM - нумеративный)
     * @param string $returnData [optional] тип возвращаемых данных (RETURN_DATA_OBJ - обьект, RETURN_DATA_ARR - массив)
     * @param string $encode_html [optional] заменяет html его кодовым аналогом
     * @return array
     */
    public function select_full($sql, $returnType = self::RETURN_DATA_NUM, $returnData = self::RETURN_DATA_OBJ, $encode_html = false)
    {
        var_dump($sql); 
      return $this->proccess($sql, $returnType, $returnData, $encode_html);
    }

    /**
     * Обновляет записи в таблице
     * @param string $table
     * @param array $data Массив данных для обновления в виде array("поле" => "значение")
     * @param string $condition  условие (без WHERE)
     * @return bool
     */
    public function update($table, $data, $condition)
    {
      $out = false;

      if (is_array($data) && sizeof($data))
      {
        $res = $this->handle->AutoExecute($this->cfg['pref'] . $table, $data, 'UPDATE', preg_replace('/^where/i', '', $condition));

        $this->printError();

        if ($res === true) $out = true;
      }

      return $out;
    }

    /**
     * Добавлет записи в таблицу
     * @param string $table
     * @param array $data Массив данных для обновления в виде array("поле" => "значение")
     * @param bool $returnID  Вернуть автоматически сгенерированный ключ добавленной записи
     * @param bool $autoUpdate  При совпадении уникальных ключей, обновит сууществующую строку
     * @return mixed
     */
    public function insert($table, $data, $returnID = false, $autoUpdate = false)
    {
      $out = false;

      if (is_array($data) && sizeof($data))
      {
        if (!$autoUpdate)
        {
          $res = $this->handle->AutoExecute($this->cfg['pref'] . $table, $data, 'INSERT');
        }
        else
        {
          $upd_vals = array();

          foreach ($data as $k => $v)
            $upd_vals[] = '`' . $k . '` = VALUES(`' . $k . '`)';

          $sql = 'INSERT INTO `' . $table . '` (`' . implode('`,`', array_keys($data)) . '`) VALUES (' . substr(str_repeat('?,', count($data)),0,-1) . ') ON DUPLICATE KEY UPDATE ' . implode(',', $upd_vals);

          $res = $this->handle->Execute($sql, $data);
        }
        $this->printError();

        if ($res)
        {
          $out = true;

          if ($returnID)
            $out = $this->handle->Insert_ID();
        }
      }

      return $out;
    }

    /**
     * Заменяет данные по ключу
     * @param string $table
     * @param array $data Массив данных для обновления в виде array("поле" => "значение")
     * @param string $key ключ для замены
     * @return mixed
     */
    public function replace($table, $data, $key)
    {
      $out = false;

      if (is_array($data) && sizeof($data))
      {
        $res = $this->handle->Replace($table, $data, $key, $autoquote = true);
        $this->printError();

        if ($res)
        {
          $out = true;
        }
      }

      return $out;
    }

    /**
     * Удаляет запись из таблицы
     * @param string $table
     * @param string $condition условие
     * @return bool
     */
    public function delete($table, $condition)
    {
      $sql = 'DELETE FROM ' . $this->cfg['pref'] . $table . ' ' . $condition;

      $res = $this->handle->Execute($sql);
      $this->printError();

      return $res ? true : false;
    }
    
    public function query($sql)
    {
      $res = $this->handle->Execute($sql);
      $this->printError();

      return $res;
    }

    public function num_rows($result)
    {
        if (is_object($result) && method_exists($result, 'RecordCount'))
        {
            return $result->RecordCount();
        }

        return null;
    }
    
    private function proccess($sql, $returnType, $returnData, $encode_html)
    {
      $out = false;

      if ($sql)
      {
        $rows = $this->handle->Execute($sql);
        $this->printError();
        
        if ($rows && $rows->RecordCount())
        {
          $out = $returnData == self::RETURN_DATA_OBJ ? $this->get_data_object($rows, $returnType, $encode_html) : $this->get_data_array($rows, $returnType, $encode_html);
        }
      }
      
      
      return $out;
    }

    // Получает данные и возвращает их в массиве
    private function get_data_array($dataSet, $returnType, $encode_html)
    {
      $res = $dataSet->GetArray();

      $newarr = array();
      foreach ($res as $num => $arr)
      {
        foreach ($arr as $k => $v)
        {
          if ($encode_html === self::ENCODE_HTML && is_string($v))
            $v = filter_var($v, FILTER_SANITIZE_SPECIAL_CHARS);

          if ($returnType === self::RETURN_DATA_ASSOC)
          {
            if (!isset ($newarr[$k])) $newarr[$k] = array();
            $newarr[$k][] = $v;
          }
          else
            $newarr[$num][$k] = $v;
        }
      }

      return $newarr;
    }

    // Получает данные и возвращает их в виде обьекта
    private function get_data_object($dataSet, $returnAsooc, $encode_html)
    {
      $arr = array();
      $i = 0;
      
      while($row = $dataSet->FetchNextObject(false))
      {
				$obj = new stdClass;
				
        foreach ($row as $key => $val)
        {
          if ($encode_html === self::ENCODE_HTML && is_string($val))
            $val = filter_var($val, FILTER_SANITIZE_SPECIAL_CHARS);

					$obj->$key = $val;
        }
				$arr[$i++] = $obj;
      }
      return $arr;
    }


    private function printError()
    {
      if ($this->handle->ErrorMsg())
      {
        switch ($this->debugMode)
        {
          case 1:
            die($this->handle->ErrorMsg());
            break;
          case 2:
            echo '<pre>' . $this->handle->ErrorMsg() . '</pre>';
            break;
        }
      }
    }
}
