<?php
    namespace app\drivers\kubernetes;

    use Google\Cloud\Container\V1\ClusterManagerClient;
    use Google\Cloud\Container\V1\Operation;
    use Google\Cloud\Container\V1\Cluster;
    use Google\Cloud\Core\ServiceBuilder;
    
    use app\models\Components;

    use yii\base\ErrorException;

    class KubernetesCluster {
       
        public $name;
        public $params;
        public $cloud;

        public function __construct($name, $params) {
            $this->cloud = new ServiceBuilder();
            $this->name = $name;
            $this->params = $params;
            file_put_contents("/tmp/maingcm.json", json_encode($params['conf']));
            putenv('GOOGLE_APPLICATION_CREDENTIALS=/tmp/maingcm.json');
        }

        public function provision() {
            return $this->_createCluster();
        }

        public function available() {
            $clusterManagerClient = new ClusterManagerClient();
            try {
                $cluster = $clusterManagerClient->getCluster($this->params['body']['gcProjectName'], $this->params['body']['gcZone'],$this->name);
                //echo "Name: " . $cluster->getName() , PHP_EOL;
                //echo "Status: " . $cluster->getStatus() , PHP_EOL, PHP_EOL;
                if($cluster->getStatus() == "2"){
                    return true;
                } else {
                    return false;
                }
            } finally {
                $clusterManagerClient->close();
            }
        } 

        private function _createCluster(){
            $p = $this->params;
            $clusterManagerClient = new ClusterManagerClient();
            try {
                $cluster = new Cluster();
                $cluster->setName($this->name);
                $cluster->setZone($this->params['body']['gcZone']);
                $cluster->setDescription($this->params['body']['description']);
                $cluster->setInitialNodeCount($this->params['body']['gcInitialNodeCount']);
                $cluster->setNetwork($this->params['body']['gcNetwork']);
                $cluster->setSubNetwork($this->params['body']['gcSubnetwork']);
                $operation = $clusterManagerClient->createCluster($this->params['body']['gcProjectName'], $this->params['body']['gcZone'], $cluster);
                $_status_table = [
                    0 => 'STATUS_UNSPECIFIED',
                    1 => 'PENDING',
                    2 => 'RUNNING',
                    3 => 'DONE',
                    4 => 'ABORTING'
                ];
                return [
                    "name" => $operation->getName(),
                    "status" => $_status_table[$operation->getStatus()],
                    "message" => $operation->getStatusMessage(),
                    "detail" => $operation->getDetail()
                ];
            }catch(\Exception $e){
                throw $e;
            }finally{
                $clusterManagerClient->close();
            } 
        }

    }