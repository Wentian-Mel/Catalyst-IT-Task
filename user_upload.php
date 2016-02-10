<?php
if ($argc <= 1 || in_array($argv[1], array('--help', '-help', '-h', '-?'))) {
    ?>

    This is a command line PHP script with one option.

    Usage:
    <?php echo $argv[0]; ?> <option>

    <option> can be some word you would like
        to print out. With the --help, -help, -h,
        or -? options, you can get this help.

        <?php
    } else {

//$shortopts  = "";
//$shortopts .= "u:";  // Required value
//$shortopts .= "p:"; // Required value
//$shortopts .= "h:"; // Required value

        $longopts = array(
            "file:", // Required value
            "create_table::", // Optional value
            "dry_run::", // Optional value
        );
        $options = getopt("", $longopts);

        $csv_file = $options["file"];

        if (($handle = fopen($csv_file, "r")) !== FALSE) {
            fgetcsv($handle);
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                for ($c = 0; $c < $num; $c++) {
                    $col[$c] = $data[$c];
                }

                $col1 = $col[0];
                $col2 = $col[1];
                $col3 = $col[2];
                print_r($col1);
                print_r($col2);
                print_r($col3);
            }
            fclose($handle);
        }
    }
    ?>
