#!/bin/bash
echo "Deploy application"
user=$1
project_name=$2
echo "Deploy project $project_name for user $user"
name=`echo ${project_name##*_}`
echo "Clone application from git server to /var/www/$name"
git clone $git_server:$user/$project_name.git /var/www/$name
chown -R www-data:www-data /var/www/$name
echo "Deploy DONE"
