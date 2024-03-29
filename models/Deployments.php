<?php

namespace app\models;
use Ramsey\Uuid\Uuid;
use app\db\ActiveRecord;

use Yii;

/**
 * This is the model class for table "deployments".
 *
 * @property int $id
 * @property string $name
 * @property string $repository_url
 * @property string $repository_base_path
 * @property string $solutions
 * @property string $dockerfile
 * @property string $deployment_yml
 * @property string $ingress_yml
 * @property string $expose_type
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Environments[] $environments
 * @property Components[] $components
 * @property DeploymentEnvironmentComponents[] $deploymentEnvironmentComponents
 * 
 */
class Deployments extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deployments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'],    'filter',  'filter' => function($value){ return date('Y-m-d H:i:s'); } , 'when'=>function($model) { return $model->isNewRecord; } ],
            [['status'],        'filter',  'filter' => function($value){ return '0'; }, 'when'=>function($model) { return $model->isNewRecord; }],
            [['solutions'],        'filter',  'filter' => function($value){ return json_encode($value); }],
            [['repository_base_path'],        'filter',  'filter' => function($value){ return json_encode($value); }],
            [[ 'status'], 'integer'],
            [['repository_url', 'repository_base_path', 'dockerfile', 'deployment_yml', 'ingress_yml'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'expose_type', 'description', 'domain'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['repository_url'], 'required']


        ];
    }

    public function afterFind(){
        parent::afterFind();
        $this->solutions = json_decode($this->solutions, true);
        $this->repository_base_path = json_decode($this->repository_base_path, true);
    }

    public function afterSave($insert, $changedAttributes) {
        if($insert){
            $data = static::findOne($this->id);
            \Yii::$app->rabbitmq->getProducer('JENKINS.CREATE.JOB.PRODUCER')->publish(json_encode($data->attributes), 'ORCHESTRATOR', 'ORCHESTRATOR.JENKINS.CREATE.JOB');
            unset($data);
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'domain' => 'Domain',
            'description' => 'Description',
            'repository_url' => 'Repository Url',
            'repository_base_path' => 'Repository Base Path',
            'dockerfile' => 'Dockerfile',
            'deployment_yml' => 'Deployment Yml',
            'ingress_yml' => 'Ingress Yml',
            'expose_type' => 'Expose Type',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    public function getComponents()
    {
        return $this->hasMany(Components::className(), ['id' => 'component_id'])->via('deploymentEnvironmentComponents');
    }

    public function getEnvironments()
    {
        return $this->hasMany(Environments::className(), ['id' => 'environment_id'])->via('deploymentEnvironmentComponents');
    }

    public function getDeploymentEnvironmentComponents()
    {
        return $this->hasMany(DeploymentEnvironmentComponents::className(), ['deployment_id' => 'id']);
    }
}