<?php

namespace app\models;
use Ramsey\Uuid\Uuid;
use app\db\ActiveRecord;


use Yii;

/**
 * This is the model class for table "components".
 *
 * @property int $id
 * @property string $name
 * @property string $avatar
 * @property string $type
 * @property string $description
 * @property string $keys
 * @property string $driver
 * @property string $driver_params
 * @property string $parameters
 * @property int $status
 * @property string $created_at
 *
 * @property Deployments[] $deployments
 * @property Environments[] $environments
 * @property DeploymentEnvironmentComponents[] $deploymentEnvironmentComponents
 */
class Components extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'components';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['driver_params','parameters'], 'filter', 'filter' => function($value){ return json_encode($value); }],
            [['name', 'created_at'], 'required'],
            [['description', 'keys', 'driver_params', 'parameters'], 'string'],
            [['status'], 'integer'],
            [['created_at'], 'safe'],
            [['name', 'avatar', 'type', 'driver'], 'string', 'max' => 255],
        ];
    }

    public function afterFind(){
        parent::afterFind();
        $this->driver_params = json_decode($this->driver_params, true);
        $this->parameters = json_decode($this->parameters, true);
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
            'type' => 'Type',
            'description' => 'Description',
            'keys' => 'Keys',
            'driver' => 'Driver',
            'driver_params' => 'Driver Params',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    public function getDeployments()
    {
        return $this->hasMany(Deployments::className(), ['deployment_id' => 'id'])->via('deploymentEnvironmentComponents');
    }

     public function getEnvironments()
    {
        return $this->hasMany(Environments::className(), ['environment_id' => 'id'])->via('deploymentEnvironmentComponents');
    }

    public function getDeploymentEnvironmentComponents()
    {
        return $this->hasMany(DeploymentEnvironmentComponents::className(), ['component_id' => 'id']);
    }
}