<?php

if (!$safe) { header("HTTP/1.0 404 Not Found"); exit(); }

class Database extends Error {

	# Class variables
	private $db;					// stores the MySQL server details
	private $connection;			// contains the MySQL connection
	private	$query;					// contains the last SQL code passed to the database
	protected static $instance;		// part of the singleton pattern used by the class

	// The constructor, sets the database details
	private function Database() {
		$this->db["host"]	= "localhost";	// server address
		$this->db["user"]	= "sam";		// database username
		$this->db["pass"]	= "HyperL0g0";	// database password
		$this->db["db"]		= "starbase";	// database name

		// Connect to the database
		$this->connect();
	}

	// Function that enables use of the singleton pattern
	public static function start() {
		// If there is no instance already, make one
		if (!isset(self::$instance)) {
			self::$instance = new Database();
		}
	return self::$instance; }

	// Function that connects us to the database
	public function connect() {
		// Try and connect, suppress errors
		$this->connection = @mysqli_connect($this->db['host'], $this->db['user'], $this->db['pass']);

		if (!$this->connection) {
			// If the connection failed record error and return false
			$this->_error("Connection Error: " . mysqli_connect_error());
			return false;
		} else {
			// Otherwise select the correct database and return true
			$this->select_db($this->db["db"]);
		};
	return true; }

	// Disconnects us from the database
	public function disconnect() {
		if ($this->connection) {
			// Close our connection
			mysqli_close($this->connection);
			$this->connection = null;
			return true;
		} else {
			// If there isn't a connection, record an error
			$this->_error("Connection Error: Unable to disconnect, not connected to '" . $this->db['host'] ."'");
		};
	return false; }

	// Function to select the working database
	public function select_db($db) {
		if (@mysqli_select_db($this->connection, $db)) {
			// If the database was selected return true
			return true;
		} else {
			// Otherwise record an error
			$this->_error("Connection Error: Unable to select " . $this->db["db"] . " as the database.");
		};
	return false; }

	// Function to peform a basic SQL query, returns raw result on success
	public function query($sql) {
		// Set the last query variable
		$this->query = $sql;

		if ($result = @mysqli_query($this->connection, $sql)) {
			// If the query was successful, return it
			return $result;
		} else {
			// Otherwise spring an error and return false
			$this->_error("SQL Error: The following query was not successful '" . $this->query . "'");
		};
	return false; }

	// Specific query function for a SELECT query, returns an associative array if successful
	public function select($sql) {
		// Set the last query variable
		$this->query = $sql;

		if ($data = $this->query($sql)) {
			// Turn the MySQL result into an associatice array
			$result = mysqli_fetch_assoc($data);
			if (!mysqli_free_result($data)) {
				$this->_error("A MySQL result could not be freed.");
			}
			return $result;
		};
	return false; }

	// Function that returns an associative array of all the results from the query
	public function select_all($sql) {
		// Set the last query variable
		$this->query = $sql;

		// Use the query() method to get the MySQL object
		// Make sure the result isn't empty
		if (($data = $this->query($sql)) && mysqli_num_rows($data) != 0) {
			// Loop through the MySQl object and add each row to the array
			while ($row = mysqli_fetch_assoc($data)) {
				$result[] = $row;
			}
			// Try and free the result after making the array, good pratice
			if (!mysqli_free_result($data)) {
				$this->_error("A MySQL result could not be freed.");
			}
			return $result;
		};
	return false; }

	// The clean method, use on any and all user input before pasing it to the database
	public function clean($dirty) {
		if (!is_array($dirty)) {
			$dirty = mysqli_real_escape_string($this->connection, trim($dirty));
			$clean = stripslashes($dirty);
	return $clean; };
		$clean = array();
		foreach ($dirty as $p => $data) {
			$data = mysqli_real_escape_string($this->connection, trim($data));
			$data = stripslashes($data);
			$clean[$p] = $data; };
	return $clean; }

}

class Error {
# Error Class
# Requires that the file it writes to
# can be written by everyone (i.e. chmod 777)

	private $error;			// the last error message

	private $name = "";		// name of the text file to write errors to
							// change to "something" to write to a diffrent file

	// The constructor, if no name is set use "log"
	public function Error($name = null) {
		if (!empty($name)) {
			$this->name = $name;
		} else {
			$this->name = "log";
		};
	}

	// Function to record an error
	public function _error($error) {
		// Record a timestamp
		$time = "[" . date("Y-m-d H:i:s") . "] ";

		// Add the timestamp to the error message
		$this->error = $time . $error;

		// Store the error message to the file
		$this->_store();
	}

