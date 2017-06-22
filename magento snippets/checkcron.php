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