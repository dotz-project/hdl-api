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
            "id" => 2,
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
            "id" => 3,
            "firstname" => "Harold",
            "lastname" => "Head",
            "cellphone" => "11998826411",
            "email" => "head@admin",
            "username" => "head",
            "avatar" => "",
            "password" => '$2a$08$ArKlBJV4wxnUMEcvotUXSudYd4f8NAw33BsSEISWgHMHhnwmvwoi.',
            "status" => "1",
            "created_at" => date('Y-m-d H:i:s'),
        
        ]);

        $this->insert('users',[
            "id" => 4,
            "firstname" => "Denis",
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
        $auth->assign($dev, 2);

        $devHead = $auth->createRole('developerHead');
        $auth->add($devHead);
        $auth->assign($devHead, 3);

        $devops = $auth->createRole('devops');
        $auth->add($devops);
        $auth->assign($devops, 4);
        
        $this->_createPermission($auth, 'api.users.index', 'list of models',[$admin]);
        $this->_createPermission($auth, 'api.users.create', 'create a new model',[$admin]);
        $this->_createPermission($auth, 'api.users.update', 'update an existing model',[$admin]);
        $this->_createPermission($auth, 'api.users.view', 'return the details of a model',[$admin]);
        $this->_createPermission($auth, 'api.users.delete', 'delete an existing model',[$admin]);
        $this->_createPermission($auth, 'api.users.options', 'return the allowed HTTP methods',[$admin,$dev,$devops]);
        $this->_createPermission($auth, 'api.users.me', 'return the data from logged user',[$admin,$dev,$devops]);
        $this->_createPermission($auth, 'api.users.me.options', 'return the data from logged user',[$admin,$dev,$devops]);

        $this->_createPermission($auth, 'api.environments.index', 'list of models',[$admin,$devops,$dev]);
        $this->_createPermission($auth, 'api.environments.create', 'create a new model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.environments.update', 'update an existing model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.environments.view', 'return the details of a model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.environments.delete', 'delete an existing model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.environments.options', 'return the allowed HTTP methods',[$admin,$dev,$devops]);
        
        $this->_createPermission($auth, 'api.components.index', 'list of models',[$admin,$devops,$dev]);
        $this->_createPermission($auth, 'api.components.create', 'create a new model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.components.update', 'update an existing model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.components.view', 'return the details of a model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.components.delete', 'delete an existing model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.components.options', 'return the allowed HTTP methods',[$admin,$dev,$devops]);
        
        $this->_createPermission($auth, 'api.deployments.index', 'list of models',[$admin,$dev,$devops]);
        $this->_createPermission($auth, 'api.deployments.create', 'create a new model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.deployments.update', 'update an existing model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.deployments.view', 'return the details of a model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.deployments.delete', 'delete an existing model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.deployments.options', 'return the allowed HTTP methods',[$admin,$dev,$devops]);

        $this->_createPermission($auth, 'api.deployment_environment_components.index', 'list of models',[$admin,$devops]);
        $this->_createPermission($auth, 'api.deployment_environment_components.create', 'create a new model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.deployment_environment_components.update', 'update an existing model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.deployment_environment_components.view', 'return the details of a model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.deployment_environment_components.delete', 'delete an existing model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.deployment_environment_components.options', 'return the allowed HTTP methods',[$admin,$dev,$devops]);

        $this->_createPermission($auth, 'api.environment_components.index', 'list of models',[$admin,$devops,$dev]);
        $this->_createPermission($auth, 'api.environment_components.create', 'create a new model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.environment_components.update', 'update an existing model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.environment_components.view', 'return the details of a model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.environment_components.delete', 'delete an existing model',[$admin,$devops]);
        $this->_createPermission($auth, 'api.environment_components.options', 'return the allowed HTTP methods',[$admin,$dev,$devops]);
     
        $this->_createPermission($auth, 'front.environments.list','access to environments screen',[$admin,$dev,$devops]);
        $this->_createPermission($auth, 'front.environments.create','access to environments screen',[$admin,$dev,$devops]);
        $this->_createPermission($auth, 'front.environments.edit','access to environments screen',[$admin,$dev,$devops]);
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
