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
        public $result;

        public function __construct($name, $params) {
            $this->cloud = new ServiceBuilder();
            $this->name = $name;
            $this->params = $params;
            file_put_contents("/tmp/maingcm.json", json_encode($params['conf']));
            putenv('GOOGLE_APPLICATION_CREDENTIALS=/tmp/maingcm.json');
        }

        public function provision() {
            
        }

        public function available() {
            try {
                $clusterManagerClient = new ClusterManagerClient();
                $cluster = $clusterManagerClient->getCluster($this->params['body']['data']['projectName'], $this->params['body']['data']['zone'],$this->name);
                //echo "Name: " . $cluster->getName() , PHP_EOL;
                //echo "Status: " . $cluster->getStatus() , PHP_EOL, PHP_EOL;
                $this->result = [
                   'name' => $cluster->getName(),   
                   'status' => $cluster->getStatus()   
                ];
                if($cluster->getStatus() == "2"){
                    return true;
                } else {
                    return false;
                }
            } finally {
                $clusterManagerClient->close();
            }
        } 

        public function createCluster(){
            $p = $this->params;
            $clusterManagerClient = new ClusterManagerClient();
            try {
                $cluster = new Cluster();
                $cluster->setName($this->name);
                $cluster->setZone(@$this->params['body']['data']['zone']);
                $cluster->setDescription(@$this->params['body']['data']['description']);
                $cluster->setInitialNodeCount(@$this->params['body']['data']['initialNodeCount']);
                $cluster->setNetwork(@$this->params['body']['data']['network']);
                $cluster->setSubNetwork(@$this->params['body']['data']['subnetwork']);
                $operation = $clusterManagerClient->createCluster(@$this->params['body']['data']['projectName'], $this->params['body']['data']['zone'], $cluster);
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