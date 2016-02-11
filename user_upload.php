<?php
if ($argc <= 2 || in_array($argv[1], array('--help', '-help', '-h', '-?'))) {
    ?>

    This is a command line PHP script that import csv file into MySql.

    Usage:
    <?php echo $argv[0]; ?> <option>

    <option> 
        --file [csv file name] - this is the name of the CSV to be parsed.
        eg. php user_upload.php --file users.csv -h localhost -d testDB -u username -p password
        --create_table - this will cause the MySQL " users"  table to be built (and no further action will be taken).
        eg. php user_upload.php --create_table -h localhost -d testDB -u username -p password
        --dry_run - this will be used with the --file directive in the instance that we want 
        to run the script but not insert into the DB. All other functions will be executed,but the database won't be altered.
        eg. php user_upload.php --file users.csv --dry_run
        -h []- MySQL host
        -d []- Database
        -u []- MySQL username
        -p []- MySQL password

        With the --help, -help, -h, or -? options, you can get this help.

        <?php
    } else {

        $shortopts = "";
        $shortopts .= "h:";  // Required value username
        $shortopts .= "d:"; // Required value password
        $shortopts .= "u:"; // Required value host
        $shortopts .= "p:"; // Required value database

        $longopts = array(
            "file:", // Required value file name
            "create_table::", // Optional value
            "dry_run::", // Optional value
        );
        $options = getopt($shortopts, $longopts);

        $DBAllSet = FALSE;
        if (array_key_exists("h", $options) && array_key_exists("d", $options) && array_key_exists("u", $options)) {
            $DBAllSet = TRUE;
        }

        if (array_key_exists("dry_run", $options)) {
            dryRun($options);
        } elseif (array_key_exists("create_table", $options) && $DBAllSet) {
            createTable($options);
        } elseif (array_key_exists("file", $options) && $DBAllSet) {
            importIntoMySQL($options);
        } else {
            ?>

            This is a command line PHP script that import csv file into MySql.

            Usage:
        <?php echo $argv[0]; ?> <option>

        <option> 
            --file [csv file name] - this is the name of the CSV to be parsed.
            eg. php user_upload.php --file users.csv -h localhost -d testDB -u username -p password
            --create_table - this will cause the MySQL " users"  table to be built (and no further action will be taken).
            eg. php user_upload.php --create_table -h localhost -d testDB -u username -p password
            --dry_run - this will be used with the --file directive in the instance that we want 
            to run the script but not insert into the DB. All other functions will be executed,but the database won't be altered.
            eg. php user_upload.php --file users.csv --dry_run
            -h []- MySQL host
            -d []- Database
            -u []- MySQL username
            -p []- MySQL password

            With the --help, -help, -h, or -? options, you can get this help.

            <?php
        }
    }

    function dryRun($options) {
        $csv_file = $options["file"];

        if (($handle = fopen($csv_file, "r")) !== FALSE) {
            fgetcsv($handle);
            while (($data = fgetcsv($handle, ",")) !== FALSE) {
                $num = count($data);
                for ($c = 0; $c < $num; $c++) {
                    $col[$c] = $data[$c];
                }

                $name = ucfirst($col[0]);
                $surname = ucfirst($col[1]);
                $email = strtolower(trim($col[2]));
                $status = emailValidate($email);
                if (!$status) {
                    echo $name . " " . $surname . " ";
                    echo "This ($email) email address is not valid." . PHP_EOL;
                }
            }
            fclose($handle);
        }
    }

    function importIntoMySQL($options) {
        $username = $options["u"];
        $password = $options["p"];
        $host = $options["h"];
        $dbname = $options["d"];
        //database connection details
        $conn = new mysqli($host, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $val = "select 1 from `Users` LIMIT 1";
        if ($conn->query($val) === FALSE) {
            echo 'Table Users does not existed' . PHP_EOL;
            createTable($options);
        }

        $csv_file = $options["file"];
        if (($handle = fopen($csv_file, "r")) !== FALSE) {
            fgetcsv($handle);
            while (($data = fgetcsv($handle, ",")) !== FALSE) {
                $num = count($data);
                for ($c = 0; $c < $num; $c++) {
                    $col[$c] = $data[$c];
                }

                $name = mysqli_real_escape_string($conn, ucfirst($col[0]));
                $surname = mysqli_real_escape_string($conn, ucfirst($col[1]));
                $email = strtolower(trim($col[2]));
                $status = emailValidate($email);

                if (!$status) {
                    echo $name . " " . $surname . " ";
                    echo "This ($email) email address is not valid." . PHP_EOL;
                } else {

                    $sql = "INSERT INTO `Users`( `name`, `surname`, `email`) VALUES ('" . $name . "','" . $surname . "','" . mysqli_real_escape_string($conn, $email) . "') ";
                    if ($conn->query($sql) === TRUE) {
                        echo "New record created successfully" . PHP_EOL;
                        ;
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error . PHP_EOL;
                        ;
                    }
                }
            }
            fclose($handle);
        }

        $conn->close();
    }

    function createTable($options) {
        $username = $options["u"];
        $password = $options["p"];
        $host = $options["h"];
        $dbname = $options["d"];
// Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

// sql to create table
        $createTableSQL = "CREATE TABLE Users (
                    id  int AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    surname VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL,
                    UNIQUE (email)
                    )";

        if ($conn->query($createTableSQL) === TRUE) {
            echo "Table Users created successfully" . PHP_EOL;
        } else {
            echo "Error creating table: " . $conn->error . PHP_EOL;
            $DropTableSQL = "DROP TABLE Users ";
            if ($conn->query($DropTableSQL) === TRUE) {
                echo "Table Users dropped successfully" . PHP_EOL;
                if ($conn->query($createTableSQL) === TRUE) {
                    echo "Table Users created successfully" . PHP_EOL;
                } else {
                    echo "Error creating table: " . $conn->error . PHP_EOL;
                }
            } else {
                echo "Error dropping table: " . $conn->error . PHP_EOL;
            }
        }
        $conn->close();
    }

    function emailValidate($email) {
        $z = TRUE;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $z = FALSE;
        }
        return $z;
    }
    ?>
