<?php

namespace app\models;
use Ramsey\Uuid\Uuid;
use app\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "environment_components".
 *
 * @property int $id
 * @property int $environment_id
 * @property int $component_id
 * @property string $data
 * @property string $feedback
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Components $component
 * @property Environments $environment
 */
class EnvironmentComponents extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'environment_components';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'],    'filter',  'filter' => function($value){ return date('Y-m-d H:i:s'); } , 'when'=>function($model) { return $model->isNewRecord; } ],
            [['status'],        'filter',  'filter' => function($value){ return '0'; }, 'when'=>function($model) { return $model->isNewRecord; }],
          
            [['data','feedback'], 'filter',  'filter' => function($value){ return json_encode($value); }],
         
            [['environment_id', 'component_id', 'created_at'], 'required'],
            [['status'], 'integer'],
            [['data', 'feedback'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['component_id'], 'exist', 'skipOnError' => true, 'targetClass' => Components::className(), 'targetAttribute' => ['component_id' => 'id']],
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

    public function afterSave($insert, $changedAttributes)
    {
        $this->data = json_decode($this->data, true);
        $this->feedback = json_decode($this->feedback, true);

        if($insert){
            switch($this->component_id){
                case 1:
                    \Yii::$app->rabbitmq->getProducer('KUBERNET.CLUSTER.REQUEST.PRODUCER')->publish(json_encode($this->attributes), 'ORCHID', 'KUBERNET.CLUSTER.REQUEST');
                    break;
                case 2:    
                    \Yii::$app->rabbitmq->getProducer('MONGODBATLAS.CLUSTER.REQUEST.PRODUCER')->publish(json_encode($this->attributes), 'ORCHID', 'MONGODBATLAS.CLUSTER.REQUEST');
                    break;
                case 3:    
                    \Yii::$app->rabbitmq->getProducer('KARAFKA.CLUSTER.REQUEST.PRODUCER')->publish(json_encode($this->attributes), 'ORCHID', 'KARAFKA.CLUSTER.REQUEST');
                    break;
                case 4:
                    \Yii::$app->rabbitmq->getProducer('AMQP.CLUSTER.REQUEST.PRODUCER')->publish(json_encode($this->attributes), 'ORCHID', 'AMQP.CLUSTER.REQUEST');
                    break;
                case 5:
                    \Yii::$app->rabbitmq->getProducer('ELASTIC.CLUSTER.REQUEST.PRODUCER')->publish(json_encode($this->attributes), 'ORCHID', 'ELASTIC.CLUSTER.REQUEST');
                    break;
            }
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
    public function getEnvironment()
    {
        return $this->hasOne(Environments::className(), ['id' => 'environment_id']);
    }
}
