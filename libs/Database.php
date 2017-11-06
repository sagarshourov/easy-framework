<?php

class Database extends PDO {

    public function __construct($DB_TYPE, $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS) {
        parent::__construct($DB_TYPE . ':host=' . $DB_HOST . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

        //parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTIONS);
    }

    public function uploadfile($uploads_dir, $files, $pa_id = FALSE) {
        $insertedphotoid = array();
        $insertedphotoname = array();
        $publicurls = array();
        $date = date("Y-m-d");
       // print_r($files);
        foreach (array_keys($files) as $value) {
  
            if (is_array($files["$value"]["error"])){
                foreach ($files["$value"]["error"] as $key => $error) {
                    $name = $files["$value"]["name"][$key];
                    $title = explode(".", $name);
                    $ext = strtolower(strrchr($name, '.'));
                    $random = rand(23456789, 98765432);

                    if ($error == UPLOAD_ERR_OK) {
                        $tmp_name = $files["$value"]["tmp_name"][$key];
                        $name = $files["$value"]["name"][$key];
                        $filename = date("Ymdhis") . $name;
                        $filename = filter_var($filename, FILTER_SANITIZE_URL);
                        move_uploaded_file($tmp_name, $uploads_dir . "/" . $filename);
                        $publicurl = date("Ymdhis") . $random;
                        $data = array('title' => $title[0], 'src' => $filename, 'publicurl' => $publicurl, 'privacy' => '1', 'uploaddate' => $date, 'parent_id' => $pa_id);
                        $this->insert('file', $data);
                        array_push($insertedphotoid, $this->lastInsertId());
                        array_push($insertedphotoname, $filename);
                        array_push($publicurls, $publicurl);
                    }
                }
            }
        }
        return array('publicurl' => $publicurls, 'photoid' => $insertedphotoid, 'photoname' => $insertedphotoname);
    }

 
    public function combinearray($post) {
        $array_mix_length = 0;
        foreach ($post as $key => $value) {
            $array_mix_length = count($post[$key]);
        }
        $index_array_length = count($post);
        $return_array = array();
        $array_index = array();
        $j = 0;
        foreach ($post as $key => $value) {
            $array_index[$j] = $key;
            $j++;
        }
        for ($index = 0; $index < $array_mix_length; $index++) {
            $temp_array = array();
            for ($j = 0; $j < $index_array_length; $j++) {
                $temp_array[$array_index[$j]] = $post[$array_index[$j]][$index];
            }
            $return_array[$index] = $temp_array;
        }
        return $return_array;
    }

    public function get_one($cloumn, $table, $where, $id) {
        $result = $this->select("SELECT * FROM $table WHERE $where=:id ", (array('id' => $id)));
        if (!empty($result)) {
            return $result[0][$cloumn];
        } else {
            return FALSE;
        }
    }

    public function get_match($cloumn, $table, $where, $id) {
        $result = $this->select("SELECT * FROM $table WHERE $where LIKE '%$id%'", (array('id' => $id)));
        if (!empty($result)) {
            return $result[0][$cloumn];
        } else {
            return FALSE;
        }
    }

    public function countjessonarray($value) {
        $jsondecode = json_decode($value, TRUE);
        return count($jsondecode);
    }

    function update_array($insertValue, $table, $cloum, $Tcolum, $Tid) {
        $getOldValue = $this->get_one($cloum, $table, $Tcolum, $Tid);
        if ($getOldValue) {
            $getOldUnserializeValue = unserialize($getOldValue);
            $getOldMergeValue = array_merge($getOldUnserializeValue, $insertValue);
            $getOldSerializeValue = serialize($getOldMergeValue);
            $this->updatee($getOldSerializeValue, $cloum, $table, $Tcolum, $Tid);
        } else {
            $getOldSerializeValue = serialize($insertValue);
            $this->updatee($getOldSerializeValue, $cloum, $table, $Tcolum, $Tid);
        }
        return $getOldSerializeValue;
    }

    function update_delete_array($insertValue, $table, $cloum, $Tcolum, $Tid) {
        $getOldValue = $this->get_one($cloum, $table, $Tcolum, $Tid);

        if ($getOldValue) {
            $getOldUnserializeValue = unserialize($getOldValue);
            $getOldFlipValue = array_flip($getOldUnserializeValue);
            unset($getOldFlipValue[$insertValue]);
            $getNewFlipValue = array_flip($getOldFlipValue);

            $getNewUnserializeValue = serialize($getNewFlipValue);
            $this->updatee($getNewUnserializeValue, $cloum, $table, $Tcolum, $Tid);
        }
        return TRUE;
    }

    function update_jason_array($insertValue, $table, $cloum, $Tcolum, $Tid) {
        $getOldValue = $this->get_one($cloum, $table, $Tcolum, $Tid);
        if ($getOldValue) {
            $getOldUnserializeValue = json_decode($getOldValue);
            $getOldMergeValue = array_merge($getOldUnserializeValue, $insertValue);
            $getOldSerializeValue = json_encode($getOldMergeValue);
            $this->updatee($getOldSerializeValue, $cloum, $table, $Tcolum, $Tid);
        } else {
            $getOldSerializeValue = json_encode($insertValue);
            $this->updatee($getOldSerializeValue, $cloum, $table, $Tcolum, $Tid);
        }
        return $getOldSerializeValue;
    }

    function update_repeat_jason_array($insertValue, $table, $cloum, $Tcolum, $Tid) {
        $getOldValue = $this->get_one($cloum, $table, $Tcolum, $Tid);
        if ($getOldValue) {
            $getOldUnserializeValue = json_decode($getOldValue);
            if (!in_array($insertValue, $getOldUnserializeValue)) {
                $getOldMergeValue = array_merge($getOldUnserializeValue, array($insertValue));
                $getOldSerializeValue = json_encode($getOldMergeValue);
                $this->updatee($getOldSerializeValue, $cloum, $table, $Tcolum, $Tid);
            }
        } else {
            $getOldSerializeValue = json_encode(array($insertValue));
            $this->updatee($getOldSerializeValue, $cloum, $table, $Tcolum, $Tid);
        }
        return 1;
    }

    public function update_multi_jason_array($table, $cloum, $Tcolum, $Tid, $index = false, $value = false) {
        $data = array();
        $all = $this->get_one($cloum, $table, $Tcolum, $Tid);

        if ($all) {
            $data = json_decode($all, true);
        }
        $data[$index] = $value;

        $this->updatee(json_encode($data), $cloum, $table, $Tcolum, $Tid);
    }

    function update_jason_delete_array($insertValue, $table, $cloum, $Tcolum, $Tid) {
        $getOldValue = $this->get_one($cloum, $table, $Tcolum, $Tid);

        if ($getOldValue) {
            $getOldUnserializeValue = json_decode($getOldValue);
            $getOldFlipValue = array_flip($getOldUnserializeValue);
            unset($getOldFlipValue[$insertValue]);
            $getNewFlipValue = array_flip($getOldFlipValue);

            $getNewUnserializeValue = json_encode($getNewFlipValue);
            $this->updatee($getNewUnserializeValue, $cloum, $table, $Tcolum, $Tid);
        }
        return TRUE;
    }

    public function updatee($value, $cloumn, $table, $where, $id) {
        $postData = array(
            $cloumn => $value
        );

        $this->update($table, $postData, "`$where` = '$id'");
    }

    /**
     * select
     * @param string $sql An SQL string
     * @param array $array Paramters to bind
     * @param constant $fetchMode A PDO Fetch mode
     * @return mixed
     */
    public function select($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC) {
        $sth = $this->prepare($sql);
        foreach ($array as $key => $value) {
            $sth->bindValue("$key", $value);
        }

        $sth->execute();
        return $sth->fetchAll($fetchMode);
    }

    /**
     * insert
     * @param string $table A name of table to insert into
     * @param string $data An associative array
     */
    public function insert($table, $data) {
        ksort($data);

        $fieldNames = implode('`, `', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));

        $sth = $this->prepare("INSERT INTO $table (`$fieldNames`) VALUES ($fieldValues)");

        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }

        $sth->execute();
        return $this->lastInsertId();
    }

    /**
     * update
     * @param string $table A name of table to insert into
     * @param string $data An associative array
     * @param string $where the WHERE query part
     */
    public function update($table, $data, $where) {
        ksort($data);

        $fieldDetails = NULL;
        foreach ($data as $key => $value) {
            $fieldDetails .= "`$key`=:$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');

        $sth = $this->prepare("UPDATE $table SET $fieldDetails WHERE $where");

        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }

        $sth->execute();
    }

    /**
     * delete
     * 
     * @param string $table
     * @param string $where
     * @param integer $limit
     * @return integer Affected Rows
     */
    public function delete($table, $where, $limit) {
        return $this->exec("DELETE FROM $table WHERE $where LIMIT $limit");
    }

    public function csdelete($table, $where) {
        return $this->exec("DELETE FROM $table WHERE $where");
    }

   
    function time($ptime) {
        $etime = time() - $ptime;
        if ($etime < 1) {
            return '0 seconds';
        }
        $a = array(12 * 30 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'minute',
            1 => 'second'
        );
        foreach ($a as $secs => $str) {
            $d = $etime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
            }
        }
    }

}
