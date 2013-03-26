#!/bin/bash
user=$1
project_name=$2
git_server="git@10.186.0.247"
name=`echo ${project_name##*_}`

git clone $git_server:$user/$project_name.git /tmp/$name
rm -rf /tmp/.git
cp -r /tmp/$name /var/www/

rm -rf /tmp/$name

chown -R www-data:www-data /var/www/$name

python /home/Workspace/codiad/src/deploy.py $name
PID=`echo $?`
if [ $PID != 0  ]
then
if
	echo "........FAILED .........."
else
	echo "........DONE .........."
fi