	// Function that writes error messages to a file
	private function _store() {
		// Open the file in write mode, if no file exists create one
		$file = fopen("log.txt", "a+")
			// If we can't open the file, stop the script and display a message
			or exit("The error call can't write to the log file. Check it's permissions (chmod 777).");

		// Write the error to file, with a line break at the end
		fwrite($file, $this->error . "\n");

		// Close the file
		fclose($file);

		// Once the error is written to file, reset the error variable
		$this->error = null;
	}
}

class Template {

	# Variables
	private $notice;
	private $db;

	# Functions
	public function login_input($type, $name, $placeholder, $index) {
		if (!empty($this->notice) && (strpos($this->notice[1], "database") || strpos($this->notice[1], "cookies"))) {
			$html = '<input type="' . $type . '" name="' . $name . '" placeholder="' . $placeholder . '" tabindex="' . $index . '" disabled />';
		} elseif (!isset($_POST["submit"]) || $type == "password") {
			$html = '<input type="' . $type . '" name="' . $name . '" placeholder="' . $placeholder . '" tabindex="' . $index . '" />';
		} else {
			$html = '<input type="' . $type . '" name="' . $name . '" placeholder="' . $placeholder . '" value="' . $_POST[$name] .'" tabindex="' . $index . '" />';
		}

		echo $html;
	return true; }

	public function register_input($type, $name, $placeholder, $index) {
		if (!empty($this->notice) && strpos($this->notice[1], "database")) {
			$html = '<input type="' . $type . '" name="' . $name . '" placeholder="' . $placeholder . '" tabindex="' . $index . '" disabled />';
		} elseif (!isset($_POST["submit"]) || $type == "password") {
			$html = '<input type="' . $type . '" name="' . $name . '" placeholder="' . $placeholder . '" tabindex="' . $index . '" />';
		} else {
			$html = '<input type="' . $type . '" name="' . $name . '" placeholder="' . $placeholder . '" value="' . $_POST[$name] .'" tabindex="' . $index . '" />';
		}

		echo $html;
	return true; }

	public function display_list($list, $user) {
			# Remove private starbases
			foreach ($list as $i => $data) {
				if ($this->check_private($data["private"]) && $user["name"] != $data["owner"]) {
					unset($list[$i]);
				}
			}
			$list = array_values($list);
		if (!empty($list)) {
			$i = 0;
			echo('<div class="section">
					<table width="850" cellspacing="0" cellpadding="10" class="list">
						<tr style="background-color:#d9d8d8; font-size:14px; font-weight:bold;">
							<td width="250">Starbase</td>
							<td width="250">Location</td>
							<td width="120">Status</td>
							<td width="90">Corp.</td>
							<td width="80">Owner</td>
							<td width="60"></td>
						</tr>');
			foreach ($list as $data) {
				if (!$this->check_private($data["private"]) || $user == $data["owner"]) {
					if (is_int($i / 2)) {
						echo('<tr class="gray">');
					} else {
						echo('<tr>');
					}
					echo('<td>' . $data["name"] . '</td>');
					echo('<td><a href="http://evemaps.dotlan.net/region/' . $data["region"] . '">' . $data["region"] . '</a> / <a href="http://evemaps.dotlan.net/system/' . $data["system"] . '">' . $data["system"] . '</a> / <a href="#">' . $data["planet"] . ' &ndash ' . $data["moon"] . '</a></td>');
					echo('<td>' . $data["status"] . '</td>');
					echo('<td>' . $data["corp"] . '</td>');
					echo('<td>' . $data["owner"] . '</td>');
					echo('<td style="text-align:center;"><a href="view.php?do=update&id=' . $data["id"] . '">Update</a></td>');
					$i++;
				}
			}
			echo('</table></div>');
		} else {
			$this->notice(array("info", "No starbases found."));
		}
	}

	public function notice($notice) {
	# needs to accept array
		$this->notice = $notice;
		if (!empty($notice)) {
			echo '<div class="notice ' . $notice[0] . '">' . $notice[1] . '</div>';
		}
	}

	public function check_private($private) {
		if ($private == 1) {
			return true;
		}
	return false; }

}

class User {

	private $db;

	# Variables
	public $detail;

	# Functions
	public function User($id) {
		# Database connection
		$db = Database::start();

		$sql = "SELECT * FROM user WHERE id = '" . $db->clean($id) . "';";
		$result = $db->select($sql);
		$this->detail = array(
			"id"		=> $result["id"],
			"name"		=> $result["user"],
			"rights"	=> explode(",", $result["rights"]),
			"corp"		=> $result["corp"]);
	}

	public function right($right) {
		if ($this->detail["rights"][$right - 1] == 1) {
			return true;
		}
	return false; }

	public function add($id, $api) {
	}

}