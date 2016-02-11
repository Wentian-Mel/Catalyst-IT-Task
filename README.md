# Catalyst-IT-Task

This is a command line PHP script that import csv file into MySql.


        Usage:
        PHP user_upload.php <option>

        <option> 
        
            --file [csv file name] - this is the name of the CSV to be parsed, if table "users" does not
                                    exists,this will create table "Users"
            
            eg. php user_upload.php --file users.csv -h localhost -d databaseName -u username -p password
            
            --create_table - this will cause the MySQL " Users"  table to be built if table "Users" exists,
                             this will drop and create again (and no further action will be taken).
            
            eg. php user_upload.php --create_table -h localhost -d databaseName -u username -p password
            
            --dry_run - this will be used with the --file directive in the instance that we want to run the
                        script but not insert into the DB. All other functions will be executed,but the 
                        database won't be altered.
                        
            eg. php user_upload.php --file users.csv --dry_run
            
            -h []- MySQL host
            -d []- Database name
            -u []- MySQL username
            -p []- MySQL password

            With the --help, -help, -h, or -? options, you can get this help.
            
            
If mysqli extension are not installed, please see below instruction.

sudo apt-get install php5-mysql

Will install package containing both old one and the new one, 
so afterwards all you need to do is to add extension=mysqli.so 
in your php.ini, restart apache and it should work.
