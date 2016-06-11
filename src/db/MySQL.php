<?php
/**
 * Created by PhpStorm.
 * User: Seth
 * Date: 11/06/16
 * Time: 9:48 PM
 */
namespace Forum\db;

use Forum\db\MySQLResult;

class MySQL
{
    var  $host ;
    var  $dbUser ;
    var  $dbPass ;
    var  $dbName ;
    var  $dbConn ;
    var  $dbconnectError ;

    function __construct ( $host , $dbUser , $dbPass , $dbName )
    {
        $this->host   = $host ;
        $this->dbUser = $dbUser ;
        $this->dbPass = $dbPass ;
        $this->dbName = $dbName ;
        $this->connectToServer() ;
    }


    function connectToServer()
    {
        $this->dbConn = mysqli_connect($this->host , $this->dbUser , $this->dbPass )  ;
        if ( !$this->dbConn )
        {
            trigger_error ('could not connect to server' ) ;
            $this->dbconnectError = true ;
        }
        else
        {
            //echo "connected to server <br />";
        }

    }

    function selectDatabase(){
        if (!mysqli_select_db ( $this->dbConn, $this->dbName ) )
        {
            echo "error";
            trigger_error ('could not select database' ) ;
            $this->dbconnectError = true ;
        }
        else
        {
            //echo "Database selected!";
        }
    }


    function dropDatabase() {
        return( $this->execute( "drop database $this->dbName"  ) );
    }


    function createDatabase(){
        return( $this->query("create database $this->dbName" ) );
    }

    function isError() {
        if  ( $this->dbconnectError ) {
            return true ;
        }
        $error = mysqli_error ( $this->dbConn ) ;
        if (empty ($error)) {
            return false ;
        } else  {
            return true ;
        }
    }

    function query ( $sql )
    {
        if (!$queryResource = mysqli_query ( $this->dbConn,  $sql ))
        {
            trigger_error ( 'Query Failed: ' . mysqli_error ($this->dbConn ) . ' SQL: ' . $sql ) ;
            return false;
        }

        return new MySQLResult( $this, $queryResource ) ;
    }


    function execute ( $sql ) {
        if (!$queryResource = mysqli_query($this->dbConn, $sql)) {
            throw new exception (
                'Query Failed: ' . mysqli_error ($this->dbConn ) . ' SQL: ' . $sql );
        }
        return new MySQLResult( $this, $queryResource ) ;
    }

}