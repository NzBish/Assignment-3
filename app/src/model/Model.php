<?php
namespace ktc\a2\model;

use mysqli;
use ktc\a2\exception\StoreException;

/**
 * Class Model
 *
 * @package ktc/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 * @author  K. Dempsey
 * @author  T. Crompton
 * @author  C. Bishop
 */
class Model
{
    /**
     * @var mysqli Database connection
     */
    protected $db;

    /**
     * Model constructor
     *
     * Sets the database connection variables via an external file
     * Creates the database if not already created
     *
     * @throws StoreException on database connection errors
     */
    public function __construct()
    {
        $envs = getenv();
        $dbHost = $envs['MYSQL_HOST'];
        $dbName = $envs['MYSQL_DATABASE'];
        $dbUser = $envs['MYSQL_USER'];
        $dbPass = $envs['MYSQL_PASSWORD'];
        $this->db = new mysqli(
            $dbHost,
            $dbUser,
            $dbPass
        );

        if (!$this->db) {
            throw new StoreException(99, $this->db->connect_error);
        }

        /**
         * This is to make our life easier
         * Create your database and populate it with sample data
         */
        $this->db->query("CREATE DATABASE IF NOT EXISTS $dbName;");

        if (!$this->db->select_db($dbName)) {
            // somethings not right.. handle it
            throw new StoreException("Mysql database not available!");
        }

        /** Defining strings for table creation */
        $databaseUser = "CREATE TABLE `user` (
                                        `user_id` INT NOT NULL AUTO_INCREMENT, 
                                        `user_name` VARCHAR(30) UNIQUE NOT NULL,
                                        `user_first` VARCHAR(30) NOT NULL, 
                                        `user_last` VARCHAR(30) NOT NULL,
                                        `user_pass` VARCHAR(72) NOT NULL,
                                        `user_email` VARCHAR(50) NOT NULL,
                                        Primary key (user_id));";


        $databaseProduct = "CREATE TABLE `product` (
                                        `prod_id` INT NOT NULL AUTO_INCREMENT, 
                                        `prod_sku` VARCHAR(30) UNIQUE NOT NULL,
                                        `prod_name` VARCHAR(30) NOT NULL, 
                                        `prod_cost` DECIMAL(15,2) NOT NULL,
                                        `prod_category` VARCHAR(30) NOT NULL,
                                        `prod_quantity` INT NOT NULL,                                
                                        PRIMARY KEY (prod_id));";

        $this->buildTable('user', $databaseUser);
        $this->buildTable('product', $databaseProduct);
        $this->buildTableData();
    }


    /**
     * Model table builder
     *
     * If not already present, creates a table based on a provided query
     *
     * @param string $tableName The name of the table to be created
     * @param string $table The full query creating the table
     * @throws StoreException on database connection errors
     */
    public function buildTable($tableName, $table)
    {
        $result = $this->db->query("SHOW TABLES LIKE '$tableName';");

        if ($result->num_rows == 0) {
            // table doesn't exist
            // create it
            $result = $this->db->query($table);
            if (!$result) {
                // handle appropriately
                throw new StoreException(99, "Failed creating table " . $tableName);
            }
        }
    }

    /**
     * Model table data builder
     *
     * If not already present, inserts sample data into the User, Account, and Transaction tables
     *
     * @throws StoreException on database connection errors
     */
    public function buildTableData()
    {
        if (!$password = password_hash("1111", PASSWORD_BCRYPT)) {
            throw new StoreException(99, "Failed to hash entered password");
        }
        if (!$admin = password_hash("admin", PASSWORD_BCRYPT)) {
            throw new StoreException(99, "Failed to hash entered password");
        }
        if (!$tim = password_hash("TheToolman", PASSWORD_BCRYPT)) {
            throw new StoreException(99, "Failed to hash entered password");
        }

        /** Strings to insert */
        $insertUser = "INSERT INTO `user` VALUES (NULL, 'admin', 'Administrator', '', '$admin',
                                                 'admin@ktc.com'),
                                                 (NULL, 'TheToolman', 'Tim', 'Taylor', '$tim',
                                                 'tim@homeimprovement.com'),
                                                 (NULL, 'CBishop', 'Chris', 'Bishop', '$password',
                                                  'chris@gmail.com'),
                                                 (NULL, 'MLittleLamb', 'Mary','LittleLamb', '$password', 
                                                  'mary@gmail.com');";

        $insertProduct = "INSERT INTO `product` VALUES (NULL,'ham11','Claw Hammer',39.95,'Hammers',11),
                                                       (NULL,'ham22','Sledge Hammer',66.00,'Hammers',2),
                                                       (NULL,'ham23','Soft-Face Hammer',24.99,'Hammers',7),
                                                       (NULL,'screw03','Flat Screwdriver',11.95,'Screwdrivers',25),
                                                       (NULL,'screw23','Philips Screwdriver',11.95,'Screwdrivers',30),
                                                       (NULL,'wren11','Allen Wrench',33.99,'Wrenches',12),
                                                       (NULL,'wren21','Socket Wrench',50.00,'Wrenches',9),
                                                       (NULL,'saw13','Hand Saw',15.00,'Saws',22),
                                                       (NULL,'saw15','Hacksaw',67.95,'Saws',13),
                                                       (NULL,'saw19','Back Saw',53.50,'Saws',19);";


        /** Check if already inserted */
        $result = $this->db->query("SELECT * FROM `user`;");

        if ($result->num_rows == 0) {
            if (!$this->db->query($insertUser)) {
                throw new StoreException(99, "Failed creating sample user data! " . mysqli_error($this->db));
            }
            if (!$this->db->query($insertProduct)) {
                throw new StoreException(99, "Failed creating sample product data! " . mysqli_error($this->db));
            }
        }
    }

    /**
     * Get DB connection
     *
     * @return mixed Either a mysqli database connection or NULL
     */
    public function getDb()
    {
        return $this->db;
    }
}
