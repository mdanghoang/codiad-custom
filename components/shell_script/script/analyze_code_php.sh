#!/bin/bash
# Check process running
# Get first process to run
# Remove it in file 
server="10.186.0.247"

rsync -vaz /home/Workspace/codiad/scripts/jenkins root@$server:/tmp/
ssh "root@"$server "cd /tmp/jenkins; sh jenkins.sh; exit"

if [ $? != 0 ];then
	echo "FAILED"
else
	echo "DONE"
fi

rm -rf /tmp/jenkins