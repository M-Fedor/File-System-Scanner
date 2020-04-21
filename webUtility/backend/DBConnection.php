<?php
define('OK', 0);
define('FAIL', 1);

class DBConnection 
{
    function __construct($server_address, $user_name, $password, $db_name)
    {
        $this->server_address = $server_address;
        $this->user_name = $user_name;
        $this->password = $password;
        $this->db_name = $db_name;

        $this->connection = $this->result = $this->stmt = NULL;
    }

    function __destruct() 
    {
        if ($this->stmt)
            $this->stmt->close();
        if ($this->connection)
            $this->connection->close();
    }

    function execute_select($digest, $file_name)
    {
        if ($this->result)
            $this->result->close();

        if (!$this->stmt->bind_param('sss', $digest, $file_name, $digest)) {
            echo "Binding parameters failed: (" . $this->stmt->errno . ") " . $this->stmt->error;
            return FAIL;
        }

        if (!$this->stmt->execute()) {
            echo "Execute failed: (" . $this->stmt->errno . ") " . $this->stmt->error;
            return FAIL;
        }

        $this->result = $this->stmt->get_result();
        if (!$this->result) {
            echo "Getting result set failed: (" . $this->stmt->errno . ") " . $this->stmt->error;
            return FAIL;
        }

        return OK;
    }

    function init()
    {
        $this->connection = new mysqli($this->server_address, $this->user_name, $this->password, $this->db_name);
        if ($this->connection->connect_errno) {
            echo "Failed to connect to MySQL: (" . $this->connection->connect_errno . ") " . $this->connection->connect_error;
            return FAIL;
        }    

        $this->stmt = $this->connection->prepare('(SELECT * FROM recognize_file WHERE file_digest = ?) UNION
                                                  (SELECT * FROM recognize_file WHERE absolute_path = ? AND file_digest != ?);');
        if (!$this->stmt) {
            echo "Prepare failed: (" . $this->connection->errno . ") " . $this->connection->error;
            return FAIL;
        }

        return OK;
    }

    private $connection;
    private $db_name;
    private $password;
    private $result;
    private $server_address;
    private $stmt;
    private $user_name;
}
?>
