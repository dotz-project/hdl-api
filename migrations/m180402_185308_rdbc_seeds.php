<?php

use yii\db\Migration;
/**
 * Class m180402_185308_rdbc_seeds
 */
class m180402_185308_rdbc_seeds extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        
        $this->insert('users',[
            "id" => 1,
            "firstname" => "Admin",
            "lastname" => "Admin",
            "cellphone" => "11998826411",
            "email" => "admin@admin",
            "username" => "admin",
            "avatar" => "",
            "password" => '$2a$08$ArKlBJV4wxnUMEcvotUXSudYd4f8NAw33BsSEISWgHMHhnwmvwoi.',
            "status" => "1",
            "created_at" => date('Y-m-d H:i:s'),
        
        ]);

        $this->insert('users',[
            "id" => 100,
            "firstname" => "João",
            "lastname" => "Dev. da Silva",
            "cellphone" => "(11) 99999-9999",
            "email" => "joao@borges",
            "username" => "dev",
            "avatar" => "",
            "password" => '$2a$08$ArKlBJV4wxnUMEcvotUXSudYd4f8NAw33BsSEISWgHMHhnwmvwoi.',
            "status" => "1",
            "created_at" => date('Y-m-d H:i:s'),
        ]);

        $this->insert('users',[
            "id" => 101,
            "firstname" => "Luiz",
            "lastname" => "Devops de Almeida",
            "cellphone" => "(11) 99999-8888",
            "email" => "luiz@devops",
            "username" => "devops",
            "avatar" => "",
            "password" => '$2a$08$ArKlBJV4wxnUMEcvotUXSudYd4f8NAw33BsSEISWgHMHhnwmvwoi.',
            "status" => "1",
            "created_at" => date('Y-m-d H:i:s'),
        ]);

        $auth = Yii::$app->authManager;
        
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->assign($admin, 1);
        
        $dev = $auth->createRole('developer');
        $auth->add($dev);
        $auth->assign($dev, 100);

        $devops = $auth->createRole('devops');
        $auth->add($devops);
        $auth->assign($devops, 101);
        
        $this->_createPermission($auth, 'users.index', 'list of models',[$admin]);
        $this->_createPermission($auth, 'users.create', 'create a new model',[$admin]);
        $this->_createPermission($auth, 'users.update', 'update an existing model',[$admin]);
        $this->_createPermission($auth, 'users.view', 'return the details of a model',[$admin]);
        $this->_createPermission($auth, 'users.delete', 'delete an existing model',[$admin]);
        $this->_createPermission($auth, 'users.options', 'return the allowed HTTP methods',[$admin,$dev,$devops]);
        $this->_createPermission($auth, 'users.me', 'return the data from logged user',[$admin,$dev,$devops]);

        $this->_createPermission($auth, 'environments.index', 'list of models',[$admin,$devops,$dev]);
        $this->_createPermission($auth, 'environments.create', 'create a new model',[$admin,$devops]);
        $this->_createPermission($auth, 'environments.update', 'update an existing model',[$admin,$devops]);
        $this->_createPermission($auth, 'environments.view', 'return the details of a model',[$admin,$devops]);
        $this->_createPermission($auth, 'environments.delete', 'delete an existing model',[$admin,$devops]);
        $this->_createPermission($auth, 'environments.options', 'return the allowed HTTP methods',[$admin,$dev,$devops]);
        
        $this->_createPermission($auth, 'components.index', 'list of models',[$admin,$devops,$dev]);
        $this->_createPermission($auth, 'components.create', 'create a new model',[$admin,$devops]);
        $this->_createPermission($auth, 'components.update', 'update an existing model',[$admin,$devops]);
        $this->_createPermission($auth, 'components.view', 'return the details of a model',[$admin,$devops]);
        $this->_createPermission($auth, 'components.delete', 'delete an existing model',[$admin,$devops]);
        $this->_createPermission($auth, 'components.options', 'return the allowed HTTP methods',[$admin,$dev,$devops]);
        
        $this->_createPermission($auth, 'deployments.index', 'list of models',[$admin,$dev,$devops]);
        $this->_createPermission($auth, 'deployments.create', 'create a new model',[$admin,$devops]);
        $this->_createPermission($auth, 'deployments.update', 'update an existing model',[$admin,$devops]);
        $this->_createPermission($auth, 'deployments.view', 'return the details of a model',[$admin,$devops]);
        $this->_createPermission($auth, 'deployments.delete', 'delete an existing model',[$admin,$devops]);
        $this->_createPermission($auth, 'deployments.options', 'return the allowed HTTP methods',[$admin,$dev,$devops]);
       
        $this->_createPermission($auth, 'deployment_components.index', 'list of models',[$admin,$devops]);
        $this->_createPermission($auth, 'deployment_components.create', 'create a new model',[$admin,$devops]);
        $this->_createPermission($auth, 'deployment_components.update', 'update an existing model',[$admin,$devops]);
        $this->_createPermission($auth, 'deployment_components.view', 'return the details of a model',[$admin,$devops]);
        $this->_createPermission($auth, 'deployment_components.delete', 'delete an existing model',[$admin,$devops]);
        $this->_createPermission($auth, 'deployment_components.options', 'return the allowed HTTP methods',[$admin,$dev,$devops]);

    }

    private function _createPermission(&$auth, $resource, $description, $owners = []){
        $_per = $auth->createPermission($resource);
        $_per->description = $description;
        $auth->add($_per);
        foreach($owners as $owner) {
            $auth->addChild($owner, $_per);
        }
        return $auth; 
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        echo "m180402_185308_rdbc_seeds cannot be reverted.\n";
        $auth = Yii::$app->authManager;
        $auth->removeAll();
        return false;
    }

 
}
