<?php

use Codeception\Util\HttpCode;
use Helper\Api;
class MainCest
{
    public $token = [];
    public $registered;
    public $userCreated;
    public $dddGroupCreated;
    public $handoutsCreated;
    public $shareCreated;
    
    public function _before(ApiTester $I){}
    public function _after(ApiTester $I){}

    private function _testDefaultAPIHeaders(ApiTester &$I){
        $I->seeHttpHeader('Access-Control-Allow-Headers', 'Authorization, Content-Type');
        $I->seeHttpHeader('Access-Control-Allow-Origin', '*');
        $I->seeHttpHeader('Access-Control-Expose-Headers', 'X-Pagination-Current-Page, X-Pagination-Page-Count, X-Pagination-Per-Page, X-Pagination-Total-Count, X-Rate-Limit-Limit, X-Rate-Limit-Remaining, X-Rate-Limit-Reset');
        $I->seeHttpHeader('Access-Control-Request-Method', 'POST, PUT, GET, PATCH, DELETE, OPTIONS, HEAD');
    }
    public function loginAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/users/login', ['username' => 'admin', 'password' => '123456']);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContains('"Bearer');
        $this->_testDefaultAPIHeaders($I);
        $this->token['admin'] = json_decode($I->grabResponse());
    }    
    public function loginFail(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/users/login', ['username' => 'invalidusername', 'password' => 'invalidpassword']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 401
        $I->seeResponseIsJson();
    }    
    public function register(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/users/register', ['firstname'=>'Tester', 'lastname'=>'Sobrenome', 'cellphone'=>'(11) 99999-'.rand(1000,9999), 'email'=>date('YmdHisu').'@localhost', 'username' => 'tester-'.date('YmdHis.u'), 'password' => '123456','avatar'=>'']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED); // 200
        $I->seeResponseIsJson();
        $this->registered = json_decode($I->grabResponse());
    }
    public function loginVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/users/login', ['username' => $this->registered->username, 'password' => '123456']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContains('"Bearer');
        $this->token['vendedor'] = json_decode($I->grabResponse());
    }
    public function loginVendedorFail(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/users/login', ['username' => $this->registered->username, 'password' => 'INVALIDPASS']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 401
        $I->seeResponseIsJson();
    }
    public function usersCreateByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendPOST('/users', ['firstname'=>'Tester', 'lastname'=>'Sobrenome', 'cellphone'=>'(11) 99899-'.rand(1000,9999), 'email'=>uniqid().'@localhost', 'username' => 'tester-'.uniqid(), 'password' => '123456','avatar'=>'']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED); // 201
        $I->seeResponseIsJson();
        $this->userCreated = json_decode($I->grabResponse());
    }
    public function usersIndexByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendGET('/users');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }
    public function usersUpdateByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendPATCH('/users/'.$this->userCreated->id, ['firstname'=>'Tester 2', 'lastname'=>'Sobrenome 2']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $this->userUpdated = json_decode($I->grabResponse());
    }
    public function usersViewByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendGET('/users/'.$this->userCreated->id);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }
    public function usersDeleteByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendDELETE('/users/'.$this->userCreated->id);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NO_CONTENT); // 200
    }
    public function usersCreateByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendPOST('/users', ['firstname'=>'Tester', 'lastname'=>'Sobrenome', 'cellphone'=>'(11) 99899-'.rand(1000,9999), 'email'=>uniqid().'@localhost', 'username' => 'tester-'.uniqid(), 'password' => '123456','avatar'=>'']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN); // 201
        $I->seeResponseIsJson();
        $this->userCreated = json_decode($I->grabResponse());
    }
    public function usersIndexByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendGET('/users');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN); // 200
        $I->seeResponseIsJson();
    }
    public function usersUpdateByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendPATCH('/users/1', ['firstname'=>'Tester 2', 'lastname'=>'Sobrenome 2']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN); // 200
        $I->seeResponseIsJson();
        $this->userUpdated = json_decode($I->grabResponse());
    }
    public function usersViewByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendGET('/users/1');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN); // 200
        $I->seeResponseIsJson();
    }
    public function usersDeleteByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendDELETE('/users/1');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN); // 200
    }
    public function usersCreateByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/users', ['firstname'=>'Tester', 'lastname'=>'Sobrenome', 'cellphone'=>'(11) 99899-'.rand(1000,9999), 'email'=>uniqid().'@localhost', 'username' => 'tester-'.uniqid(), 'password' => '123456','avatar'=>'']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 201
        $I->seeResponseIsJson();
        $this->userCreated = json_decode($I->grabResponse());
    }
    public function usersIndexByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/users');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 200
        $I->seeResponseIsJson();
    }
    public function usersUpdateByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH('/users/1', ['firstname'=>'Tester 2', 'lastname'=>'Sobrenome 2']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 200
        $I->seeResponseIsJson();
        $this->userUpdated = json_decode($I->grabResponse());
    }
    public function usersViewByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/users/1');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 200
        $I->seeResponseIsJson();
    }
    public function usersDeleteByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDELETE('/users/1');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 200
    }
    public function dddGroupsCreateByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendPOST('/ddd-groups', ['name'=>'REGIONAL TESTE' . uniqid(), 'ddds'=>'20,21,22,23,24,25,26,'. uniqid()]);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED); // 201
        $I->seeResponseIsJson();
        $this->dddGroupCreated = json_decode($I->grabResponse());
    }
    public function dddGroupsIndexByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendGET('/ddd-groups');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }
    public function dddGroupsUpdateByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendPATCH('/ddd-groups/'.$this->dddGroupCreated->id,['name'=>'teste...'.uniqid(),'ddds'=>'45,46,47,48,'.uniqid()]);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }
    public function dddGroupsViewByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendGET('/ddd-groups/'.$this->dddGroupCreated->id);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
       
    }
    public function dddGroupsDeleteByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendDELETE('/ddd-groups/'.$this->dddGroupCreated->id);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NO_CONTENT); // 200
    }
    public function dddGroupsCreateByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendPOST('/ddd-groups', ['name'=>'REGIONAL TESTE' . uniqid(), 'ddds'=>'20,21,22,23,24,25,26,'. uniqid()]);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN); // 201
        $I->seeResponseIsJson();
        $this->dddGroupCreated = json_decode($I->grabResponse());
    }
    public function dddGroupsIndexByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendGET('/ddd-groups');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }
    public function dddGroupsUpdateByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendPATCH('/ddd-groups/1',['name'=>'teste...'.uniqid(),'ddds'=>'45,46,47,48,'.uniqid()]);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN); // 200
        $I->seeResponseIsJson();
    }
    public function dddGroupsViewByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendGET('/ddd-groups/1');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
       
    }
    public function dddGroupsDeleteByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendDELETE('/ddd-groups/1');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN); // 200
    }
    public function dddGroupsCreateByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/ddd-groups', ['name'=>'REGIONAL TESTE' . uniqid(), 'ddds'=>'20,21,22,23,24,25,26,'. uniqid()]);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 201
        $I->seeResponseIsJson();
        $this->dddGroupCreated = json_decode($I->grabResponse());
    }
    public function dddGroupsIndexByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/ddd-groups');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }
    public function dddGroupsUpdateByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH('/ddd-groups/1',['name'=>'teste...'.uniqid(),'ddds'=>'45,46,47,48,'.uniqid()]);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 200
        $I->seeResponseIsJson();
    }
    public function dddGroupsViewByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/ddd-groups/1');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
       
    }
    public function dddGroupsDeleteByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDELETE('/ddd-groups/1');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 200
    }
    public function handoutsCreateByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendPOST('/handouts', ['title'=>'Titulo' . uniqid(), 'description'=>'descricao...'. uniqid(),'started_at'=>'2018-02-19 00:00:00', 'expired_at'=>'2019-02-19 00:00:00']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED); // 201
        $I->seeResponseIsJson();
        $this->handoutsCreated = json_decode($I->grabResponse());
    }
    public function handoutsIndexByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendGET('/handouts');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }
    public function handoutsUpdateByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendPATCH('/handouts/'.$this->handoutsCreated->id,['title'=>'teste...'.uniqid()]);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }
    public function handoutsViewByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendGET('/handouts/'.$this->handoutsCreated->id);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }
    public function handoutsDeleteByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendDELETE('/handouts/'.$this->handoutsCreated->id);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NO_CONTENT); // 200
    }
    public function handoutsCreateByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendPOST('/handouts', ['title'=>'Titulo' . uniqid(), 'description'=>'descricao...'. uniqid(),'started_at'=>'2018-02-19 00:00:00', 'expired_at'=>'2019-02-19 00:00:00']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN); // 201
        $I->seeResponseIsJson();
        $this->handoutsCreated = json_decode($I->grabResponse());
    }
    public function handoutsIndexByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendGET('/handouts');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN); // 200
        $I->seeResponseIsJson();
    }
    public function handoutsUpdateByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendPOST('/handouts', ['title'=>'Titulo' . uniqid(), 'description'=>'descricao...'. uniqid(),'started_at'=>'2018-02-19 00:00:00', 'expired_at'=>'2019-02-19 00:00:00']);
        $this->handoutsCreated = json_decode($I->grabResponse());
        
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendPATCH('/handouts/'.$this->handoutsCreated->id,['title'=>'teste...'.uniqid()]);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN); // 200
        //$I->seeResponseCodeIs(\Codeception\Util\HttpCode::NOT_FOUND); // 200
        $I->seeResponseIsJson();
    }
    public function handoutsViewByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendGET('/handouts/'.$this->handoutsCreated->id);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN); // 200
        $I->seeResponseIsJson();
    }
    public function handoutsDeleteByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendDELETE('/handouts/'.$this->handoutsCreated->id);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN); // 200

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendDELETE('/handouts/'.$this->handoutsCreated->id);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NO_CONTENT); // 200
        
    }
    public function handoutsCreateByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/handouts', ['title'=>'Titulo' . uniqid(), 'description'=>'descricao...'. uniqid(),'started_at'=>'2018-02-19 00:00:00', 'expired_at'=>'2019-02-19 00:00:00']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 201
        $I->seeResponseIsJson();
        $this->handoutsCreated = json_decode($I->grabResponse());
    }
    public function handoutsIndexByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/handouts');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 200
        $I->seeResponseIsJson();
    }
    public function handoutsUpdateByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH('/handouts/1',['title'=>'teste...'.uniqid()]);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 200
        $I->seeResponseIsJson();
    }
    public function handoutsViewByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/handouts/1');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 200
        $I->seeResponseIsJson();
    }
    public function handoutsDeleteByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDELETE('/handouts/1');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 200
    }
    public function handoutsAllByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/handouts/all',['ddd'=>'11']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }
    public function handoutsDetailByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/handouts/detail',['id'=>'1']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
    }

    public function handoutSheetsCreateByAdmin(ApiTester $I){
        
    }
    public function handoutSheetsIndexByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendGET('/handout-sheets');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }
    public function handoutSheetsUpdateByAdmin(ApiTester $I){}
    public function handoutSheetsViewByAdmin(ApiTester $I){}
    public function handoutSheetsDeleteByAdmin(ApiTester $I){}

    public function handoutDddGroupCreateByAdmin(ApiTester $I){}
    public function handoutDddGroupIndexByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendGET('/handout-ddd-group');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }
    public function handoutDddGroupUpdateByAdmin(ApiTester $I){}
    public function handoutDddGroupViewByAdmin(ApiTester $I){}
    public function handoutDddGroupDeleteByAdmin(ApiTester $I){}
    
    
    public function shareCreateByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendPOST('/share', ['firstname'=>'nome_' . uniqid(), 'lastname'=>'sobre_' . uniqid(),'cellphone'=>'2018-02-19 00:00:00', 'email'=>'2019-02-19 00:00:00', 'link' => '']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED); // 201
        $I->seeResponseIsJson();
        $this->shareCreated = json_decode($I->grabResponse());
    }
    public function shareIndexByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendGET('/share');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }
    public function shareUpdateByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendPATCH('/share/'.$this->shareCreated->id, ['firstname'=>'nome_' . uniqid(), 'lastname'=>'sobre_' . uniqid(),'cellphone'=>'2018-02-19 00:00:00', 'email'=>'2019-02-19 00:00:00', 'link' => '']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 201
        $I->seeResponseIsJson();
    }
    public function shareViewByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendGET('/share/'.$this->shareCreated->id);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 201
        $I->seeResponseIsJson();
    }
    public function shareDeleteByAdmin(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendDELETE('/share/'.$this->shareCreated->id);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NO_CONTENT); // 201
    }
    public function shareSendByAdmin(ApiTester $I){
        return true;
    }
    public function shareOpenByAdmin(ApiTester $I){
        return true;
    }
    public function shareCreateByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendPOST('/share', ['firstname'=>'nome_' . uniqid(), 'lastname'=>'sobre_' . uniqid(),'cellphone'=>'2018-02-19 00:00:00', 'email'=>'2019-02-19 00:00:00', 'link' => '']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED); // 201
        $I->seeResponseIsJson();
        $this->shareCreated = json_decode($I->grabResponse());
    }
    public function shareIndexByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendGET('/share');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }
    public function shareUpdateByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendPATCH('/share/'.$this->shareCreated->id, ['firstname'=>'nome_' . uniqid(), 'lastname'=>'sobre_' . uniqid(),'cellphone'=>'2018-02-19 00:00:00', 'email'=>'2019-02-19 00:00:00', 'link' => '']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN); // 201
        $I->seeResponseIsJson();
    }
    public function shareViewByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendGET('/share/'.$this->shareCreated->id);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 201
        $I->seeResponseIsJson();
    }
    public function shareDeleteByVendedor(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['vendedor']);
        $I->sendDELETE('/share/'.$this->shareCreated->id);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN); // 201
    }
    public function shareCreateByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/share', ['firstname'=>'nome_' . uniqid(), 'lastname'=>'sobre_' . uniqid(),'cellphone'=>'2018-02-19 00:00:00', 'email'=>'2019-02-19 00:00:00', 'link' => '']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 201
        $I->seeResponseIsJson();
        $this->shareCreated = json_decode($I->grabResponse());
    }
    public function shareIndexByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/share');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 200
        $I->seeResponseIsJson();
    }
    public function shareUpdateByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPATCH('/share/1', ['firstname'=>'nome_' . uniqid(), 'lastname'=>'sobre_' . uniqid(),'cellphone'=>'2018-02-19 00:00:00', 'email'=>'2019-02-19 00:00:00', 'link' => '']);
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 201
        $I->seeResponseIsJson();
    }
    public function shareViewByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/share/1');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 201
        $I->seeResponseIsJson();
    }
    public function shareDeleteByGuest(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDELETE('/share/1');
        $this->_testDefaultAPIHeaders($I);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::UNAUTHORIZED); // 201
    }
    public function handoutsAllValidityAndActive(ApiTester $I){
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $handouts = [];
        //Cadastra 3 handouts ativos e na vigencia
        for($i = 1; $i <= 3; $i++){
            $data = [
                "title" => "Grupo 1", 
                "description" => "handouts ativos e na vigencia",
                "started_at" => date("Y-m-d H:i:s", strtotime("-1 days")),
                "status" => 1,
                "expired_at" => date("Y-m-d H:i:s", strtotime("+1 years"))
            ];
            $I->sendPOST('/handouts', $data);
            $I->seeResponseIsJson();
            $handouts[] = json_decode($I->grabResponse());
        }
        
        //Cadastra 2 handouts ativos fora da vigencia (1 vencido outro a publicar)
        $data = [
            'title'=>'Grupo 2.1', 
            'description'=>'handouts ativos fora da vigencia (1 vencido)',
            'status' => 1,
            'started_at'=>date('Y-m-d H:i:s',strtotime("-10 days")), 
            'expired_at'=>date('Y-m-d H:i:s',strtotime("-1 days"))
        ];
        $I->sendPOST('/handouts', $data);
        $I->seeResponseIsJson();
        $handouts[] = json_decode($I->grabResponse());

        $data = [
            'title'=>'Grupo 2.2', 
            'description'=>'handouts ativos fora da vigencia (a publicar)',
            'status' => 1,
            'started_at'=>date('Y-m-d H:i:s',strtotime("+3 days")), 
            'expired_at'=>date('Y-m-d H:i:s',strtotime("+30 days"))
        ];
        $I->sendPOST('/handouts', $data);
        $I->seeResponseIsJson();
        $handouts[] = json_decode($I->grabResponse());

        //Cadastra 1 handout na vigencia mas inativo
        $data = [
            'title'=>'Grupo 3', 
            'description'=>'handout na vigencia mas inativo',
            'status' => 0,
            'started_at'=>date('Y-m-d H:i:s',strtotime("-1 years")), 
            'expired_at'=>date('Y-m-d H:i:s',strtotime("+1 years"))
        ];
        $I->sendPOST('/handouts', $data);
        $I->seeResponseIsJson();
        $handouts[] = json_decode($I->grabResponse());
        
        $ddd = rand(111111,999999);
        $I->sendPOST('/ddd-groups', ['name'=>'REGIONAL TESTE' . uniqid(), 'ddds'=>$ddd.','. uniqid()]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED); // 201
        $I->seeResponseIsJson();
        $dddGroup = json_decode($I->grabResponse());
        //echo $I->grabResponse() . PHP_EOL;
        
        foreach($handouts as $h){
            $I->haveHttpHeader('Content-Type', 'application/json');
            $I->haveHttpHeader('Authorization', $this->token['admin']);
            $I->sendPOST('/handout-ddd-groups', [
                "handout_id" => $h->id,
                "ddd_group_id" => $dddGroup->id
            ]);
            //echo $I->grabResponse() . PHP_EOL;
            $I->seeResponseCodeIs(\Codeception\Util\HttpCode::CREATED); // 201
        }

        $I->sendGET('/handouts/all',['ddd'=>$ddd,"page"=>0,"per-page"=>1000,"order"=>"id"]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
       
        $resp = json_decode($I->grabResponse());
        /*
        print_r($resp);
        print_r($handouts);
        */

        foreach($resp as $k => $h){
            $I->seeResponseMatchesJsonType([
                'id' => 'integer:='.$handouts[$k]->id,
                'title' => 'string',
                'description' => 'string',
                'started_at' => 'string',
                'expired_at' => 'string'
            ], '$['.$k.']');
        }

        $I->dontSeeResponseMatchesJsonType([
            'id' => 'integer:='.$handouts[3]->id,
            'title' => 'string',
            'description' => 'string',
            'started_at' => 'string',
            'expired_at' => 'string'
        ], '$[*]');
        $I->dontSeeResponseMatchesJsonType([
            'id' => 'integer:='.$handouts[4]->id,
            'title' => 'string',
            'description' => 'string',
            'started_at' => 'string',
            'expired_at' => 'string'
        ], '$[*]');
        $I->dontSeeResponseMatchesJsonType([
            'id' => 'integer:='.$handouts[4]->id,
            'title' => 'string',
            'description' => 'string',
            'started_at' => 'string',
            'expired_at' => 'string'
        ], '$[*]');
        
        foreach($handouts as $k => $h){
            //limpa todos os handouts
            $I->haveHttpHeader('Authorization', $this->token['admin']);
            $I->sendDELETE('/handouts/'.$h->id);
            $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NO_CONTENT);  
        }
        //limpa o grupo criado
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Authorization', $this->token['admin']);
        $I->sendDELETE('/ddd-groups/'.$dddGroup->id);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NO_CONTENT); // 200
    }    
    public function handoutsDetailValidityAndActive(ApiTester $I){

    }
}