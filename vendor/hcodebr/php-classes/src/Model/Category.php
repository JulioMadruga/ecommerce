<?php 

namespace Hcode\Model;

use \Hcode\Model;
use \Hcode\DB\Sql;

class Category extends Model {

    protected $fields = [ "idcategory", "descategory" ];

	public static function listAll(){

	    $sql = new Sql();
	    return $sql->select("SELECT * FROM tb_categories ORDER BY descategory");
    }


    public function save(){


	    $sql = new Sql();


	  $results =  $sql->select("CALL sp_categories_save(:idcategory, :descategory)",array(

           ":idcategory" => $this->getidcategory(),
           ":descategory" =>$this->getdescategory()


        ));


	    $this->setData($results[0]);



    }

    public function get($iduser){

	    $sql = new Sql();

	   $results = $sql->select("SELECT * FROM tb_users a INNER  JOIN tb_persons b USING (idperson) WHERE a.iduser = :iduser",array(

	        ":iduser" => $iduser

        ));

        $this->setData($results[0]);


    }


    public function delete(){

        $sql = new Sql();


        $sql->query("CALL sp_users_delete(:iduser)",array(

            ":iduser" => $this->getiduser()


        ));


           }




}

 ?>