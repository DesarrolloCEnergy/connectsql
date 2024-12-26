<?php

class Connecc
{
    private $pdo;
        public function __construct(){
            try{
                $hostname = 'tcp:transbus-server.database.windows.net,1433';
                $dbname = 'tbg';
                $username = 'transbus-server-admin';
                $password = 'Tr4nsb4s!!.';
                $port = 1433;
				$driver = '{ODBC Driver 17 for SQL Server}';
                $hosport = $hostname.":".$port;
                //require("varl.php");
                ////$this->pdo = new PDO("sqlsrv:server = tcp:".$hosport.";Database=".$dbname.",". $username.",".$password);
                // $this->pdo = new PDO("sqlsrv:server = tcp:transbus-server.database.windows.net; Database = dbtg", "admindbce", "C4n3l0BD!!.");
                ////$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->pdo = new PDO("sqlsrv:server = tcp:transbus-server.database.windows.net,1433; Database= tbg", "transbus-server-admin", "Tr4nsb4s!!.");
				//$this->pdo = new PDO("sqlsrv:server = tcp:transbus-server.database.windows.net,1433; Database = tbg", "transbus-server-admin", "Tr4nsb4s!!.");
    			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch(PDOException $e)
            {
				print("Error connecting to SQL Server.");
                die(print_r($e));
            }
        }

        public function ConSe(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare('SELECT * FROM tbg.ta_1_user as tu
				INNER JOIN tbg.ca_1_company as tc
				on tu.companyUser=tc.idCompany
				INNER JOIN tbg.ca_1_profile as tp
				on tu.profileUser=tp.idProfile');
						
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
                    $entt = new Sesion();

					$entt->__SET('idUser', $r->idUser);
					$entt->__SET('nameUser', $r->nameUser);
					$entt->__SET('fullNameUser', $r->fullNameUser);
					$entt->__SET('passwordUser', $r->passwordUser);
					$entt->__SET('phoneUser', $r->phoneUser);
					$entt->__SET('emailUser', $r->emailUser);
					$entt->__SET('companyUser', $r->companyUser);
					$entt->__SET('nameCompany', $r->nameCompany);
					$entt->__SET('profileUser', $r->profileUser);
					$entt->__SET('nameProfile', $r->nameProfile);
					$entt->__SET('positionUser', $r->positionUser);
					$entt->__SET('baseUser', $r->baseUser);
					$entt->__SET('statusUser', $r->statusUser);
					$result[] = $entt;
				}
                
                return $result;
			} catch (Exception $e)

			{
				die($e->getMessage());
			}
		}
		

}


?>