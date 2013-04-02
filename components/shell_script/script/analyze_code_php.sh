#!/bin/bash
# Check process running
# Get first process to run
# Remove it in file 
server="10.186.0.247"
acphp_queue="/var/www/ctc_ide/data/acphp_queue.php"
info=`cat $acphp_queue`
if [ $info = "" ] ; then
        echo "Stop"
else
        echo "Run"
		cp $acphp_queue /home/Workspace/codiad/src/scripts/jenkins/
		cp $acphp_queue $acphp_queue.bak
		#>$acphp_queue
		rsync -vaz /home/Workspace/codiad/src/scripts/jenkins root@$server:/tmp/
		ssh "root@"$server "cd /tmp/jenkins; sh jenkins.sh; exit"

		if [ $? != 0 ];then
			echo "FAILED"
		else
			echo "DONE"
		fi

		rm -rf /tmp/jenkins
fi