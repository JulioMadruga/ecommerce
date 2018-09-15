<?php
/**
 * Created by PhpStorm.
 * User: JULIO
 * Date: 14/09/2018
 * Time: 23:31
 */

namespace Hcode;


class PageAdmin extends Page
{

   public  function __construct(array $opts = array(), $tpl_dir = "/views/admin/")
   {
       parent::__construct($opts, $tpl_dir);
   }


}