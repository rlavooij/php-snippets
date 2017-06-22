find . -type f \-exec chmod 644 {} \;
find . -type d \-exec chmod 755 {} \;
find ./var -type d \-exec chmod 777 {} \;
find ./var -type f \-exec chmod 666 {} \;
find ./media -type d \-exec chmod 777 {} \;
find ./media -type f \-exec chmod 666 {} \;
chmod 777 ./app/etc
chmod 644 ./app/etc/*.xml