<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "deployment_environment_components".
 *
 * @property int $id
 * @property int $environment_id
 * @property int $deployment_id
 * @property int $component_id
 * @property string $data
 * @property string $feedback
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Components $component
 * @property Deployments $deployment
 * @property Environments $environment
 */
class DeploymentEnvironmentComponents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deployment_environment_components';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['updated_at'],    'filter',  'filter' => function($value){ return date('Y-m-d H:i:s'); } , 'when'=>function($model) { return !$model->isNewRecord; } ],
            [['created_at'],    'filter',  'filter' => function($value){ return date('Y-m-d H:i:s'); } , 'when'=>function($model) { return $model->isNewRecord; } ],
            [['status'],        'filter',  'filter' => function($value){ return '0'; }, 'when'=>function($model) { return $model->isNewRecord; }],
            [['data'],        'filter',  'filter' => function($value){ @$value['name'] = $this->deployment->name . "-" . $this->environment->alias; return $value; }],
            [['data','feedback'],        'filter',  'filter' => function($value){ return json_encode($value); }],
            [['deployment_id', 'component_id', 'environment_id' ,  'status'], 'integer'],
            [['data','feedback'], 'string'],
            [['deployment_id', 'environment_id' , 'component_id', 'created_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['component_id'], 'exist', 'skipOnError' => true, 'targetClass' => Components::className(), 'targetAttribute' => ['component_id' => 'id']],
            [['deployment_id'], 'exist', 'skipOnError' => false, 'targetClass' => Deployments::className(), 'targetAttribute' => ['deployment_id' => 'id']],
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
            'environment_id' => 'Environment ID',
            'deployment_id' => 'Deployment ID',
            'component_id' => 'Component ID',
            'data' => 'Data',
            'feedback' => 'Feedback',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    public function afterFind(){
        parent::afterFind();
        $this->data = json_decode($this->data, true);
        $this->feedback = json_decode($this->feedback, true);
    }
    
    public function afterSave($insert, $changedAttributes) {
        if($insert){
            $data = static::findOne($this->id);
            $attrs = $data->attributes; 
            \Yii::$app->rabbitmq->getProducer('COMPONENTS.REQUEST.PRODUCER')->publish(json_encode($attrs), 'ORCHESTRATOR', 'ORCHESTRATOR.COMPONENTS.REQUEST');
            unset($data);
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComponent()
    {
        return $this->hasOne(Components::className(), ['id' => 'component_id']);
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
