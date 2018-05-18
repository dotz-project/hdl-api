#!/bin/bash

# Source function library.
#. /etc/rc.d/init.d/functions
#
## Path

RETVAL=0
STOP_TIMEOUT=${STOP_TIMEOUT-10}
noConsumidores=$(ps -ef | grep -i 'yii.*rabbitmq' | wc -l)

## The semantics of these two functions differ from the way apachectl does
## things -- attempting to start while running is a failure, and shutdown
## when not running is also a failure.  So we just do it the way init scripts
## are expected to behave here.
start() {

	if [ $noConsumidores -gt  1 ]; then
		echo "Erro : Já existem consumidores rodando.";
        echo "Use STOP para conseguir iniciar os processos.";
		exit
	fi       
	
	echo "starting COMPONENTS.REQUEST.CONSUMER";
	/app/yii rabbitmq/consume COMPONENTS.REQUEST.CONSUMER > /dev/null &
	echo "starting COMPONENTS.PROCESS.CONSUMER";
  /app/yii rabbitmq/consume COMPONENTS.PROCESS.CONSUMER > /dev/null &
	echo "starting CLUSTER.REQUEST.CONSUMER";
  /app/yii rabbitmq/consume CLUSTER.REQUEST.CONSUMER > /dev/null &
	echo "starting CLUSTER.PROCESS.CONSUMER";
  /app/yii rabbitmq/consume CLUSTER.PROCESS.CONSUMER > /dev/null &

	RETVAL=$?
        [ $RETVAL = 0 ]
	echo "OK"
        return $RETVAL
}

status() {
	ps -aux | grep yii
}

# When stopping httpd, a delay (of default 10 second) is required
# before SIGKILLing the httpd parent; this gives enough time for the
# httpd parent to SIGKILL any errant children.
stop() {
	echo -n $"Stopping consumers: "
	for i in $(ps -ef | grep -i 'yii.*rabbitmq' | awk '{print $2}'); do kill -9 $i 2> /dev/null; done
	RETVAL=$?
	echo "STOPPED"
	[ $RETVAL = 0 ]
}

# See how we were called.
case "$1" in
  start)
	start
	;;
  stop)
	stop
	;;
  status)
        status -p ${pidfile} $httpd
	RETVAL=$?
	;;
  restart)
	stop
	start
	;;
	status)
	status
	;;
  condrestart|try-restart)
	if status -p ${pidfile} $httpd >&/dev/null; then
		stop
		start
	fi
	;;
  force-reload|reload)
        reload
	;;
  graceful|help|configtest|fullstatus)
	$apachectl $@
	RETVAL=$?
	;;
  *)
	echo $"Usage: consumers.sh {start|stop|restart}"
	RETVAL=2
esac

exit $RETVAL