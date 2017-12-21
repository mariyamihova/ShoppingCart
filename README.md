


How to run this project on my PC?

1. Download/clone project

2. 
Go into the root directory of the project (where the bin folder resides)

3. Make sure you’ve started your MySQL server (either from XAMPP or standalone)

4. Create file "parameters.yml" in folder app/config and populate it with the required parameters(e.g db host, port, name and etc.)

5. Open a shell / command prompt / PowerShell window in the root directory and enter “php composer.phar install” command to restore its Composer dependencies (described in composer.json)

6. Enter the “php bin/console doctrine:database:create --if-not-exists” command 

7. Open project

8. Run "composer require knplabs/knp-paginator-bundle" command

9. Run "php/bin console server:run" command