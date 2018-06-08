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

	echo "starting KUBERNET.CLUSTER.REQUEST.CONSUMER";
  /app/yii rabbitmq/consume KUBERNET.CLUSTER.REQUEST.CONSUMER > /tmp/KUBERNET.CLUSTER.REQUEST.CONSUMER.LOG &
	echo "starting KUBERNET.CLUSTER.PROCESS.CONSUMER";
  /app/yii rabbitmq/consume KUBERNET.CLUSTER.PROCESS.CONSUMER > /tmp/KUBERNET.CLUSTER.PROCESS.CONSUMER.LOG &
	
  echo "starting MONGODBATLAS.CLUSTER.REQUEST.CONSUMER";
  /app/yii rabbitmq/consume MONGODBATLAS.CLUSTER.REQUEST.CONSUMER > /tmp/MONGODBATLAS.CLUSTER.REQUEST.CONSUMER.LOG &
	echo "starting MONGODBATLAS.CLUSTER.PROCESS.CONSUMER";
  /app/yii rabbitmq/consume MONGODBATLAS.CLUSTER.PROCESS.CONSUMER > /tmp/MONGODBATLAS.CLUSTER.PROCESS.CONSUMER.LOG &

  #echo "starting ELASTIC.CLUSTER.REQUEST.CONSUMER";
  #/app/yii rabbitmq/consume ELASTIC.CLUSTER.REQUEST.CONSUMER > /tmp/ELASTIC.CLUSTER.REQUEST.CONSUMER.LOG &
	#echo "starting ELASTIC.CLUSTER.PROCESS.CONSUMER";
  #/app/yii rabbitmq/consume ELASTIC.CLUSTER.PROCESS.CONSUMER > /tmp/ELASTIC.CLUSTER.PROCESS.CONSUMER.LOG &
	
	echo "starting AMQP.CLUSTER.REQUEST.CONSUMER";
  /app/yii rabbitmq/consume AMQP.CLUSTER.REQUEST.CONSUMER > /tmp/AMQP.CLUSTER.REQUEST.CONSUMER.LOG &
	echo "starting AMQP.CLUSTER.PROCESS.CONSUMER";
  /app/yii rabbitmq/consume AMQP.CLUSTER.PROCESS.CONSUMER > /tmp/AMQP.CLUSTER.PROCESS.CONSUMER.LOG &
	
	echo "starting KARAFKA.CLUSTER.REQUEST.CONSUMER";
  /app/yii rabbitmq/consume KARAFKA.CLUSTER.REQUEST.CONSUMER > /tmp/KARAFKA.CLUSTER.REQUEST.CONSUMER.LOG &
	echo "starting KARAFKA.CLUSTER.PROCESS.CONSUMER";
  /app/yii rabbitmq/consume KARAFKA.CLUSTER.PROCESS.CONSUMER > /tmp/KARAFKA.CLUSTER.PROCESS.CONSUMER.LOG &
	
	echo "starting COMPONENTS.REQUEST.CONSUMER";
	/app/yii rabbitmq/consume COMPONENTS.REQUEST.CONSUMER > /tmp/COMPONENTS.REQUEST.CONSUMER.LOG &
	echo "starting COMPONENTS.PROCESS.CONSUMER";
  /app/yii rabbitmq/consume COMPONENTS.PROCESS.CONSUMER > /tmp/COMPONENTS.PROCESS.CONSUMER.LOG &
	
	echo "starting JENKINS.CREATE.JOB.CONSUMER";
  /app/yii rabbitmq/consume JENKINS.CREATE.JOB.CONSUMER > /tmp/JENKINS.CREATE.JOB.CONSUMER.LOG &
	echo "starting JENKINS.BUILD.CONSUMER";
  /app/yii rabbitmq/consume JENKINS.BUILD.CONSUMER > /tmp/JENKINS.BUILD.CONSUMER.LOG &
	echo "starting JENKINS.BUILD.PROCESS.CONSUMER";
  /app/yii rabbitmq/consume JENKINS.BUILD.PROCESS.CONSUMER > /tmp/JENKINS.BUILD.PROCESS.CONSUMER.LOG &

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