<?php

class Connecc
{
    private $pdo;
        public function __construct(){
            try{
                $hostname = 'srv-dbtransbus.database.windows.net';
                $dbname = 'tbg';
                $username = 'Desarrollo';
                $password = 'D3s4rr0ll0!!';
                $port = 1433;
				$driver = '{ODBC Driver 17 for SQL Server}';
                $hosport = $hostname.":".$port;
                //require("varl.php");
                $this->pdo = new PDO("sqlsrv:server=".$hostname.";Database=".$dbname.",". $username.",".$password);
                // $this->pdo = new PDO("sqlsrv:server = tcp:srv-dbtransbus.database.windows.net; Database = dbtg", "admindbce", "C4n3l0BD!!.");
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch(Exception $e)
            {
				print("Error connecting to SQL Server.");
                die($e->getMessage());
            }
        }

        public function ConSe(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare('SELECT * FROM tbg.ta_1_user');
						
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
					$entt->__SET('profileUser', $r->profileUser);
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