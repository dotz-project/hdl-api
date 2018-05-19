<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "deployment_components".
 *
 * @property int $id
 * @property int $deployment_id
 * @property int $component_id
 * @property string $data
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Components $component
 * @property Deployment $deployment
 */
class DeploymentComponents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deployment_components';
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
            [['data','feedback'],        'filter',  'filter' => function($value){ return json_encode($value); }],
            [['deployment_id', 'component_id', 'status'], 'integer'],
            [['data','feedback'], 'string'],
            [['deployment_id', 'component_id', 'created_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['component_id'], 'exist', 'skipOnError' => true, 'targetClass' => Components::className(), 'targetAttribute' => ['component_id' => 'id']],
            [['deployment_id'], 'exist', 'skipOnError' => false, 'targetClass' => Environments::className(), 'targetAttribute' => ['deployment_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'deployment_id' => 'Environment ID',
            'component_id' => 'Component ID',
            'data' => 'Data',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
    public function getEnvironment()
    {
        return $this->hasOne(Environments::className(), ['id' => 'deployment_id']);
    }


    public function afterFind(){
        parent::afterFind();
        if(is_string($this->data)){
            $this->data = json_decode($this->data, true);
        }
        if(is_string($this->feedback)){
            $this->feedback = json_decode($this->feedback, true);
        }
    }
    
    public function afterSave($insert, $changedAttributes) {
        if($insert){
            $data = static::findOne($this->id);
            \Yii::$app->rabbitmq->getProducer('COMPONENTS.REQUEST.PRODUCER')->publish(json_encode($data->attributes), 'ORCHESTRATOR', 'ORCHESTRATOR.COMPONENTS.REQUEST');
            unset($data);
        }
        parent::afterSave($insert, $changedAttributes);
    }
}