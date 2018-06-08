<?php

use yii\db\Migration;

/**
 * Class m180328_130209_init
 */
class m180328_130209_init extends Migration
{
    public function up()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'firstname' => $this->string()->notNull(),
            'lastname' => $this->string()->notNull(),
            'cellphone' => $this->string(),
            'email' => $this->string()->notNull()->unique(),
            'username' => $this->string()->notNull()->unique(),
            'avatar' => $this->string(),
            'password' => $this->string()->notNull(),
            'status' => $this->integer(),
            'created_at' => $this->dateTime()->notNull()
        ], 'ENGINE InnoDB');

        $this->createIndex('idx-users-username', 'users', 'username',true);
        $this->createIndex('idx-users-email', 'users', 'email',true);
        $this->createIndex('idx-users-status', 'users', 'status');
        $this->createIndex('idx-users-firstname', 'users', 'firstname');
        $this->createIndex('idx-users-lastname', 'users', 'lastname');
        $this->createIndex('idx-users-created_at', 'users', 'created_at');

        $this->createTable('environments', [
            'id' => $this->primaryKey(),
            'alias' => $this->string()->notNull()->unique(),
            'name' => $this->string()->notNull()->unique(),
            'avatar' => $this->string(),
            'description' => $this->string(),
            'type' => $this->string(),
            'production' => $this->integer(),
            'gcProjectId' =>  $this->string(),
            'gcProjectName' =>  $this->string(),
            'gcZone' =>  $this->string(),
            'gcNetwork' => $this->string(),
            'gcSubnetwork' => $this->string(),
            'gcInitialNodeCount' => $this->string(),
            'gcIpv4Cidr' => $this->string(),
            'gcStatus' => $this->string(),
            'status' => $this->integer(),
            'created_at' => $this->dateTime()->notNull(),
            'owner_id' => $this->integer()
        ], 'ENGINE InnoDB');
        $this->createIndex('idx-environments-name', 'environments', 'name',true);
        $this->createIndex('idx-environments-alias', 'environments', 'alias',true);
        $this->createIndex('idx-environments-status', 'environments', 'status');
        $this->createIndex('idx-environments-created_at', 'environments', 'created_at');
        $this->addForeignKey('fk-environments-owner_id', 'environments', 'owner_id', 'users', 'id', 'CASCADE');
        
     
        $this->createTable('components', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'avatar' => $this->string(),
            'type' => $this->string(),
            'description' => $this->text(),
            'keys' => $this->text(),
            'driver' => $this->string(),
            'driver_params' => $this->text(),
            'parameters' => $this->text(),
            'status' => $this->integer(),
            'created_at' => $this->dateTime()->notNull()
        ], 'ENGINE InnoDB');        

        $this->createIndex('idx-components-name', 'components', 'name');
        $this->createIndex('idx-components-status', 'components', 'status');
        $this->createIndex('idx-components-created_at', 'components', 'created_at');
        
        $this->createTable('deployments', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'description' => $this->text(),
            'domain' => $this->text(),
            'repository_url' => $this->text(),
            'repository_base_path' => $this->text(),
            'solutions' => $this->text(),
            'dockerfile' => $this->text(),
            'deployment_yml' => $this->text(),
            'ingress_yml' => $this->text(),
            'expose_type' => $this->string(),
            'status' => $this->integer(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()
        ], 'ENGINE InnoDB');        
        
        $this->createIndex('idx-deployments-name', 'deployments', 'name', true);
        $this->createIndex('idx-deployments-status', 'deployments', 'status');
        $this->createIndex('idx-deployments-created_at', 'deployments', 'created_at');
        
        $this->createTable('deployment_environment_components', [
            'id' => $this->primaryKey(),
            'environment_id' => $this->integer()->notNull(),
            'deployment_id' => $this->integer()->notNull(),
            'component_id' => $this->integer()->notNull(),
            'data' => $this->text(),
            'feedback' => $this->text(),
            'status' => $this->integer(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()
        ], 'ENGINE InnoDB');

        $this->addForeignKey('fk-deployment_environment_components-deployment', 'deployment_environment_components', 'deployment_id', 'deployments', 'id', 'CASCADE');
        $this->addForeignKey('fk-deployment_environment_components-environment_id', 'deployment_environment_components', 'environment_id', 'environments', 'id', 'CASCADE');
        $this->addForeignKey('fk-deployment_environment_components-component_id', 'deployment_environment_components', 'component_id', 'components', 'id', 'CASCADE');
        
        $this->createTable('environment_components', [
            'id' => $this->primaryKey(),
            'environment_id' => $this->integer()->notNull(),
            'component_id' => $this->integer()->notNull(),
            'data' => $this->text(),
            'feedback' => $this->text(),
            'status' => $this->integer(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()
        ], 'ENGINE InnoDB');
        $this->createIndex('idx-environment_components-environment_id-component_id','environment_components', ['environment_id', 'component_id'], true);
        $this->addForeignKey('fk-environment_components-environment_id', 'environment_components', 'environment_id', 'environments', 'id', 'CASCADE');
        $this->addForeignKey('fk-environment_components-component_id', 'environment_components', 'component_id', 'components', 'id', 'CASCADE');


    }

    public function down()
    {
        echo "m180328_130209_init cannot be reverted.\n";
        $this->dropTable('deployment_environment_components');
        $this->dropTable('deployments');
        $this->dropTable('components');
        $this->dropTable('environments');
        $this->dropTable('users');
        return false;
    }
}