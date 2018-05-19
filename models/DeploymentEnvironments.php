<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "deployment_environments".
 *
 * @property int $id
 * @property int $deployment_id
 * @property int $environment_id
 * @property int $status
 * @property string $created_at
 *
 * @property Deployments $deployment
 * @property Components $environment
 */
class DeploymentEnvironments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deployment_environments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deployment_id', 'environment_id', 'status'], 'integer'],
            [['created_at'], 'required'],
            [['created_at'], 'safe'],
            [['deployment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Deployments::className(), 'targetAttribute' => ['deployment_id' => 'id']],
            [['environment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Environments::className(), 'targetAttribute' => ['environment_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'deployment_id' => 'Deployment ID',
            'environment_id' => 'Environment ID',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeployment()
    {
        return $this->hasOne(Deployments::className(), ['id' => 'deployment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnvironment()
    {
        return $this->hasOne(Environments::className(), ['id' => 'environment_id']);
    }
}
