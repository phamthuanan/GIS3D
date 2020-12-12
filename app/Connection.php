<?php

class Connection
{
    private static function create_connection()
    {
        global $db_host, $db_user, $db_pass, $db_name;
        return new PDO ("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    }

    public static function query($sql, $params = array(), $key = "")
    {
        $conn = self::create_connection();
        try {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $result = array();
            while ($attrib = $stmt->fetch()) {
                if (!empty($key)) {
                    if (!array_key_exists($attrib[$key], $result)) {
                        $result[$attrib[$key]] = array();
                    }
                } else {
                    $result[] = $attrib;
                }
            }
            return $result;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}