
# PHP Script Execution as a Cron Job

This document outlines the steps for setting up a PHP script to be executed regularly as a cron job. The example demonstrates how to run a PHP script every 4 hours and includes a bash script for managing the process, including handling XML file transfers to a database if necessary.

## Step 1: Creating a Bash Script

### Create a script

The php file that runs the cron job is "/xml_to_db_cron.php".

First, create a bash script named `run_xml_to_db_php_script.sh` that initiates your PHP application. This script navigates to your PHP script's directory, executes the PHP script, and can include additional commands for XML file processing or other logic.

Create a file with the following content:

**bash**

```
#!/bin/bash

# Navigate to the directory of your PHP script
cd /path/to/your/php/script

# Execute the PHP script
/usr/bin/php xml_to_db_cron.php

# Optional: Additional commands for XML file processing can be added here
```

Replace /path/to/your/php/script with the full path to your PHP script's directory and your_script.php with the name of the PHP file you want to execute.

### Make the script executable:

**bash**

```
chmod +x run_xml_to_db_php_script.sh
```

## Step 2: Setting Up a Cron Job

To schedule a cron job that executes your PHP script every 4 hours, follow these steps:

1. Open your user's crontab configuration:

**bash**

```
crontab -e
```

2. Add the following line to schedule the cron job:

**bash**

```
0 */4 * * * /path/to/run_xml_to_db_php_script.sh
```

Replace /path/to/run_xml_to_db_php_script.sh with the full path to your bash script.

3. Save and close the file. The cron job is now scheduled to run your PHP script every 4 hours.

## Additional Notes

- Ensure the path to the PHP interpreter (/usr/bin/php) is correct for your system. You might need to adjust it according to your configuration. Use which php in Linux to find the correct path.

- If your PHP script interacts with XML files for transferring data to a database, ensure the script is correctly written and tested before scheduling it as a cron job.

- The cron daemon must be running on your system for the scheduled tasks to be executed. Consult your system's documentation for details on managing cron services.