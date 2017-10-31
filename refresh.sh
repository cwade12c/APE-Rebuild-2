#!/bin/bash

# refresh bash script
# built up to be ran from the cron job
# will run the script for all website/ subwebsites for development
# the 'refresh.php' file requires #!/usr/bin/php at the top with execute permissions
# cron job: * * * * * /absolute/path/to/refresh.sh

PARENT_DIR=$(pwd)
REFRESH_SUBDIR='scripts'
REFRESH_SCRIPT='refresh.php'

execute_refresh () {
	SUBDIR=$1
	if [ -n "$SUBDIR" ] ; then
		SUBDIR="$SUBDIR/"
	fi
	SCRIPT_DIR="$PARENT_DIR/$SUBDIR" 
	SCRIPT_DIR="$SCRIPT_DIR$REFRESH_SUBDIR"
	
	#echo "script dir: $SCRIPT_DIR"
	
	# running php from cli, need to swap into refresh script directory
	# for includes to work properly
	cd $SCRIPT_DIR
	
	./$REFRESH_SCRIPT
	
	cd $PARENT_DIR
}

#execute_refresh ""
#execute_refresh "chdev"
execute_refresh "mmdev/APE-Rebuild-2"