<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "environments".
 *
 * @property int $id
 * @property string $name
 * @property string $alias
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
 * @property DeploymentEnvironmentComponents[] $deploymentEnvironmentComponents
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
            [['name', 'alias', 'created_at'], 'required'],
            [['status', 'owner_id', 'gcInitialNodeCount', 'production'], 'integer'],
            [['created_at'], 'safe'],
            [['name', 'avatar', 'description', 'type',  'gcProjectId', 'gcProjectName', 'gcZone', 'gcNetwork', 'gcSubnetwork', 'gcIpv4Cidr', 'gcStatus'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['alias'], 'unique'],
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

    public function getDeploymentEnvironmentComponents()
    {
        return $this->hasMany(DeploymentEnvironmentComponents::className(), ['environment_id' => 'id']);
    }

    public function getEnvironmentComponents()
    {
        return $this->hasMany(EnvironmentComponents::className(), ['environment_id' => 'id']);
    }

    public function getDeployments()
    {
        return $this->hasMany(Deployments::className(), ['deployment_id' => 'id'])->via('deploymentEnvironmentComponents');
    }

    public function getOwner()
    {
        return $this->hasOne(Users::className(), ['id' => 'owner_id']);
    }
    
}