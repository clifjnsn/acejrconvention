# acejrconvention
"Master Control" system for ACE Jr Convention events

This is a basic php based website (with Mysql database backend), containerized in Docker so that anyone can build the image and run their own "Master Control" system and track Schools, People, Events, Payments, and Scores

##To Install:
> git clone https://github.com/clifjnsn/acejrconvention.git

> sudo docker build -t acejrconvention:1.0 acejrconvention

> sudo docker run --rm -t -p 8080:80 -i acejrconvention:1.0 /bin/bash


At the command prompt of the Docker container, type:
> /root/startup.sh

Now, we have a running server, with sample data inserted into the database

To connect, point your web browswer to: http://localhost:8080

