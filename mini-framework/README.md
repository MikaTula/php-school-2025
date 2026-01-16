# docker-php-mysql-nginx

# Install:
  Go to docker and run

  docker-compose up
  or
  docker-compose up -d

  Add to C:\Windows\System32\drivers\etc\hosts 
     127.0.0.1 hello-docker.loc

# Site
  Go to http://hello-docker.loc/

# phpmyadmin
  Phpmyadmin works on http://hello-docker.loc:8081/
  
# redis-commander
  Redis Commander works on http://hello-docker.loc:8082/  
  Need add db #2
  KEYS * - get all exist KEYS
  GET -key- - get value by key
  DEL -key- - del value by key  
  
  
# mailhog
  MailHog works on http://hello-docker.loc:8025/  


# Set git hooks

	Run in root folder of project
	git config core.hooksPath .githooks

# Reformat all files
vendor\bin\phpcbf --standard=phpcs.xml --colors --warning-severity=0 app

# Reformat one file
./vendor/bin/phpcbf --standard=phpcs.xml --colors --warning-severity=0 tests/FileEditorTest.php


# Check all files
./vendor/bin/phpcs --standard=phpcs.xml --colors --warning-severity=0 ./src/*
./vendor/bin/phpcs --standard=phpcs.xml --colors --warning-severity=0 ./tests/*

