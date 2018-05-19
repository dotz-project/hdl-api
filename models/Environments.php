<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "environments".
 *
 * @property int $id
 * @property string $name
 * @property string $avatar
 * @property string $description
 * @property string $type
 * @property string $gcProjectId
 * @property string $gcProjectName
 * @property string $gcZone
 * @property string $gcNetwork
 * @property string $gcSubnetwork
 * @property string $gcIpv4Cidr
 * @property string $gcStatus
 * @property int $status
 * @property string $created_at
 * @property int $owner_id
 *
 * @property DeploymentComponents[] $deploymentComponents
 * @property Deployments[] $deployments
 * @property Users $owner
 */
class Environments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'environments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'],    'filter',  'filter' => function($value){ return date('Y-m-d H:i:s'); } , 'when'=>function($model) { return $model->isNewRecord; } ],
            [['status'],        'filter',  'filter' => function($value){ return '0'; }, 'when'=>function($model) { return $model->isNewRecord; }],
            [['owner_id'],      'filter',  'filter' => function($value){ return \Yii::$app->user->id; }, 'when'=>function($model) { return $model->isNewRecord; }],
            [['name', 'created_at'], 'required'],
            [['status', 'owner_id', 'gcInitialNodeCount', 'production'], 'integer'],
            [['created_at'], 'safe'],
            [['name', 'avatar', 'description', 'type',  'gcProjectId', 'gcProjectName', 'gcZone', 'gcNetwork', 'gcSubnetwork', 'gcIpv4Cidr', 'gcStatus'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['owner_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'avatar' => 'Avatar',
            'description' => 'Description',
            'type' => 'Type',
            'gcProjectId' => 'Gc Project ID',
            'gcProjectName' => 'Gc Project Name',
            'gcZone' => 'Gc Zone',
            'gcNetwork' => 'Gc Network',
            'gcSubnetwork' => 'Gc Subnetwork',
            'gcInitialNodeCount' => 'Initial Node Count',
            'gcIpv4Cidr' => 'Gc Ipv4 Cidr',
            'gcStatus' => 'Gc Status',
            'status' => 'Status',
            'created_at' => 'Created At',
            'owner_id' => 'Owner ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
   
   

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeploymentEnvironments()
    {
        return $this->hasMany(DeploymentEnvironments::className(), ['environment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(Users::className(), ['id' => 'owner_id']);
    }




    public function afterSave($insert, $changedAttributes)
    {
        if($insert){
            \Yii::$app->rabbitmq->getProducer('CLUSTER.REQUEST.PRODUCER')->publish(json_encode($this->attributes), 'ORCHESTRATOR', 'ORCHESTRATOR.CLUSTER.REQUEST');
        }
        parent::afterSave($insert, $changedAttributes);
    }
}
