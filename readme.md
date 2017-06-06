# Export from multiple tables to a ftp location

```sh
export to ftp
```

Features:
  - Reads from multiple tables on a primary key
  - Builds a csv on the source location
  - Uploads this csv to a external ftp location (has been tested to work ftp->ftp on a cron)
  - Todo: move the built csv's to a "history" folder

Notes: 
The export_perorder.php is the latest file, as the client required this per order I have not updated the export_allorders.php with all the changes to make it work on par.