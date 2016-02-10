<?php
if ($argc <= 1 || in_array($argv[1], array('--help', '-help', '-h', '-?'))) {
    ?>

    This is a command line PHP script that import csv file into MySql.

    Usage:
    <?php echo $argv[0]; ?> <option>

    <option> 
        --file [csv file name] - this is the name of the CSV to be parsed.
        --create_table - this will cause the MySQL users table to be built (and no further action will be taken).
        --dry_run - this will be used with the --file directive in the instance that we want 
        to run the script but not insert into the DB. All other functions will be executed,but the database won't be altered.
        -u - MySQL username
        -p - MySQL password
        -h - MySQL host
        With the --help, -help, -h, or -? options, you can get this help.

        <?php
    } else {

        $shortopts = "";
        $shortopts .= "u:";  // Required value
        $shortopts .= "p:"; // Required value
        $shortopts .= "h:"; // Required value

        $longopts = array(
            "file:", // Required value
            "create_table::", // Optional value
            "dry_run::", // Optional value
        );
        $options = getopt($shortopts, $longopts);
        
        $username = $options[u];
        $password = $options[p];
        $host = $options[h];
        
        echo $username.$password.$host. PHP_EOL;
        if(array_key_exists("dry_run",$options)){
            dryRun($options,$col);
        }
        
        
        
    }
    function dryRun($options,$col){
        $csv_file = $options["file"];

        if (($handle = fopen($csv_file, "r")) !== FALSE) {
            fgetcsv($handle);
            while (($data = fgetcsv($handle, ",")) !== FALSE) {
                $num = count($data);
                for ($c = 0; $c < $num; $c++) {
                    $col[$c] = $data[$c];
                }

                $firstname = ucfirst($col[0]);
                $surname = ucfirst($col[1]);
                $email = strtolower(trim($col[2]));
                $status=emailValidate($email);
                                    
                if(!$status){
                    echo "This ($email) email address is not valid.". PHP_EOL;
                };
            }
            fclose($handle);
        }
    }
    function emailValidate($email){
        $z=TRUE;
         if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                   $z=FALSE;
                }
                 return $z;
    }
    ?>
