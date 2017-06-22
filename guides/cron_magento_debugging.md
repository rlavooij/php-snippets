# Magento 1.9.x cron problem solving
by Ruben
(most of this works for m2 too)

### What is not working?
*cronjobs in magento are not firing.*
This limits the the automated parts of the website. like sending e-mails, stocks, exports etc.

>*this is a critical magento process and should work at all times.*

### How to troubleshoot?

#### Server
Log in to the server trough ssh.
run the following command:
```sh
tail /var/log/cron
```
This will give you an output with the latest crons that ran on the server. Magento's `cron.php` should be in there.
```sh
Jun 22 12:26:01 46 CROND[47555]: (sitecoukrd50od) CMD (wget -O /dev/null -q http://www.site.co.uk/cron.php > /dev/null)
Jun 22 12:26:01 46 CROND[47556]: (root) CMD (php /var/www/vhosts/site.co.uk/htdocs/cron.php)
Jun 22 12:27:01 46 CROND[47600]: (sitecoukrd50od) CMD (wget -O /dev/null -q http://www.site.co.uk/cron.php > /dev/null)
Jun 22 12:27:01 46 CROND[47601]: (root) CMD (php /var/www/vhosts/site.co.uk/htdocs/cron.php)
```
If there's recent information in here you can assume the cron is working on the server's end.
If it is empty, or no recent information is given, check the contab:
```sh
crontab -e
```
If you end up in vim, burn your computer and start again, but try this command to open it in `nano`
```sh
export VISUAL=nano; crontab -e
```
If it is empty, you found your problem. if the `cron.php` is not in there, it's obvious that it's not firing. 
This is what you would want to see for magento: (every 5 min)
```sh
*/5 * * * * php /var/www/vhosts/site.co.uk/htdocs/cron.php
```
check it with the `tail` command if it is actually running now.
If not it might be worth to check if the service is running:
```sh
ps -A | grep cron
```
and if not (re)start it
```sh
/sbin/service crond restart
```
### Magento side
So the server cron is definitely working now. so the next step is Magento. boom!
Clear Cache. to make sure its not some stupid caching issue. 

In the magento backend (1.9.x) go to `system -> configuration ` choose `Advanced -> System` and under the header `Cron (Scheduled Tasks)` see if the settings are:
>15
20
15
10
60
600

In the database table `cron_schedule` you can see the last runs of the magento cron. also when the next one is scheduled. To make it easy, use this script and navigate to the file:
```php
<?php
  
// Parse magento's local.xml to get db info, if local.xml is found
  
if (file_exists('app/etc/local.xml')) {
  
$xml = simplexml_load_file('app/etc/local.xml');
  
$tblprefix = $xml->global->resources->db->table_prefix;
$dbhost = $xml->global->resources->default_setup->connection->host;
$dbuser = $xml->global->resources->default_setup->connection->username;
$dbpass = $xml->global->resources->default_setup->connection->password;
$dbname = $xml->global->resources->default_setup->connection->dbname;
  
}
  
else {
    exit('Failed to open app/etc/local.xml');
}
  
// DB Interaction
$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to <a class="HelpLink" onclick="showHelpTip(event, hint_id_7); return false;" href="javascript:void(0)">mysql</a>');
mysql_select_db($dbname);
  
$result = mysql_query("SELECT * FROM " . $tblprefix . "cron_schedule") or die (mysql_error());
  
  
// DB info for user to see
echo '
<body>

  
<b>Table Prefix:</b> ' . $tblprefix . ''
. '<b>DB Host:</b> ' . $dbhost . ''
. '<b>DB User:</b> ' . $dbuser . ''
. '<b>DB Name</b>: ' . $dbname . '</p>';
  
// Set up <span style="background-color:#CCFF00;">the</span> table
echo "
        <table border='1'>
        <thread>
        <tr>
        <th>schedule_id</th>
           <th>job_code</th>
           <th>status</th>
           <th>messages</th>
           <th>created_at</th>
           <th>scheduled_at</th>
           <th>executed_at</th>
           <th>finished_at</th>
           </tr>
           </thread>
           <tbody>";
  
// Display <span style="background-color:#CCFF00;">the</span> data from <span style="background-color:#CCFF00;">the</span> query
while ($row = mysql_fetch_array($result)) {
           echo "<tr>";
           echo "<td>" . $row['schedule_id'] . "</td>";
           echo "<td>" . $row['job_code'] . "</td>";
           echo "<td>" . $row['<span style="background-color:#CCFF00;">status</span>'] . "</td>";
           echo "<td>" . $row['messages'] . "</td>";
           echo "<td>" . $row['created_at'] . "</td>";
           echo "<td>" . $row['scheduled_at'] . "</td>";
           echo "<td>" . $row['executed_at'] . "</td>";
           echo "<td>" . $row['finished_at'] . "</td>";
           echo "</tr>";
}
  
// Close table and last few tags
echo "</tbody></table></body>";
  
mysql_close($conn);
?>
```
this should give you enough information to debug crons, the magento side is really simple because theres not much options to worry about. restarting apache/nginx might help too!

