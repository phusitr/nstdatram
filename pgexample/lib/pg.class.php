<?php
/*
PHP REST SQL: A HTTP REST interface to relational databases
written in PHP

postgresql.php :: PostgreSQL database adapter
Copyright (C) 2008 Guido De Rosa <guidoderosa@gmail.com>

based on MySQL driver mysql.php by Paul James
Copyright (C) 2004 Paul James <paul@peej.co.uk>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/* $id$ */

/**
 * PHP REST PostgreSQL class
 * PostgreSQL connection class.
 */
class postgresql {
    
	/**
	 * @var int
	 */
	public $lastInsertPKeys;

	/**
	 * @var resource
	 */
    public  $lastQueryResultResource;
    
    /**
     * @var resource Database resource
     */
    public  $db;
    
    /**
     * Connect to the database.
     * @param str[] config
     */
    public function connect($config) {

		$connString = sprintf(
			'host=%s dbname=%s user=%s password=%s',
			$config['server'],
			$config['database'],
			$config['username'],
			$config['password']
		);

        if ($this->db = pg_pconnect($connString)) {
            return TRUE;
	    }
		return FALSE;
    }

    /**
     * Close the database connection.
     */
    public function close() {
        pg_close($this->db);
    }
    
    /**
     * Get the columns in a table.
     * @param str table
     * @return resource A resultset resource
     */
    public function getcolumns($table) {
    	$qs = sprintf('SELECT * FROM information_schema.columns WHERE table_name =\'%s\'', $table);
		return pg_query($qs, $this->db);
    }
    
    
    /**
     * Get a row from a table by condition
     * @param str table
     * @return resource A resultset resource
     */
    public function querycond($strcond) {
    	$result = pg_query ($strcond) ;
    	if ($result) {
    		$this->lastQueryResultResource = $result;
    	}
    	return $result;
    }
    
    
    /**
     * Get a row from a table.
     * @param str table
     * @return resource A resultset resource
     */
    public function query($strtable) {
    	$result = pg_query(sprintf('SELECT * FROM %s', $strtable) );
    	if ($result) {
    		$this->lastQueryResultResource = $result;
    	}
    	return $result;
    }
    
    /**
     * Get the rows in a table.
     * @param str primary The names of the primary columns to return
     * @param str table
     * @return resource A resultset resource
     */
    public function gettable($primary, $table) {
        $result = pg_query(sprintf('SELECT %s FROM %s', $primary, $table));  
        if ($result) {
            $this->lastQueryResultResource = $result;
        }
        return $result;        
    }

    /**
     * Get the tables in a database.
     * @return resource A resultset resource
     */
    public function getdatabase() {
        return pg_query('SELECT table_name FROM information_schema.tables WHERE table_schema=\'public\'');   
    }

    /**
     * Get the primary keys for the request table.
     * @return str[] The primary key field names
     */
    public function getprimarykeys($table) {
        $i = 0;
        $primary = NULL;
        do {
		    $query = sprintf('SELECT pg_attribute.attname
		        FROM pg_class, pg_attribute, pg_index
                WHERE pg_class.oid = pg_attribute.attrelid AND
                pg_class.oid = pg_index.indrelid AND
                pg_index.indkey[%d] = pg_attribute.attnum AND
                pg_index.indisprimary = \'t\'
                and relname=\'%s\'',
				$i,
				$table
			);
        	$result = pg_query($query);
            $row = pg_fetch_assoc($result);
            if ($row) {
                $primary[] = $row['attname'];
            } 
            $i++;
        } while ($row);

        return $primary;
    }

    /**
     * Update a row.
     * @param str table
     * @param str values
     * @param str where
     * @return bool
     */
    public function updaterow($table, $values, $where) {
        # translate from MySQL syntax :)
        $values = preg_replace('/"/','\'',$values);
        $values = preg_replace('/`/','"',$values); 
        $qs = sprintf('UPDATE %s SET %s WHERE %s', $table, $values, $where);
        $result = pg_query($qs);       
        if ($result) {
            $this->lastQueryResultResource = $result;
        }
        return $result;
    }
    
    /**
     * Insert a new row.
     * @param str table
     * @param str names
     * @param str values
     * @return bool
     */
    public function insertrow($table, $names, $values) {
        # translate from MySQL syntax
		$names = preg_replace('/`/', '"', $names); #backticks r so MySQL-ish! ;)
        $values = preg_replace('/"/', '\'', $values);
        $pkeys = join(', ', $this->getPrimaryKeys($table));
        
        $qs = sprintf(
			'INSERT INTO %s (%s) VALUES (%s) RETURNING (%s)',
			$table,
        	$names,
			$values,
			$pkeys
		);
        $result = pg_query($qs); #or die(pg_last_error());

        $lastInsertPKeys = pg_fetch_row($result);
        $this->lastInsertPKeys = $lastInsertPKeys;

        if ($result) {
            $this->lastQueryResultResource = $result;
        }
        return $result;
    }
    
    /**
     * Get the columns in a table.
     * @param str table
     * @return resource A resultset resource
     */
    public function deleterow($table, $where) {
        $result = pg_query(sprintf('DELETE FROM %s WHERE %s', $table, $where));   
        if ($result) {
            $this->lastQueryResultResource = $result;
        }
        return $result;
    }
    
    /**
     * Escape a string to be part of the database query.
     * @param str string The string to escape
     * @return str The escaped string
     */
    public function escape($string) {
        return pg_escape_string($string);
    }
    
    /**
     * Fetch a row from a query resultset.
     * @param resource resource A resultset resource
     * @return str[] An array of the fields and values from the next row in the resultset
     */
    public function fetchrow($resource) {
    	return pg_fetch_row($resource);
    }
    
    /**
     * Fetch a row from a query resultset.
     * @param resource resource A resultset resource
     * @return str[] An array of the fields and values from the next row in the resultset
     */
    public function assoc($resource) {
        return pg_fetch_assoc($resource);
    }

    /**
     * Fetch a row from a query resultset.
     * @param resource resource A resultset resource
     * @return str[] An array of the fields and values from the next row in the resultset
     */
    public function fetchobject($resource) {
    	return pg_fetch_object($resource);
    }
    
    
    /**
     * The number of rows in a resultset.
     * @param resource resource A resultset resource
     * @return int The number of rows
     */
    public function numrows($resource) {
        return pg_num_rows($resource);
    }

    /**
     * The number of rows affected by a query.
     * @return int The number of rows
     */
    public function numaffected() {
        return pg_affected_rows($this->lastQueryResultResource);
    }
    
    /**
     * Get the ID of the last inserted record. 
     * @return int The last insert ID ('a/b' in case of multi-field primary key)
     */
    public function lastinsertid() {
        return join('/', $this->lastInsertPKeys);
    }
    
}
?>
