<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 11/06/16
 * Time: 9:56 PM
 */
namespace Forum\db;

class CommonFunctions
{

    function getConnection()
    {
        $host = 'localhost';
        $dbUser = 'root';
        $dbPass = 'root';
        $dbName = 'SudokuCommunityForum';

        // create a new database object and connect to server
        $db = new MySQL($host, $dbUser, $dbPass, $dbName);
        return $db;
    }

    function getDatabase()
    {
        $db = $this->getConnection();
        $db->selectDatabase();
        return $db;
    }

// gets a parameter from the URL, or null if not specified
    function getFromURL($key)
    {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }
        return null;
    }

    function sqlSafe($input)
    {
        return mysqli_real_escape_string(stripslashes($input));
    }

    function getMemberID($username, $password)
    {
        $s = 'muchsaltiness';
        $u = sqlSafe($username);
        $p = sqlSafe($password);
        $h = hash('sha256', $p . $s);
        $db = getDatabase();
        $sql = "select memberID, passwordHash from members where login='$u'";
        $result = $db->query($sql);
        if ($result->size() == 1) {
            $row = $result->fetch();
            $hash = $row['passwordHash'];
            $id = $row['memberID'];
            if ($h == $hash) {
                return $id;
            }
            if ($hash == null || $hash == "") {
                $result = $db->query("UPDATE members SET passwordHash='$h' WHERE memberID=$id");
                return $id;
            }
        }
        return null;
    }
}