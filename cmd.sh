#!/bin/bash
# ps -ef | grep "php"
# kill -9
# ps -ef | grep "java"
# kill -9
## kill -9 `ps -ef | grep "php" | cut -d " " -f 6 | head -n 2 | tail -n 1`
## kill -9 `ps -ef | grep "java" | cut -d " " -f 6 | head -n 2 | tail -n 1`
# cd /var/www/html/ljc/Network/test
javac -classpath /var/www/html/ljc/Java/test/org.json.jar /var/www/html/ljc/Java/test/Socket_Socket.java
nohup java -classpath .:/var/www/html/ljc/Java/test/org.json.jar Socket_Socket &>> /var/www/html/ljc/java_history &
# cd /var/www/html/ljc/PHP/Weixin/network
nohup php -f Client_Input.php &>> /var/www/html/ljc/php_history &
