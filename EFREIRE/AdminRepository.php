<?php

namespace Repository;

use PDO;

class AdminRepository extends EntityRepository
{
    public $orepo;
    public $vrepo;
    public $prepo;
    public $vorepo;
    public $urepo;

    public function __construct(){
      $this -> db = \Manager\PDOManager::getInstance() -> getPdo();
      $this -> orepo = new OptionsRepository;
      $this -> vrepo = new VehiculesRepository;
      $this -> prepo = new PhotosRepository;
      $this -> vorepo = new Vehicules_optionsRepository;
      $this -> urepo = new UsersRepository;
    }



}
?>