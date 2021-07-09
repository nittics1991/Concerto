<?php

//save()の場合
//domain/serviceは,containerをinjection
//infra/repositoryImpleはserviceで,containerをinjection
//requestをRootDmainEntityのpropertyへ設定はfactoryで行う
class DomainService
{
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function save(Input $input)
    {
        $factory = $this->container->get('domainfactory');
        $domain = $factory->create($input);
        $repository = $this->container->get('repository');
        return $repository->save($domain) :id;
    }
}

class Repository
{
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function save(Domain $domain): id
    {
        $factory = $this->container->get('dataMapperfactory');
        $modelData = $factory->create($domain);

        $dataMapper = $this->container->get('dataMapper');
        $dataMapper->insert($modelData);
        return $modelData->id;
    }
}
