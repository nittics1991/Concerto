<?php

//FROM boot


//////////////////////////
//190308
DomainInputInterfaceはget,postなど分けないで、toArray()で取得できるように
query,post,cookieの壁がが無く、propertyを作る
ValidateServiceは作る必要がある場合のみ、無い場合はInputClassでvalidで良いのでは?
InputClassを作るサービスでQueryをSessionに保存する
DBのeditor columnのように、Domainに直接関係ないpropはInputClassに含まない
AuthUserがRepositoryImplで必要なら,containerをinjectionして取得
その為に、ServiceでcontainerにAuthUserをセットする



//////////////////////////
//fw層

class Controller()  //called by WebKernel
{
    $appService = $container->get('appservice');
    $domain = $appService($psrRequest, $psrResponce);
    
    $viewService = $container->get('view'); //AppServiceの一種
    
    //http
    return $viewService->setDomain($domain) //HttpRenderView
        ->buildPsrResponce();
    
    //excel
    $domain = $excelBuldService($domain);
    return $viewService->setDomain($domain) //HttpFileStreamView
        ->buildPsrResponce();
    
    //json
    $domain = $excelBuldService($domain);
    return $viewService->setDomain($domain) //HttpJsonView
        ->buildPsrResponce();
}


//////////////////////////
//app層

class AppService implements Invokable   //extends AppService
{
    public function __invoke(...$args)  //:Domainable //AppService
    {
        $input = $this->convertPsrRequest($args[0]);
        return $rootDomainService($input);
    }
    
    private function convertPsrRequest(PsrRequest $request) :DomainInputInterface;
    {
    }
}


//////////////////////////
//domain層

class RootDomainService
{
    public function __invoke(...$args)  //:Domainable //DomainService
    {
        $input = $args[0];
        if (!$athenticationService->set($input)->fail()) {  //SharedAppService
            return athenticationService->failResponce();
        }
        
        if (!$athorizationService->set($input)->fail()) {   //SharedAppService
            return athorizationService->failResponce();
        }
        
        if (!$validateService->set($input)->fail()) {   //UseCaseAppService
            return validateService->failResponce();
        }
        
        //command
        $domainService($input);
        return $queryService($args[0], $args[1]);   //AppService
        
        //query
        return $domainService($input) :Domainable;
    }
}

abstract class Viewable
{
    public function setDomain(Domainable $domain);
    public function buildPsrResponce() :PsrResponce;
}

class HttpRenderView extends Viewable;
class HttpFileStreamView extends Viewable;
class HttpJsonView extends Viewable;

interface Domainable
{
    public function toArray();
}

class RootDomain implements Domainable



interface DomainInputInterface
{
    /*
    public function query() :Query;
    public function post() :Post;
    public function cookie() :Cookie;
    public function session() :Session;
    */
    public function toArray() :array;
}

class InputClass implements DomainInputInterface
{
    public function toArray() :array;
}

//Validateを省略する場合
class InputClass implements DomainInputInterface, Validatable
{
    public function toArray() :array;
    //どうする?
    public function isValidArray() :bool; //?
    public function fail() :bool; //?
    public function validate(); //?
}
