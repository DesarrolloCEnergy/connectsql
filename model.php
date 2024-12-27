<?php

class Model
{
        private $pdo;
		private $stm;
        public function __construct(){
			try{
                require("varl.php");
				//$this->pdo = new PDO("sqlsrv:server = tcp:transbus-server.database.windows.net,1433; Database = tbg", "transbus-server-admin", "Tr4nsb4s!!.");
				$this->pdo = new PDO("sqlsrv:server = $hostname; Database= $dbname", "$username", "$password");
    			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch(Exception $e)
			{die($e->getMessage());}
        }
		public function ConClose(){
			$this->stm->closeCursor();
			$this->stm = null;
			$this->pdo = null;
		}


		////////////   ***user***   /////////

		public function UserAdd(Enti $data){
			try {
				$sql = "INSERT INTO tbg.ta_1_user (nameUser,fullNameUser,passwordUser,emailUser,phoneUser,companyUser,profileUser, positionUser,baseUser,dateUser,timeUser) 
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('nameUser'),
					$data->__GET('fullNameUser'),
					$data->__GET('passwordUser'),
					$data->__GET('emailUser'),
					$data->__GET('phoneUser'),
					$data->__GET('companyUser'),
					$data->__GET('profileUser'),
					$data->__GET('positionUser'),
					$data->__GET('baseUser'),
					$data->__GET('dateUser'),
					$data->__GET('timeUser')
					)
				);
				$idUser = $this->pdo->lastInsertId();
				return $idUser;
			} catch (Exception $e) 
			{die($e->getMessage());}
			return $res;
		}
		public function UserList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_1_user INNER JOIN tbg.ca_7_position ON ta_1_user.positionUser = ca_7_position.idPosition INNER JOIN tbg.ca_1_company ON ta_1_user.companyUser=ca_1_company.idCompany WHERE ta_1_user.statusUser = 1 AND ta_1_user.profileUser != 1 ORDER BY ta_1_user.idUser DESC ");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idUser', $r->idUser);
					$ent->__SET('nameUser', $r->nameUser);
					$ent->__SET('fullNameUser', $r->fullNameUser);
					$ent->__SET('emailUser', $r->emailUser);
					$ent->__SET('phoneUser', $r->phoneUser);
					$ent->__SET('companyUser', $r->companyUser);
					$ent->__SET('positionUser', $r->positionUser);
					$ent->__SET('statusUser', $r->statusUser);
					$ent->__SET('namePosition', $r->namePosition);
					$ent->__SET('nameCompany', $r->nameCompany);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function UserPosition($positionUser, $baseUser = ""){
			try 
			{
				$e_SQL = "SELECT
				  COUNT(t1u.idUser) AS userCountPosition
				FROM tbg.ca_7_position AS c7p
				LEFT JOIN tbg.ta_1_user AS t1u ON t1u.positionUser = c7p.idPosition
				WHERE idPosition = '{$positionUser}'";
				if($baseUser != ""){
					$e_SQL .= " AND baseUser LIKE '%\"{$baseUser}\"%'";
				}
				$e_SQL .= " GROUP BY c7p.idPosition";
				$result=array();
				$stm = $this->pdo->prepare($e_SQL);	
				$stm->execute();
				$ent = new Enti();
				$eCount = 0;
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$eCount = is_numeric($r->userCountPosition) ? $r->userCountPosition : 0;
				}
				$ent->__SET('userCountPosition', $eCount + 1);
				$result[] = $ent;
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function UserListU($euid){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_1_user INNER JOIN tbg.ca_7_position ON ta_1_user.positionUser = ca_7_position.idPosition INNER JOIN tbg.ca_1_company ON ta_1_user.companyUser=ca_1_company.idCompany WHERE ta_1_user.statusUser = 1 AND ta_1_user.idUser = ? ORDER BY ta_1_user.idUser DESC ");	
				$stm->execute(array($euid));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idUser', $r->idUser);
					$ent->__SET('nameUser', $r->nameUser);
					$ent->__SET('fullNameUser', $r->fullNameUser);
					$ent->__SET('emailUser', $r->emailUser);
					$ent->__SET('phoneUser', $r->phoneUser);
					$ent->__SET('companyUser', $r->companyUser);
					$ent->__SET('positionUser', $r->positionUser);
					$ent->__SET('baseUser', $r->baseUser);
					$ent->__SET('statusUser', $r->statusUser);
					$ent->__SET('namePosition', $r->namePosition);
					$ent->__SET('nameCompany', $r->nameCompany);
					$result[] = $ent;
				}
				//print_r($result)
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function UserListPU($id){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_1_user AS t1u 
				INNER JOIN tbg.ta_7_unloading AS t7u ON t1u.idUser=t7u.userUnloading 
				WHERE t7u.idUnloading = ? ");	
				$stm->execute(array($id));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('nameUser', $r->nameUser);
					$result[] = $ent;
				}
				//print_r($result);
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function UserListPC($id){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_1_user AS t1u 
				INNER JOIN tbg.ta_7_unloading AS t7u ON t1u.idUser=t7u.userUnloadingC 
				WHERE t7u.idUnloading = ? ");	
				$stm->execute(array($id));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('nameUser', $r->nameUser);
					$result[] = $ent;
				}
				//print_r($result);
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function UserListPE($id){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_1_user AS t1u 
				INNER JOIN tbg.ta_7_unloading AS t7u ON t1u.idUser=t7u.userUnloadingEPS 
				WHERE t7u.idUnloading = ? ");	
				$stm->execute(array($id));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('nameUser', $r->nameUser);
					$result[] = $ent;
				}
				//print_r($result);
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function UserListPT($id){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_1_user AS t1u 
				INNER JOIN tbg.ta_7_unloading AS t7u ON t1u.idUser=t7u.userUnloadingT 
				WHERE t7u.idUnloading = ? ");	
				$stm->execute(array($id));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('nameUser', $r->nameUser);
					$result[] = $ent;
				}
				//print_r($result);
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function UserEdit(Enti $data){
			try 
			{
					$sql = "UPDATE tbg.ta_1_user SET 
								fullNameUser = ?,
								emailUser = ?,
								phoneUser = ?,
								baseUser = ?
							WHERE idUser = ?";
					$res=$this->pdo->prepare($sql)->execute(
						array(
							$data->__GET('fullNameUser'),
							$data->__GET('emailUser'),
							$data->__GET('phoneUser'),
							$data->__GET('baseUser'),
							$data->__GET('idUser')
						)
					);
				
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function UserInactive(Enti $data){
			try 
			{
				$sql = "UPDATE tbg.ta_1_user SET 
							statusUser = ?
						WHERE idUser = ?";
				$res=$this->pdo->prepare($sql)
					->execute(
					array(
					$data->__GET('statusUser'),
						$data->__GET('idUser')
						)
					);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}

		////////////   ***employee***   /////////

		public function EmployeeAdd(Enti $data){
			//print_r($data);
			try {
				$sql = "INSERT INTO tbg.ta_7_employee (codeEmployee,namesEmployee,lastnameEmployee,mlastnameEmployee,
				companyEmployee,baseEmployee,nssEmployee,rfcEmployee,curpEmployee,departamentEmployee,positionEmployee,
				dateadmissionEmployee,phoneEmployee,emailEmployee,countryEmployee,stateEmployee,municipalityEmployee,
				colonyEmployee,streeEmployee,cpEmployee,birthdaydateEmployee,ageEmployee,genderEmployee,maritalEmployee,
				nationalityEmployee,statebirthEmployee,municipalitybirthEmployee,licenseDriverEmployee,companyemailEmployee,bankEmployee,
				accountEmployee,interbankcodeEmployee,beneficiaryEmployee,beneficiaryrelationshipEmployee,
				benefeciaryrfcEmployee,beneficiarycountryEmployee,beneficiarystateEmployee,beneficiarymunicipalityEmployee,
				beneficiarycolonyEmployee,beneficiarystreeEmployee,beneficiarycpEmployee,ifinfonavitcreditEmployee,
				creditnumberinfonavitEmployee,totalinfonavitEmployee,amountPaidInfonavitEmployee,
				balanceInfonavitEmployee,bimonthlydiscountEmployee,
				grossmonthlysalaryEmployee,socialpreventionEmployee,indasaEmployee,
				bonusdaysEployee,holidaysEmployee,vacationbonusporEmployee,
				imgEmployee,dateEmployee,timeEmployee) 
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
					?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
					?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('codeEmployee'),
					$data->__GET('namesEmployee'),
					$data->__GET('lastnameEmployee'),
					$data->__GET('mlastnameEmployee'),
					$data->__GET('companyEmployee'),
					$data->__GET('baseEmployee'),
					$data->__GET('nssEmployee'),
					$data->__GET('rfcEmployee'),
					$data->__GET('curpEmployee'),
					$data->__GET('departamentEmployee'),
					$data->__GET('positionEmployee'),
					$data->__GET('dateadmissionEmployee'),
					$data->__GET('phoneEmployee'),
					$data->__GET('emailEmployee'),
					$data->__GET('countryEmployee'),
					$data->__GET('stateEmployee'),
					$data->__GET('municipalityEmployee'),
					$data->__GET('colonyEmployee'),
					$data->__GET('streeEmployee'),
					$data->__GET('cpEmployee'),
					$data->__GET('birthdaydateEmployee'),
					$data->__GET('ageEmployee'),
					$data->__GET('genderEmployee'),
					$data->__GET('maritalEmployee'),
					$data->__GET('nationalityEmployee'),
					$data->__GET('statebirthEmployee'),
					$data->__GET('municipalitybirthEmployee'),
					$data->__GET('licenseDriverEmployee'),
					$data->__GET('companyemailEmployee'),
					$data->__GET('bankEmployee'),
					$data->__GET('accountEmployee'),
					$data->__GET('interbankcodeEmployee'),
					$data->__GET('beneficiaryEmployee'),
					$data->__GET('beneficiaryrelationshipEmployee'),
					$data->__GET('benefeciaryrfcEmployee'),
					$data->__GET('beneficiarycountryEmployee'),
					$data->__GET('beneficiarystateEmployee'),
					$data->__GET('beneficiarymunicipalityEmployee'),
					$data->__GET('beneficiarycolonyEmployee'),
					$data->__GET('beneficiarystreeEmployee'),
					$data->__GET('beneficiarycpEmployee'),
					$data->__GET('ifinfonavitcreditEmployee'),
					$data->__GET('creditnumberinfonavitEmployee'),
					$data->__GET('totalinfonavitEmployee'),
					$data->__GET('amountPaidInfonavitEmployee'),
					$data->__GET('balanceInfonavitEmployee'),
					$data->__GET('bimonthlydiscountEmployee'),
					$data->__GET('grossmonthlysalaryEmployee'),
					$data->__GET('socialpreventionEmployee'),
					$data->__GET('indasaEmployee'),
					$data->__GET('bonusdaysEployee'),
					$data->__GET('holidaysEmployee'),
					$data->__GET('vacationbonusporEmployee'),
					$data->__GET('imgEmployee'),
					$data->__GET('dateEmployee'),
					$data->__GET('timeEmployee')
					)
				);
				$idEmployee = $this->pdo->lastInsertId();
				return $idEmployee;
			} catch (Exception $e) 
			{die($e->getMessage());}
			return $res;
		}
		public function EmployeeListCode(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_employee WHERE statusEmployee = 1 AND positionEmployee = 2 ORDER BY idEmployee DESC LIMIT 1 ");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idEmployee', $r->idEmployee);
					$result[] = $ent;
				}
				//print_r($result);
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function EmployeeUnloadingList(){
			try 
			{
				$e_SQL = "SELECT
					idUser,
					nameUser,
					fullNameUser,
					emailUser,
					positionUser,
					namePosition,
					baseUser,
					statusUser
				FROM tbg.ta_1_user
				JOIN tbg.ca_7_position ON positionUser = idPosition
				WHERE statusUser = 1
				AND positionUser = 10
				ORDER BY idUser";
				$result=array();
				$stm = $this->pdo->prepare($e_SQL);	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idUser', $r->idUser);
					$ent->__SET('nameUser', $r->nameUser);
					$ent->__SET('fullNameUser', $r->fullNameUser);
					$ent->__SET('emailUser', $r->emailUser);
					$ent->__SET('positionUser', $r->positionUser);
					$ent->__SET('namePosition', $r->namePosition);
					$ent->__SET('baseUser', $r->baseUser);
					$ent->__SET('statusUser', $r->statusUser);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function EmployeeDriverList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_employee WHERE statusEmployee = 1 AND positionEmployee = 2 ORDER BY codeEmployee ASC ");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idEmployee', $r->idEmployee);
					$ent->__SET('codeEmployee', $r->codeEmployee);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
					$ent->__SET('companyEmployee', $r->companyEmployee);
					$ent->__SET('baseEmployee', $r->baseEmployee);
					$ent->__SET('nssEmployee', $r->nssEmployee);
					$ent->__SET('rfcEmployee', $r->rfcEmployee);
					$ent->__SET('curpEmployee', $r->curpEmployee);
					$ent->__SET('departamentEmployee', $r->departamentEmployee);
					$ent->__SET('positionEmployee', $r->positionEmployee);
					$ent->__SET('dateadmissionEmployee', $r->dateadmissionEmployee);
					$ent->__SET('phoneEmployee', $r->phoneEmployee);
					$ent->__SET('emailEmployee', $r->emailEmployee);
					$ent->__SET('countryEmployee', $r->countryEmployee);
					$ent->__SET('stateEmployee', $r->stateEmployee);
					$ent->__SET('municipalityEmployee', $r->municipalityEmployee);
					$ent->__SET('colonyEmployee', $r->colonyEmployee);
					$ent->__SET('streeEmployee', $r->streeEmployee);
					$ent->__SET('cpEmployee', $r->cpEmployee);
					$ent->__SET('birthdaydateEmployee', $r->birthdaydateEmployee);
					$ent->__SET('ageEmployee', $r->ageEmployee);
					$ent->__SET('genderEmployee', $r->genderEmployee);
					$ent->__SET('maritalEmployee', $r->maritalEmployee);
					$ent->__SET('nationalityEmployee', $r->nationalityEmployee);
					$ent->__SET('statebirthEmployee', $r->statebirthEmployee);
					$ent->__SET('municipalitybirthEmployee', $r->municipalitybirthEmployee);
					$ent->__SET('licenseDriverEmployee', $r->licenseDriverEmployee);
					$ent->__SET('companyemailEmployee', $r->companyemailEmployee);
					$ent->__SET('bankEmployee', $r->bankEmployee);
					$ent->__SET('accountEmployee', $r->accountEmployee);
					$ent->__SET('interbankcodeEmployee', $r->interbankcodeEmployee);
					$ent->__SET('beneficiaryEmployee', $r->beneficiaryEmployee);
					$ent->__SET('beneficiaryrelationshipEmployee', $r->beneficiaryrelationshipEmployee);
					$ent->__SET('benefeciaryrfcEmployee', $r->benefeciaryrfcEmployee);
					$ent->__SET('beneficiarycountryEmployee', $r->beneficiarycountryEmployee);
					$ent->__SET('beneficiarystateEmployee', $r->beneficiarystateEmployee);
					$ent->__SET('beneficiarymunicipalityEmployee', $r->beneficiarymunicipalityEmployee);
					$ent->__SET('beneficiarycolonyEmployee', $r->beneficiarycolonyEmployee);
					$ent->__SET('beneficiarystreeEmployee', $r->beneficiarystreeEmployee);
					$ent->__SET('beneficiarycpEmployee', $r->beneficiarycpEmployee);
					$ent->__SET('ifinfonavitcreditEmployee', $r->ifinfonavitcreditEmployee);
					$ent->__SET('creditnumberinfonavitEmployee', $r->creditnumberinfonavitEmployee);
					$ent->__SET('totalinfonavitEmployee', $r->totalinfonavitEmployee);
					$ent->__SET('balanceInfonavitEmployee', $r->balanceInfonavitEmployee);
					$ent->__SET('amountPaidInfonavitEmployee', $r->amountPaidInfonavitEmployee);
					$ent->__SET('bimonthlydiscountEmployee', $r->bimonthlydiscountEmployee);
					$ent->__SET('grossmonthlysalaryEmployee', $r->grossmonthlysalaryEmployee);
					$ent->__SET('socialpreventionEmployee', $r->socialpreventionEmployee);
					$ent->__SET('indasaEmployee', $r->indasaEmployee);
					$ent->__SET('bonusdaysEployee', $r->bonusdaysEployee);
					$ent->__SET('holidaysEmployee', $r->holidaysEmployee);
					$ent->__SET('vacationbonusporEmployee', $r->vacationbonusporEmployee);
					$ent->__SET('imgEmployee', $r->imgEmployee);
					$ent->__SET('statusEmployee', $r->statusEmployee);
					$ent->__SET('dateEmployee', $r->dateEmployee);
					$ent->__SET('timeEmployee', $r->timeEmployee);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function Age($cum){
			//echo $cum;
			$born = new DateTime($cum);
			$now = new DateTime(date("Y-m-d"));
			$diferens = $now->diff($born);
			//print_r($diferens);
			return $diferens;
		}
		public function EmployeeListUpdate($idE){
			//echo $idE;
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_employee AS t7e
				INNER JOIN tbg.ca_1_base AS c1b
				ON t7e.baseEmployee=c1b.idBase
				INNER JOIN tbg.ca_7_departament AS c7d
				ON t7e.departamentEmployee=c7d.idDepartament
				INNER JOIN tbg.ca_7_position AS c7p
				ON t7e.positionEmployee=c7p.idPosition
				INNER JOIN tbg.ca_7_state AS c7s
				ON t7e.stateEmployee=c7s.idState
				INNER JOIN tbg.ca_7_municipality AS c7m
				ON t7e.municipalityEmployee=c7m.idMunicipality
				LEFT JOIN tbg.ca_7_gender AS c7g
				ON t7e.genderEmployee=c7g.idGender
				LEFT JOIN tbg.ca_7_marital AS c7ma
				ON t7e.maritalEmployee=c7ma.idMarital
				LEFT JOIN tbg.ca_7_bank AS c7ba
				ON t7e.bankEmployee=c7ba.idBank
				LEFT JOIN tbg.ca_7_relationship AS c7r
				ON t7e.beneficiaryrelationshipEmployee=c7r.idRelationship
				WHERE t7e.idEmployee = ? AND t7e.statusEmployee = 1 ");	
				$stm->execute(array($idE));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					/////
    				foreach ((array)$this->EmployeeVacationData($idE) as $rVacation) :
    				    $dayUsed=$rVacation->__GET('dayUsed');
    				endforeach;
    				$dateStart = new DateTime($r->dateadmissionEmployee);
    				$dateHow= new DateTime(date('Y-m-d'));
    				$diference = $dateStart->diff($dateHow);
    				$yearsPassed = $diference->y;
    				$dayVacation = 0;
    				$availableDays = 0;
    				if($yearsPassed > 0){
    				    foreach ((array)$this->VacationLaw($yearsPassed) as $rVacation) :
    				        $dayVacation=$rVacation->__GET('dayVacation');
    				    endforeach;
    				    $availableDays = $dayVacation - $dayUsed;
    				}
					/////////
					$ent = new Enti();
					$ent->__SET('idBase', $r->idBase);
					$ent->__SET('nameBase', $r->nameBase);
					$ent->__SET('idDepartament', $r->idDepartament);
					$ent->__SET('nameDepartament', $r->nameDepartament);
					$ent->__SET('idPosition', $r->idPosition);
					$ent->__SET('namePosition', $r->namePosition);
					$ent->__SET('idState', $r->idState);
					$ent->__SET('nameState', $r->nameState);
					$ent->__SET('idMunicipality', $r->idMunicipality);
					$ent->__SET('nameMunicipality', $r->nameMunicipality);
					$ent->__SET('idGender', $r->idGender);
					$ent->__SET('nameGender', $r->nameGender);
					$ent->__SET('idMarital', $r->idMarital);
					$ent->__SET('nameMarital', $r->nameMarital);
					$ent->__SET('idBank', $r->idBank);
					$ent->__SET('nameBank', $r->nameBank);
					$ent->__SET('idRelationship', $r->idRelationship);
					$ent->__SET('nameRelationship', $r->nameRelationship);
					$ent->__SET('idEmployee', $r->idEmployee);
					$ent->__SET('codeEmployee', $r->codeEmployee);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
					$ent->__SET('companyEmployee', $r->companyEmployee);
					$ent->__SET('baseEmployee', $r->baseEmployee);
					$ent->__SET('nssEmployee', $r->nssEmployee);
					$ent->__SET('rfcEmployee', $r->rfcEmployee);
					$ent->__SET('curpEmployee', $r->curpEmployee);
					$ent->__SET('departamentEmployee', $r->departamentEmployee);
					$ent->__SET('positionEmployee', $r->positionEmployee);
					$ent->__SET('dateadmissionEmployee', $r->dateadmissionEmployee);
					$ent->__SET('phoneEmployee', $r->phoneEmployee);
					$ent->__SET('emailEmployee', $r->emailEmployee);
					$ent->__SET('countryEmployee', $r->countryEmployee);
					$ent->__SET('stateEmployee', $r->stateEmployee);
					$ent->__SET('municipalityEmployee', $r->municipalityEmployee);
					$ent->__SET('colonyEmployee', $r->colonyEmployee);
					$ent->__SET('streeEmployee', $r->streeEmployee);
					$ent->__SET('cpEmployee', $r->cpEmployee);
					$ent->__SET('birthdaydateEmployee', $r->birthdaydateEmployee);
					$ent->__SET('ageEmployee', $r->ageEmployee);
					$ent->__SET('genderEmployee', $r->genderEmployee);
					$ent->__SET('maritalEmployee', $r->maritalEmployee);
					$ent->__SET('nationalityEmployee', $r->nationalityEmployee);
					$ent->__SET('statebirthEmployee', $r->statebirthEmployee);
					$ent->__SET('municipalitybirthEmployee', $r->municipalitybirthEmployee);
					$ent->__SET('licenseDriverEmployee', $r->licenseDriverEmployee);
					$ent->__SET('companyemailEmployee', $r->companyemailEmployee);
					$ent->__SET('bankEmployee', $r->bankEmployee);
					$ent->__SET('accountEmployee', $r->accountEmployee);
					$ent->__SET('interbankcodeEmployee', $r->interbankcodeEmployee);
					$ent->__SET('beneficiaryEmployee', $r->beneficiaryEmployee);
					$ent->__SET('beneficiaryrelationshipEmployee', $r->beneficiaryrelationshipEmployee);
					$ent->__SET('benefeciaryrfcEmployee', $r->benefeciaryrfcEmployee);
					$ent->__SET('beneficiarycountryEmployee', $r->beneficiarycountryEmployee);
					$ent->__SET('beneficiarystateEmployee', $r->beneficiarystateEmployee);
					$ent->__SET('beneficiarymunicipalityEmployee', $r->beneficiarymunicipalityEmployee);
					$ent->__SET('beneficiarycolonyEmployee', $r->beneficiarycolonyEmployee);
					$ent->__SET('beneficiarystreeEmployee', $r->beneficiarystreeEmployee);
					$ent->__SET('beneficiarycpEmployee', $r->beneficiarycpEmployee);
					$ent->__SET('ifinfonavitcreditEmployee', $r->ifinfonavitcreditEmployee);
					$ent->__SET('creditnumberinfonavitEmployee', $r->creditnumberinfonavitEmployee);
					$ent->__SET('totalinfonavitEmployee', $r->totalinfonavitEmployee);
					$ent->__SET('balanceInfonavitEmployee', $r->balanceInfonavitEmployee);
					$ent->__SET('amountPaidInfonavitEmployee', $r->amountPaidInfonavitEmployee);
					$ent->__SET('bimonthlydiscountEmployee', $r->bimonthlydiscountEmployee);
					$ent->__SET('grossmonthlysalaryEmployee', $r->grossmonthlysalaryEmployee);
					$ent->__SET('socialpreventionEmployee', $r->socialpreventionEmployee);
					$ent->__SET('indasaEmployee', $r->indasaEmployee);
					$ent->__SET('bonusdaysEployee', $r->bonusdaysEployee);
					//$ent->__SET('holidaysEmployee', $r->holidaysEmployee);
					$ent->__SET('holidaysEmployee', $availableDays);
					$ent->__SET('vacationbonusporEmployee', $r->vacationbonusporEmployee);
					$ent->__SET('statusEmployee', $r->statusEmployee);
					$ent->__SET('imgEmployee', $r->imgEmployee);
					$ent->__SET('dateEmployee', $r->dateEmployee);
					$ent->__SET('timeEmployee', $r->timeEmployee);
					$result[] = $ent;
				}
				//print_r($result);
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function EmployeeInactive(Enti $data){
			//print_r($data);
			try 
			{
				$aNewData = array(
					'statusEmployee' => $data->__GET('statusEmployee'),
					'idEmployee' => $data->__GET('idEmployee')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ta_7_employee','idEmployee = '.$data->__GET('idEmployee'), 'Empleados');
				$sql = "UPDATE ta_7_employee SET 
							statusEmployee = ?
						WHERE idEmployee = ?";
				$res=$this->pdo->prepare($sql)->execute($aNewValues);
				/*
				$res=$this->pdo->prepare($sql)
					->execute(
					array(
					$data->__GET('statusEmployee'),
						$data->__GET('idEmployee')
						)
					);
					*/
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function EmployeeUpdate(Enti $data){
			//print_r($data);
			try 
			{
				$aNewData = array(
					'namesEmployee' => $data->__GET('namesEmployee'),
					'lastnameEmployee' => $data->__GET('lastnameEmployee'),
					'mlastnameEmployee' => $data->__GET('mlastnameEmployee'),
					'baseEmployee' => $data->__GET('baseEmployee'),
					'nssEmployee' => $data->__GET('nssEmployee'),
					'rfcEmployee' => $data->__GET('rfcEmployee'),
					'curpEmployee' => $data->__GET('curpEmployee'),
					'departamentEmployee' => $data->__GET('departamentEmployee'),
					'positionEmployee' => $data->__GET('positionEmployee'),
					'phoneEmployee' => $data->__GET('phoneEmployee'),
					'emailEmployee' => $data->__GET('emailEmployee'),
					'colonyEmployee' => $data->__GET('colonyEmployee'),
					'streeEmployee' => $data->__GET('streeEmployee'),
					'cpEmployee' => $data->__GET('cpEmployee'),
					'maritalEmployee' => $data->__GET('maritalEmployee'),
					'licenseDriverEmployee' => $data->__GET('licenseDriverEmployee'),
					'companyemailEmployee' => $data->__GET('companyemailEmployee'),
					'bankEmployee' => $data->__GET('bankEmployee'),
					'accountEmployee' => $data->__GET('accountEmployee'),
					'interbankcodeEmployee' => $data->__GET('interbankcodeEmployee'),
					'beneficiaryEmployee' => $data->__GET('beneficiaryEmployee'),
					'beneficiaryrelationshipEmployee' => $data->__GET('beneficiaryrelationshipEmployee'),
					'benefeciaryrfcEmployee' => $data->__GET('benefeciaryrfcEmployee'),
					'beneficiarycolonyEmployee' => $data->__GET('beneficiarycolonyEmployee'),
					'beneficiarystreeEmployee' => $data->__GET('beneficiarystreeEmployee'),
					'beneficiarycpEmployee' => $data->__GET('beneficiarycpEmployee'),
					'ifinfonavitcreditEmployee' => $data->__GET('ifinfonavitcreditEmployee'),
					'creditnumberinfonavitEmployee' => $data->__GET('creditnumberinfonavitEmployee'),
					'totalinfonavitEmployee' => $data->__GET('totalinfonavitEmployee'),
					'balanceInfonavitEmployee' => $data->__GET('balanceInfonavitEmployee'),
					'amountPaidInfonavitEmployee' => $data->__GET('amountPaidInfonavitEmployee'),
					'bimonthlydiscountEmployee' => $data->__GET('bimonthlydiscountEmployee'),
					'grossmonthlysalaryEmployee' => $data->__GET('grossmonthlysalaryEmployee'),
					'socialpreventionEmployee' => $data->__GET('socialpreventionEmployee'),
					'indasaEmployee' => $data->__GET('indasaEmployee'),
					'bonusdaysEployee' => $data->__GET('bonusdaysEployee'),
					'holidaysEmployee' => $data->__GET('holidaysEmployee'),
					'vacationbonusporEmployee' => $data->__GET('vacationbonusporEmployee'),
					'idEmployee' => $data->__GET('idEmployee')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ta_7_employee','idEmployee = '.$data->__GET('idEmployee'), 'Empleados');
				$sql = "UPDATE ta_7_employee SET 
							namesEmployee = ?,
							lastnameEmployee = ?,
							mlastnameEmployee = ?,
							baseEmployee = ?,
							nssEmployee = ?,
							rfcEmployee = ?,
							curpEmployee = ?,
							departamentEmployee = ?,
							positionEmployee = ?,
							phoneEmployee = ?,
							emailEmployee = ?,
							colonyEmployee = ?,
							streeEmployee = ?,
							cpEmployee = ?,
							maritalEmployee = ?,
							licenseDriverEmployee = ?,
							companyemailEmployee = ?,
							bankEmployee = ?,
							accountEmployee = ?,
							interbankcodeEmployee = ?,
							beneficiaryEmployee = ?,
							beneficiaryrelationshipEmployee = ?,
							benefeciaryrfcEmployee = ?,
							beneficiarycolonyEmployee = ?,
							beneficiarystreeEmployee = ?,
							beneficiarycpEmployee = ?,
							ifinfonavitcreditEmployee = ?,
							creditnumberinfonavitEmployee = ?,
							totalinfonavitEmployee = ?,
							balanceInfonavitEmployee = ?,
							amountPaidInfonavitEmployee = ?,
							bimonthlydiscountEmployee = ?,
							grossmonthlysalaryEmployee = ?,
							socialpreventionEmployee = ?,
							indasaEmployee = ?,
							bonusdaysEployee = ?,
							holidaysEmployee = ?,
							vacationbonusporEmployee = ?
						WHERE idEmployee = ?";
						$res=$this->pdo->prepare($sql)->execute($aNewValues);
						/*
				$res=$this->pdo->prepare($sql)
					->execute(
					array(
						$data->__GET('namesEmployee'),
						$data->__GET('lastnameEmployee'),
						$data->__GET('mlastnameEmployee'),
						$data->__GET('baseEmployee'),
						$data->__GET('nssEmployee'),
						$data->__GET('rfcEmployee'),
						$data->__GET('curpEmployee'),
						$data->__GET('departamentEmployee'),
						$data->__GET('positionEmployee'),
						$data->__GET('phoneEmployee'),
						$data->__GET('emailEmployee'),
						$data->__GET('colonyEmployee'),
						$data->__GET('streeEmployee'),
						$data->__GET('cpEmployee'),
						$data->__GET('maritalEmployee'),
						$data->__GET('licenseDriverEmployee'),
						$data->__GET('companyemailEmployee'),
						$data->__GET('bankEmployee'),
						$data->__GET('accountEmployee'),
						$data->__GET('interbankcodeEmployee'),
						$data->__GET('beneficiaryEmployee'),
						$data->__GET('beneficiaryrelationshipEmployee'),
						$data->__GET('benefeciaryrfcEmployee'),
						$data->__GET('beneficiarycolonyEmployee'),
						$data->__GET('beneficiarystreeEmployee'),
						$data->__GET('beneficiarycpEmployee'),
						$data->__GET('ifinfonavitcreditEmployee'),
						$data->__GET('creditnumberinfonavitEmployee'),
						$data->__GET('discounttypeInfoEmployee'),
						$data->__GET('discountfactorEmployee'),
						$data->__GET('iffonacotcreditEmployee'),
						$data->__GET('creditnumberfonacotEmployee'),
						$data->__GET('monthlydiscountEmployee'),
						$data->__GET('grossmonthlysalaryEmployee'),
						$data->__GET('socialpreventionEmployee'),
						$data->__GET('indasaEmployee'),
						$data->__GET('bonusdaysEployee'),
						$data->__GET('holidaysEmployee'),
						$data->__GET('vacationbonusporEmployee'),
						$data->__GET('idEmployee')
						)
					);
					*/
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}

		////////////   ***company***   /////////

		public function CompanyAdd(Enti $data){
			try {
				$sql = "INSERT INTO tbg.ca_1_company (nameCompany,icoCompany,logoCompany) 
		        	VALUES (?, ?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
                	$data->__GET('nameCompany'),
                	$data->__GET('icoCompany'),
                	$data->__GET('logoCompany')
					)
				);
				$idCompany = $this->pdo->lastInsertId();
				return $idCompany;
			} catch (Exception $e) 
			{die($e->getMessage());}
			return $res;
		}
		public function CompanyList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_1_company ORDER BY idCompany DESC ");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idCompany', $r->idCompany);
					$ent->__SET('nameCompany', $r->nameCompany);
					$ent->__SET('icoCompany', $r->icoCompany);
					$ent->__SET('logoCompany', $r->logoCompany);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function CompanyListPrint($company){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_1_company WHERE idCompany = ? ");	
				$stm->execute(array($company));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idCompany', $r->idCompany);
					$ent->__SET('nameCompany', $r->nameCompany);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
	
		////////////   ***profile***   /////////
		
		public function ProfileAdd(Enti $data){
			try {
				$sql = "INSERT INTO tbg.ca_1_profile (nameProfile) 
		        	VALUES (?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('nameProfile')
					)
				);
				$idProfile = $this->pdo->lastInsertId();
				return $idProfile;
			} catch (Exception $e) 
			{die($e->getMessage());}
			return $res;
		}
		public function ProfileList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_1_profile");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idProfile', $r->idProfile);
					$ent->__SET('nameProfile', $r->nameProfile);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		////////////   ***departament***   /////////

		public function DepartamentAdd(Enti $data){
			try {
				$sql = "INSERT INTO tbg.ca_7_departament (nameDepartament) 
					VALUES (?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('nameDepartament')
					)
				);
				$idDepartament = $this->pdo->lastInsertId();
				return $idDepartament;
			} catch (Exception $e) 
			{die($e->getMessage());}
			return $res;
		}
		public function DepartamentList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_7_departament ORDER BY nameDepartament ASC");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idDepartament', $r->idDepartament);
					$ent->__SET('nameDepartament', $r->nameDepartament);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		////////////   ***position***   /////////	

		public function PositionAdd(Enti $data){
			try {
				$sql = "INSERT INTO tbg.ca_7_position (namePosition, credentialPosition, statusPosition) 
					VALUES (?, ?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('namePosition'),
					$data->__GET('credentialPosition'),
					$data->__GET('statusPosition')
					)
				);
				$idPosition = $this->pdo->lastInsertId();
				return $idPosition;
			} catch (Exception $e) 
			{die($e->getMessage());}
			return $res;
		}
		public function PositionListU($idPosition){
			try {
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_7_position WHERE idPosition = ?");	
				$stm->execute(array($idPosition));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idPosition', $r->idPosition);
					$ent->__SET('namePosition', $r->namePosition);
					$ent->__SET('credentialPosition', $r->credentialPosition);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}
		public function PositionList($employee=""){
			try 
			{
				$result=array();
				if($employee == ""){
					$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_7_position");	
				} else {
					$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_7_position where idPosition > 1");	
				}
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idPosition', $r->idPosition);
					$ent->__SET('namePosition', $r->namePosition);
					$ent->__SET('typeBasePosition', $r->typeBasePosition);
					$ent->__SET('credentialPosition', $r->credentialPosition);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function PositionUpdate(Enti $data){
			try {
				$aNewData = array(
					'namePosition' => $data->__GET('namePosition'),
					'credentialPosition' => $data->__GET('credentialPosition'),
					'idPosition' => $data->__GET('idPosition')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ca_7_position','idPosition = '.$data->__GET('idPosition'), 'Puestos');
				
				$sql = "UPDATE ca_7_position SET 
							namePosition = ?,
							credentialPosition = ?
						WHERE idPosition = ?";
				$res=$this->pdo->prepare($sql)->execute($aNewValues);
				/*
				$res=$this->pdo->prepare($sql)->execute(
				array(
					$data->__GET('namePosition'),
					$data->__GET('credentialPosition'),
					$data->__GET('idPosition')
					)
				);
				*/
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function PositionInactive(Enti $data){
			try {
				$aNewData = array(
					'statusPosition' => $data->__GET('statusPosition'),
					'idPosition' => $data->__GET('idPosition')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ca_7_position','idPosition = '.$data->__GET('idPosition'), 'Puestos');
				$sql = "UPDATE ca_7_position SET 
							statusPosition = ?
						WHERE idPosition = ?";
				$res=$this->pdo->prepare($sql)->execute($aNewValues);
						/*
				$res=$this->pdo->prepare($sql)->execute(
					array(
						$data->__GET('statusPosition'),
						$data->__GET('idPosition')
						)
					);
					*/
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}

		////////////   ***bank***   /////////

		public function BankList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_7_bank");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idBank', $r->idBank);
					$ent->__SET('nameBank', $r->nameBank);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		////////////   ***gender***   /////////

		public function GenderList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_7_gender");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idGender', $r->idGender);
					$ent->__SET('nameGender', $r->nameGender);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		////////////   ***marital***   /////////

		public function MaritalList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_7_marital WHERE idMarital > 0");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idMarital', $r->idMarital);
					$ent->__SET('nameMarital', $r->nameMarital);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		////////////   ***relationship***   /////////

		public function RelationshipList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_7_relationship");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idRelationship', $r->idRelationship);
					$ent->__SET('nameRelationship', $r->nameRelationship);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		////////////   ***typebenefits***   /////////

		public function TypeBenefitsList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_7_typebenefits");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idTypeBenefits', $r->idTypeBenefits);
					$ent->__SET('nameTypeBenefits', $r->nameTypeBenefits);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function BenefitsAdd(Enti $data){
			try {
				//print_r($data);
				$sql = "INSERT INTO tbg.ta_7_benefits (employeeBenefits,idTypeBenefits) 
					VALUES (?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('employeeBenefits'),
					$data->__GET('idTypeBenefits')
					)
				);
				$idBenefit = $this->pdo->lastInsertId();
				return $idBenefit;
			} catch (Exception $e) 
			{die($e->getMessage());}
			return $res;
		}
		public function BenefitsList($idE){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_benefits AS tb
				INNER JOIN tbg.ca_7_typebenefits AS cb ON tb.idTypeBenefits=cb.idTypeBenefits
				WHERE tb.statusBenefits = 1 AND tb.employeeBenefits = ? ");	
				$stm->execute(array($idE));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idBenefits', $r->idBenefits);
					$ent->__SET('employeeBenefits', $r->employeeBenefits);
					$ent->__SET('nameTypeBenefits', $r->nameTypeBenefits);
					$ent->__SET('statusBenefits', $r->statusBenefits);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function BenefitsDelete(Enti $data){
			try 
			{
				$aNewData = array(
					'statusBenefits' => $data->__GET('statusBenefits'),
					'idBenefits' => $data->__GET('idBenefits')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ta_7_benefits','idBenefits = '.$data->__GET('idBenefits'), 'Prestaciones');
				$sql = "UPDATE ta_7_benefits SET 
							statusBenefits = ?
						WHERE idBenefits = ?";
				$res=$this->pdo->prepare($sql)->execute($aNewValues);
						/*
				$res=$this->pdo->prepare($sql)
					->execute(
					array(
					$data->__GET('statusBenefits'),
						$data->__GET('idBenefits')
						)
					);
					*/
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		
		////////////   ***state***   /////////

		public function StateList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_7_state");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idState', $r->idState);
					$ent->__SET('nameState', $r->nameState);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function StateListP(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_7_state ORDER BY nameState ASC");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idState', $r->idState);
					$ent->__SET('nameState', $r->nameState);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		////////////   ***municipality***   /////////

		public function MunicipalityListP($stateP){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_7_municipality where stateMunicipality = ? ORDER BY nameMunicipality ASC ");	
				$stm->execute(array($stateP));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idMunicipality', $r->idMunicipality);
					$ent->__SET('nameMunicipality', $r->nameMunicipality);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		//////////////  ***base***   ////////////////

		public function BBDList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_1_base AS bc 
											INNER JOIN tbg.ta_1_bus AS bs ON bc.idBase = bs.baseBus 
											INNER JOIN tbg.ta_7_Employee AS ds ON bc.idBase = ds.baseEmployee");
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idBase', $r->idBase);
					$ent->__SET('nameBase', $r->nameBase);
					$ent->__SET('idEmployee', $r->idEmployee);
					$ent->__SET('codeEmployee', $r->codeEmployee);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('idBus', $r->idBus);
					$ent->__SET('codeBus', $r->codeBus);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function BaseListE(){
			try 
			{
					$result=array();
					$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_1_base ORDER BY nameBase");	
					$stm->execute();
				
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idBase', $r->idBase);
					$ent->__SET('nameBase', $r->nameBase);
					$ent->__SET('prefixBase', $r->prefixBase);
					$result[] = $ent;
				}
				//print_r($result);
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function BaseList($bu=array()){
			try 
			{
				if (count($bu) == 0) {
					$result=array();
					$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_1_base ORDER BY nameBase");	
					$stm->execute();
				}else{
					$buAux = implode("','",$bu);
					$result=array();
					$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_1_base WHERE idBase IN('{$buAux}') ORDER BY nameBase");	
					$stm->execute();
				}
				
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idBase', $r->idBase);
					$ent->__SET('nameBase', $r->nameBase);
					$ent->__SET('prefixBase', $r->prefixBase);
					$result[] = $ent;
				}
				//print_r($result);
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function BaseListPrefix($baseUnloading){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_1_base WHERE idBase = ? ");	
				$stm->execute(array($baseUnloading));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idBase', $r->idBase);
					$ent->__SET('nameBase', $r->nameBase);
					$ent->__SET('prefixBase', $r->prefixBase);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		//////////////  ***route***   ////////////////

		public function RouteList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_1_route WHERE statusRoute = '1'  ORDER BY nameRoute ASC");
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idRoute', $r->idRoute);
					$ent->__SET('nameRoute', $r->nameRoute);
					$ent->__SET('baseRoute', $r->baseRoute);
					$ent->__SET('originRoute', $r->originRoute);
					$ent->__SET('destinationRoute', $r->destinationRoute);
					$ent->__SET('lapDurationRoute', $r->lapDurationRoute);
					$ent->__SET('jorneyKMRoute', $r->jorneyKMRoute);
					$ent->__SET('statusRoute', $r->statusRoute);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function RouteListData($idRoute){
			try {
				$result=array();
				$stm = $this->pdo->prepare("SELECT
				  idRoute,
				  nameRoute,
				  baseRoute,
				  originRoute,
				  destinationRoute,
				  lapDurationRoute,
				  jorneyKMRoute,
				  statusRoute
				FROM tbg.ta_1_route WHERE idRoute = ? ");	
				$stm->execute(array($idRoute));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idRoute', $r->idRoute);
					$ent->__SET('nameRoute', $r->nameRoute);
					$ent->__SET('baseRoute', $r->baseRoute);
					$ent->__SET('originRoute', $r->originRoute);
					$ent->__SET('destinationRoute', $r->destinationRoute);
					$ent->__SET('lapDurationRoute', $r->lapDurationRoute);
					$ent->__SET('jorneyKMRoute', $r->jorneyKMRoute);
					$ent->__SET('statusRoute', $r->statusRoute);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function RouteAdd(Enti $data){
			try {
				$sql = "INSERT INTO tbg.ta_1_route (nameRoute, lapDurationRoute, jorneyKMRoute) VALUES (?, ?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('nameRoute'),
					$data->__GET('lapDurationRoute'),
					$data->__GET('jorneyKMRoute')
					)
				);
				$idRoute = $this->pdo->lastInsertId();
				return $idRoute;
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function RouteUpdate(Enti $data){
			try {
				$aNewData = array(
					'nameRoute' => $data->__GET('nameRoute'),
					'lapDurationRoute' => $data->__GET('lapDurationRoute'),
					'jorneyKMRoute' => $data->__GET('jorneyKMRoute'),
					'idRoute' => $data->__GET('idRoute')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ta_1_route','idRoute = '.$data->__GET('idRoute'), 'Rutas');
				$sql = "UPDATE ta_1_route SET
							nameRoute = ?,
							lapDurationRoute = ?,
							jorneyKMRoute = ?
						WHERE idRoute = ?";
				$res=$this->pdo->prepare($sql)->execute($aNewValues);
						/*
				$res=$this->pdo->prepare($sql)->execute(array(
						$data->__GET('nameRoute'),
						$data->__GET('lapDurationRoute'),
						$data->__GET('jorneyKMRoute'),
						$data->__GET('idRoute')
					)
				);
				*/
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function RouteInactive(Enti $data){
			try {
				$aNewData = array(
					'statusRoute' => $data->__GET('statusRoute'),
					'idRoute' => $data->__GET('idRoute')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ta_1_route','idRoute = '.$data->__GET('idRoute'), 'Rutas');
				$sql = "UPDATE ta_1_route SET
							statusRoute = ?
						WHERE idRoute = ?";
				$res=$this->pdo->prepare($sql)->execute($aNewValues);
						/*
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('statusRoute'),
						$data->__GET('idRoute')
					)
				);
				*/
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}

		////////////  ***bus***   ////////////////

		public function BusList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_1_bus WHERE statusBus = '1' ORDER BY idBus ASC ");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idBus', $r->idBus);
					$ent->__SET('codeBus', $r->codeBus);
					$ent->__SET('economicBus', $r->economicBus);
					$ent->__SET('plateEBus', $r->plateEBus);
					$ent->__SET('plateFBus', $r->plateFBus);
					$ent->__SET('serialBus', $r->serialBus);
					$ent->__SET('engineBus', $r->engineBus);
					$ent->__SET('permitBus', $r->permitBus);
					$ent->__SET('modelBus', $r->modelBus);
					$ent->__SET('brandBus', $r->brandBus);
					$ent->__SET('bodyworkBus', $r->bodyworkBus);
					$ent->__SET('fuelBus', $r->fuelBus);
					$ent->__SET('baseBus', $r->baseBus);
					$ent->__SET('dateBus', $r->dateBus);
					$ent->__SET('timeBus', $r->timeBus);
					$ent->__SET('statusBus', $r->statusBus);
					$result[] = $ent;
				}
				//print_r($result);
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function BusListDate($baseBus){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_1_bus WHERE baseBus = ?  ORDER BY codeBus ASC ");	
				$stm->execute(array($baseBus));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idBus', $r->idBus);
					$ent->__SET('codeBus', $r->codeBus);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function BusListData($idBus){
			try {
				$result=array();
				$stm = $this->pdo->prepare("SELECT
				  idBus,
				  codeBus,
				  economicBus,
				  plateEBus,
				  plateFBus,
				  serialBus,
				  engineBus,
				  permitBus,
				  modelBus,
				  brandBus,
				  bodyworkBus,
				  fuelBus,
				  baseBus,
				  dateBus,
				  timeBus,
				  statusBus
				FROM tbg.ta_1_bus WHERE statusBus = 1 AND idBus = ? ");	
				$stm->execute(array($idBus));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idBus',$r->idBus);
					$ent->__SET('codeBus',$r->codeBus);
					$ent->__SET('economicBus',$r->economicBus);
					$ent->__SET('plateEBus',$r->plateEBus);
					$ent->__SET('plateFBus',$r->plateFBus);
					$ent->__SET('serialBus',$r->serialBus);
					$ent->__SET('engineBus',$r->engineBus);
					$ent->__SET('permitBus',$r->permitBus);
					$ent->__SET('modelBus',$r->modelBus);
					$ent->__SET('brandBus',$r->brandBus);
					$ent->__SET('bodyworkBus',$r->bodyworkBus);
					$ent->__SET('fuelBus',$r->fuelBus);
					$ent->__SET('baseBus',$r->baseBus);
					$ent->__SET('dateBus',$r->dateBus);
					$ent->__SET('timeBus',$r->timeBus);
					$ent->__SET('statusBus',$r->statusBus);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function BusAdd(Enti $data){
			try {
				$sql = "INSERT INTO tbg.ta_1_bus (economicBus, plateEBus, plateFBus, serialBus, engineBus, permitBus, modelBus, brandBus, bodyworkBus, fuelBus, baseBus, dateBus, timeBus ) 
				VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('economicBus'),
					$data->__GET('plateEBus'),
					$data->__GET('plateFBus'),
					$data->__GET('serialBus'),
					$data->__GET('engineBus'),
					$data->__GET('permitBus'),
					$data->__GET('modelBus'),
					$data->__GET('brandBus'),
					$data->__GET('bodyworkBus'),
					$data->__GET('fuelBus'),
					$data->__GET('baseBus'),
					$data->__GET('dateBus'),
					$data->__GET('timeBus'),
					)
				);
				$idBus = $this->pdo->lastInsertId();
				return $idBus;
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function BusUpdateCode(Enti $data){
			try {
				$sql = "UPDATE ta_1_bus SET
							codeBus = ?
						WHERE idBus = ?";
				$res=$this->pdo->prepare($sql)->execute(array(
						$data->__GET('codeBus'),
						$data->__GET('idBus')
					)
				);
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function BusUpdate(Enti $data){
			try {
				$aNewData = array(
					'economicBus' => $data->__GET('economicBus'),
					'plateEBus' => $data->__GET('plateEBus'),
					'plateFBus' => $data->__GET('plateFBus'),
					'serialBus' => $data->__GET('serialBus'),
					'engineBus' => $data->__GET('engineBus'),
					'permitBus' => $data->__GET('permitBus'),
					'modelBus' => $data->__GET('modelBus'),
					'brandBus' => $data->__GET('brandBus'),
					'bodyworkBus' => $data->__GET('bodyworkBus'),
					'fuelBus' => $data->__GET('fuelBus'),
					'baseBus' => $data->__GET('baseBus'),
					'idBus' => $data->__GET('idBus'),
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ta_1_bus','idBus = '.$data->__GET('idBus'), 'Unidades');
				$sql = "UPDATE ta_1_bus SET
							economicBus = ?,
							plateEBus = ?,
							plateFBus = ?,
							serialBus = ?,
							engineBus = ?,
							permitBus = ?,
							modelBus = ?,
							brandBus = ?,
							bodyworkBus = ?,
							fuelBus = ?,
							baseBus = ?
						WHERE idBus = ?";
				//$selectSql = $this->updateToSelect($sql);
				//$aOldData = (array) $this->currentData($selectSql .' WHERE idBus = '.$data->__GET('idBus'));
				/*
				$res=$this->pdo->prepare($sql)->execute(array(
						$data->__GET('economicBus'),
						$data->__GET('plateEBus'),
						$data->__GET('plateFBus'),
						$data->__GET('serialBus'),
						$data->__GET('engineBus'),
						$data->__GET('permitBus'),
						$data->__GET('modelBus'),
						$data->__GET('brandBus'),
						$data->__GET('bodyworkBus'),
						$data->__GET('fuelBus'),
						$data->__GET('baseBus'),
						$data->__GET('idBus') 
					)
				);
				*/
				$res=$this->pdo->prepare($sql)->execute($aNewValues);
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function BusInactive(Enti $data){
			try {
				$aNewData = array(
					'statusBus' => $data->__GET('statusBus'),
					'idBus' => $data->__GET('idBus')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ta_1_bus','idBus = '.$data->__GET('idBus'), 'Unidades');
				$sql = "UPDATE ta_1_bus SET
							statusBus = ?
						WHERE idBus = ?";
				$res=$this->pdo->prepare($sql)->execute($aNewValues);
						/*
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('statusBus'),
						$data->__GET('idBus')
					)
				);
				*/
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}

		////////////  ***unloading***   ////////////////

		public function UnloadingAdd(Enti $data){
			try {
				$sql = "INSERT INTO tbg.ta_7_unloading (userUnloading,baseUnloading,busUnloading,employeeUnloading,countingOutUnloading,countingInUnloading,commentsUnloading,dateUnloading,timeUnloading) 
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('userUnloading'),
					$data->__GET('baseUnloading'),
					$data->__GET('busUnloading'),
					$data->__GET('employeeUnloading'),
					$data->__GET('countingOutUnloading'),
					$data->__GET('countingInUnloading'),
					$data->__GET('commentsUnloading'),
					$data->__GET('dateUnloading'),
					$data->__GET('timeUnloading')
					)
				);
				$idUnloading = $this->pdo->lastInsertId();
				return $idUnloading;
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function UnloadingUpdate(Enti $data){
			try 
			{
				$sql = "UPDATE ta_7_unloading SET 
							serialUnloading = ?
						WHERE idUnloading = ?";
				$res=$this->pdo->prepare($sql)
					->execute(
					array(
						$data->__GET('serialUnloading'),
						$data->__GET('idUnloading')
						)
					);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function UnloadingListBefore(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_unloading ORDER BY idUnloading DESC LIMIT 1");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idUnloading', $r->idUnloading);
					$result[] = $ent;
				}
				//print_r($result);
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function UnloadingList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_unloading AS t7u
									INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
									INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
									INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
									AND t7u.statusUnloading = 1
									ORDER BY dateUnloading,timeUnloading ASC ");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idUnloading', $r->idUnloading);
					$ent->__SET('serialUnloading', $r->serialUnloading);
					$ent->__SET('nameBase', $r->nameBase);
					$ent->__SET('codeBus', $r->codeBus);
					$ent->__SET('economicBus', $r->economicBus);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
					$ent->__SET('commentsUnloading', $r->commentsUnloading);
					$ent->__SET('dateUnloading', $r->dateUnloading);
					$ent->__SET('timeUnloading', $r->timeUnloading);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function UnloadingListToVerified($idU){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_unloading AS t7u
									INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
									INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
									INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
									WHERE t7u.idUnloading = ?
									ORDER BY dateUnloading,timeUnloading ASC ");	
				$stm->execute(array($idU));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idUnloading', $r->idUnloading);
					$ent->__SET('serialUnloading', $r->serialUnloading);
					$ent->__SET('idBase', $r->idBase);
					$ent->__SET('nameBase', $r->nameBase);
					$ent->__SET('idBus', $r->idBus);
					$ent->__SET('economicBus', $r->economicBus);
					$ent->__SET('codeBus', $r->codeBus);
					$ent->__SET('idEmployee', $r->idEmployee);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
					$ent->__SET('commentsUnloading', $r->commentsUnloading);
					$ent->__SET('dateUnloading', $r->dateUnloading);
					$ent->__SET('timeUnloading', $r->timeUnloading);
					$result[] = $ent;
				}
				//print_r($result);
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function UnloadingEPS(Enti $data){
			try 
			{
				$sql = "UPDATE ta_7_unloading SET 
							userUnloadingEPS = ?,
							cashUnloadingEPS = ?,
							cardUnloadingEPS = ?,
							passageUnloadingEPS = ?,
							rechargeUnloadingEPS = ?,
							commentsUnloadingEPS = ?,
							statusUnloading = ?,
							dateUnloadingEPS = ?,
							timeUnloadingEPS = ?
						WHERE idUnloading = ?";
				$res=$this->pdo->prepare($sql)
					->execute(
					array(
					$data->__GET('userUnloadingEPS'),
					$data->__GET('cashUnloadingEPS'),
					$data->__GET('cardUnloadingEPS'),
					$data->__GET('passageUnloadingEPS'),
					$data->__GET('rechargeUnloadingEPS'),
					$data->__GET('commentsUnloadingEPS'),
					$data->__GET('statusUnloading'),
					$data->__GET('dateUnloadingEPS'),
					$data->__GET('timeUnloadingEPS'),
						$data->__GET('idUnloading')
						)
					);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function UnloadingCounting(Enti $data){
			try 
			{
				$sql = "UPDATE ta_7_unloading SET 
							userUnloadingC = ?,
							countingUnloadingC = ?,
							commentsUnloadingC = ?,
							statusUnloading = ?,
							dateUnloadingC = ?,
							timeUnloadingC = ?
						WHERE idUnloading = ?";
				$res=$this->pdo->prepare($sql)
					->execute(
					array(
					$data->__GET('userUnloadingC'),
					$data->__GET('countingUnloadingC'),
					$data->__GET('commentsUnloadingC'),
					$data->__GET('statusUnloading'),
					$data->__GET('dateUnloadingC'),
					$data->__GET('timeUnloadingC'),
						$data->__GET('idUnloading')
						)
					);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function UnloadingListEPS(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_unloading AS t7u
									INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
									INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
									INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
									WHERE t7u.statusUnloading = 2
									ORDER BY dateUnloading,timeUnloading ASC ");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idUnloading', $r->idUnloading);
					$ent->__SET('serialUnloading', $r->serialUnloading);
					$ent->__SET('nameBase', $r->nameBase);
					$ent->__SET('codeBus', $r->codeBus);
					$ent->__SET('economicBus', $r->economicBus);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
					$ent->__SET('countingOutUnloading', $r->countingOutUnloading);
					$ent->__SET('countingInUnloading', $r->countingInUnloading);
					$ent->__SET('commentsUnloading', $r->commentsUnloading);
					$ent->__SET('dateUnloading', $r->dateUnloading);
					$ent->__SET('timeUnloading', $r->timeUnloading);
					$ent->__SET('countingUnloadingC', $r->countingUnloadingC);
					$ent->__SET('commentsUnloadingC', $r->commentsUnloadingC);
					$ent->__SET('dateUnloadingC', $r->dateUnloadingC);
					$ent->__SET('timeUnloadingC', $r->timeUnloadingC);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function UnloadingListVerified(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_unloading AS t7u
									INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
									INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
									INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
									WHERE t7u.statusUnloading = 3
									ORDER BY dateUnloading,timeUnloading ASC ");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idUnloading', $r->idUnloading);
					$ent->__SET('serialUnloading', $r->serialUnloading);
					$ent->__SET('nameBase', $r->nameBase);
					$ent->__SET('codeBus', $r->codeBus);
					$ent->__SET('economicBus', $r->economicBus);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
					$ent->__SET('countingOutUnloading', $r->countingOutUnloading);
					$ent->__SET('countingInUnloading', $r->countingInUnloading);
					$ent->__SET('commentsUnloading', $r->commentsUnloading);
					$ent->__SET('dateUnloading', $r->dateUnloading);
					$ent->__SET('timeUnloading', $r->timeUnloading);
					$ent->__SET('countingUnloadingC', $r->countingUnloadingC);
					$ent->__SET('commentsUnloadingC', $r->commentsUnloadingC);
					$ent->__SET('dateUnloadingC', $r->dateUnloadingC);
					$ent->__SET('timeUnloadingC', $r->timeUnloadingC);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function UnloadingListVerifiedPrint($id){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_unloading AS t7u
									INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
									INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
									INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
									INNER JOIN tbg.ta_1_user AS t1u ON t7u.userUnloading = t1u.idUser
									INNER JOIN tbg.ta_1_user AS t1uc ON t7u.userUnloadingC = t1uc.idUser
									INNER JOIN tbg.ta_1_user AS t1ue ON t7u.userUnloadingEPS = t1ue.idUser
									INNER JOIN tbg.ta_1_user AS t1ut ON t7u.userUnloadingT = t1ut.idUser
									WHERE t7u.idUnloading = ? ");	
				$stm->execute(array($id));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idUnloading', $r->idUnloading);
					$ent->__SET('userUnloading', $r->userUnloading);
					$ent->__SET('serialUnloading', $r->serialUnloading);
					$ent->__SET('nameBase', $r->nameBase);
					$ent->__SET('economicBus', $r->economicBus);
					$ent->__SET('codeBus', $r->codeBus);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
					$ent->__SET('countingOutUnloading', $r->countingOutUnloading);
					$ent->__SET('countingInUnloading', $r->countingInUnloading);
					$ent->__SET('commentsUnloading', $r->commentsUnloading);
					$ent->__SET('dateUnloading', $r->dateUnloading);
					$ent->__SET('timeUnloading', $r->timeUnloading);
					$ent->__SET('userUnloadingC', $r->userUnloadingC);
					$ent->__SET('countingUnloadingC', $r->countingUnloadingC);
					$ent->__SET('commentsUnloadingC', $r->commentsUnloadingC);
					$ent->__SET('dateUnloadingC', $r->dateUnloadingC);
					$ent->__SET('timeUnloadingC', $r->timeUnloadingC);
					$ent->__SET('userUnloadingEPS', $r->userUnloadingEPS);
					$ent->__SET('cashUnloadingEPS', $r->cashUnloadingEPS);
					$ent->__SET('cardUnloadingEPS', $r->cardUnloadingEPS);
					$ent->__SET('passageUnloadingEPS', $r->passageUnloadingEPS);
					$ent->__SET('rechargeUnloadingEPS', $r->rechargeUnloadingEPS);
					$ent->__SET('commentsUnloadingEPS', $r->commentsUnloadingEPS);
					$ent->__SET('dateUnloadingEPS', $r->dateUnloadingEPS);
					$ent->__SET('timeUnloadingEPS', $r->timeUnloadingEPS);
					$ent->__SET('userUnloadingT', $r->userUnloadingT);
					$ent->__SET('countingUnloadingT', $r->countingUnloadingT);
					$ent->__SET('commentsUnloadingT', $r->commentsUnloadingT);
					$ent->__SET('dateUnloadingT', $r->dateUnloadingT);
					$ent->__SET('timeUnloadingT', $r->timeUnloadingT);
					$result[] = $ent;
				}
				//print_r($result);
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function UnloadingListSettlement(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_unloading AS t7u
									INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
									INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
									INNER JOIN tbg.ta_7_employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
									WHERE t7u.statusUnloading = 4");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idUnloading', $r->idUnloading);
					$ent->__SET('serialUnloading', $r->serialUnloading);
					$ent->__SET('prefixBase', $r->prefixBase);
					$ent->__SET('nameBase', $r->nameBase);
					$ent->__SET('codeBus', $r->codeBus);
					$ent->__SET('economicBus', $r->economicBus);
					$ent->__SET('idEmployee', $r->idEmployee);
					$ent->__SET('codeEmployee', $r->codeEmployee);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
					$ent->__SET('countingUnloadingC', $r->countingUnloadingC);
					$ent->__SET('cashUnloadingEPS', $r->cashUnloadingEPS);
					$ent->__SET('cardUnloadingEPS', $r->cardUnloadingEPS);
					$ent->__SET('passageUnloadingEPS', $r->passageUnloadingEPS);
					$ent->__SET('rechargeUnloadingEPS', $r->rechargeUnloadingEPS);
					$ent->__SET('commentsUnloading', $r->commentsUnloading);
					$ent->__SET('dateUnloading', $r->dateUnloading);
					$ent->__SET('timeUnloading', $r->timeUnloading);
					$ent->__SET('countingUnloadingC', $r->countingUnloadingC);
					$ent->__SET('commentsUnloadingC', $r->commentsUnloadingC);
					$ent->__SET('dateUnloadingC', $r->dateUnloadingC);
					$ent->__SET('timeUnloadingC', $r->timeUnloadingC);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function UnloadingListSettlementD($idU){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT
				*,
				(SELECT SUM(t7t.totalTicket) FROM tbg.ta_7_ticket AS t7t INNER JOIN tbg.ca_7_concept AS c7c ON t7t.conceptTicket = c7c.idConcept WHERE t7t.unloadingTicket = t7u.idUnloading AND c7c.comissionConcept = '1') AS ticketTaquilla
				FROM tbg.ta_7_unloading AS t7u
				INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
				INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
				INNER JOIN tbg.ta_7_employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
				WHERE t7u.idUnloading = ? AND t7u.statusUnloading = 4");	
				$stm->execute(array($idU));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idUnloading', $r->idUnloading);
					$ent->__SET('serialUnloading', $r->serialUnloading);
					$ent->__SET('prefixBase', $r->prefixBase);
					$ent->__SET('nameBase', $r->nameBase);
					$ent->__SET('codeBus', $r->codeBus);
					$ent->__SET('economicBus', $r->economicBus);
					$ent->__SET('idEmployee', $r->idEmployee);
					$ent->__SET('codeEmployee', $r->codeEmployee);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
					$ent->__SET('countingUnloadingC', $r->countingUnloadingC);
					$ent->__SET('cashUnloadingEPS', $r->cashUnloadingEPS);
					$ent->__SET('cardUnloadingEPS', $r->cardUnloadingEPS);
					$ent->__SET('passageUnloadingEPS', $r->passageUnloadingEPS);
					$ent->__SET('rechargeUnloadingEPS', $r->rechargeUnloadingEPS);
					$ent->__SET('commentsUnloading', $r->commentsUnloading);
					$ent->__SET('dateUnloading', $r->dateUnloading);
					$ent->__SET('timeUnloading', $r->timeUnloading);
					$ent->__SET('countingUnloadingC', $r->countingUnloadingC);
					$ent->__SET('commentsUnloadingC', $r->commentsUnloadingC);
					$ent->__SET('dateUnloadingC', $r->dateUnloadingC);
					$ent->__SET('timeUnloadingC', $r->timeUnloadingC);
					$ent->__SET('ticketTaquilla', $r->ticketTaquilla);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function UnloadingData($idU){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT *, (SELECT SUM(t7t.totalTicket) FROM tbg.ta_7_ticket AS t7t INNER JOIN tbg.ca_7_concept AS c7c ON t7t.conceptTicket = c7c.idConcept WHERE t7t.unloadingTicket = t7u.idUnloading AND c7c.comissionConcept = '1') AS ticketTaquilla
				FROM tbg.ta_7_unloading AS t7u
				INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
				INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
				INNER JOIN tbg.ta_7_employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
				WHERE t7u.idUnloading = ?");	
				$stm->execute(array($idU));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idUnloading', $r->idUnloading);
					$ent->__SET('serialUnloading', $r->serialUnloading);
					$ent->__SET('prefixBase', $r->prefixBase);
					$ent->__SET('nameBase', $r->nameBase);
					$ent->__SET('codeBus', $r->codeBus);
					$ent->__SET('economicBus', $r->economicBus);
					$ent->__SET('idEmployee', $r->idEmployee);
					$ent->__SET('codeEmployee', $r->codeEmployee);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
					//$ent->__SET('infonavitEmployee', $r->infonavitEmployee);
					//$ent->__SET('bailEmployee', $r->bailEmployee);
					//$ent->__SET('insuranceEmployee', $r->insuranceEmployee);
					//$ent->__SET('loanEmployee', $r->loanEmployee);
					//$ent->__SET('vialEmployee', $r->vialEmployee);
					$ent->__SET('countingUnloadingC', $r->countingUnloadingC);
					$ent->__SET('cashUnloadingEPS', $r->cashUnloadingEPS);
					$ent->__SET('cardUnloadingEPS', $r->cardUnloadingEPS);
					$ent->__SET('passageUnloadingEPS', $r->passageUnloadingEPS);
					$ent->__SET('rechargeUnloadingEPS', $r->rechargeUnloadingEPS);
					$ent->__SET('commentsUnloading', $r->commentsUnloading);
					$ent->__SET('dateUnloading', $r->dateUnloading);
					$ent->__SET('timeUnloading', $r->timeUnloading);
					$ent->__SET('countingOutUnloading', $r->countingOutUnloading);
					$ent->__SET('countingInUnloading', $r->countingInUnloading);
					$ent->__SET('commentsUnloadingC', $r->commentsUnloadingC);
					$ent->__SET('dateUnloadingC', $r->dateUnloadingC);
					$ent->__SET('timeUnloadingC', $r->timeUnloadingC);
					$ent->__SET('ticketTaquilla', $r->ticketTaquilla);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function AsleepSettlement($idemp){
			//echo $idemp;
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT SUM(countingAsleep) AS countingAsleep 
				FROM tbg.ta_7_asleep 
				WHERE employeeAsleep = ? AND statusAsleep = 1 ");	
				$stm->execute(array($idemp));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('countingAsleep', $r->countingAsleep);
					$result[] = $ent;
				}
				//print_r($result);
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function BulletSettlement($idemp){
			//echo $idemp;
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT SUM(amountTBullet) AS amountTBullet 
				FROM tbg.ta_7_bullet 
				WHERE employeeBullet = ? AND statusBullet = 1 ");	
				$stm->execute(array($idemp));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('amountTBullet', $r->amountTBullet);
					$result[] = $ent;
				}
				//print_r($result);
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function debtSettlement($idemp){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT
				  idSettlement,
                  debtSettlement,
				  paymentsDebtSettlement
                FROM tbg.ta_7_settlement AS t7s
                LEFT JOIN tbg.ta_7_unloading AS t7u ON t7s.idUnloading = t7u.idUnloading
                WHERE t7u.employeeUnloading = ?
                AND debtSettlement > 0");	
				$stm->execute(array($idemp));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('debtSettlement', $r->debtSettlement);
					$ent->__SET('idSettlement', $r->idSettlement);
					$ent->__SET('paymentsDebtSettlement', $r->paymentsDebtSettlement);
					$result[] = $ent;
				}
				//print_r($result);
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function UnloadingListSettlementPay(){
			try 
			{
				$result=array();
				$eSQL ="SELECT
					t7u.idUnloading,
					serialUnloading,
					nameBase,
					codeBus,
					economicBus,
					namesEmployee,
					lastnameEmployee,
					mlastnameEmployee,
					countingOutUnloading,
					countingInUnloading,
					commentsUnloading,
					dateUnloading,
					timeUnloading,
					countingUnloadingC,
					commentsUnloadingC,
					commentsSettlement,
					dateUnloadingC,
					timeUnloadingC,
					dateSettlement,
					timeSettlement,
					nameRoute,
					perceptionSettlement,
					statusSettlement,
					idSettlement
				FROM tbg.ta_7_unloading AS t7u
				INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
				INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
				INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
				INNER JOIN tbg.ta_7_settlement AS t7s ON t7u.idUnloading = t7s.idUnloading
				INNER JOIN tbg.ta_1_route AS t1r ON t7s.routeSettlement = t1r.idRoute
				WHERE t7u.statusUnloading = 5
				ORDER BY dateUnloading,timeUnloading ASC";
				/*
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_unloading AS t7u
									INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
									INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
									INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
									WHERE t7u.statusUnloading = 5
									ORDER BY dateUnloading,timeUnloading ASC ");
									*/
				$stm = $this->pdo->prepare($eSQL);
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idUnloading', $r->idUnloading);
					$ent->__SET('idSettlement', $r->idSettlement);
					$ent->__SET('serialUnloading', $r->serialUnloading);
					$ent->__SET('nameBase', $r->nameBase);
					$ent->__SET('codeBus', $r->codeBus);
					$ent->__SET('economicBus', $r->economicBus);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
					$ent->__SET('countingOutUnloading', $r->countingOutUnloading);
					$ent->__SET('countingInUnloading', $r->countingInUnloading);
					$ent->__SET('commentsUnloading', $r->commentsUnloading);
					$ent->__SET('dateUnloading', $r->dateUnloading);
					$ent->__SET('timeUnloading', $r->timeUnloading);
					$ent->__SET('countingUnloadingC', $r->countingUnloadingC);
					$ent->__SET('commentsUnloadingC', $r->commentsUnloadingC);
					$ent->__SET('commentsSettlement', $r->commentsSettlement);
					$ent->__SET('dateUnloadingC', $r->dateUnloadingC);
					$ent->__SET('timeUnloadingC', $r->timeUnloadingC);
					$ent->__SET('dateSettlement', $r->dateSettlement);
					$ent->__SET('timeSettlement', $r->timeSettlement);
					$ent->__SET('nameRoute', $r->nameRoute);
					$ent->__SET('statusSettlement', $r->statusSettlement);
					$ent->__SET('perceptionSettlement', $r->perceptionSettlement);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function UnloadingReport($aFiltros){
			try 
			{
				$result=array();
				$e_SQL = "SELECT
					serialUnloading,
					c1b.nameBase,
					t1b.economicBus,
					CONCAT(t7e.namesEmployee, ' ',t7e.lastnameEmployee,' ',t7e.mlastnameEmployee) AS employee,
					t1u.fullNameUser,
					CONCAT(t7u.dateUnloading, ' ', t7u.timeUnloading) AS dateUnloading,
					t7u.countingOutUnloading
				FROM tbg.ta_7_unloading AS t7u
				INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
				INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
				INNER JOIN tbg.ta_7_employee AS t7e ON t7u.employeeUnloading = t7e.idEmployee
				INNER JOIN tbg.ta_1_user AS t1u ON t7u.userUnloading = t1u.idUser
				WHERE 1 = 1";
				if(isset($aFiltros["startDateUnloading"]) && isset($aFiltros["endDateUnloading"])){
					$e_SQL .= " AND t7u.dateUnloading BETWEEN '{$aFiltros["startDateUnloading"]}' AND '{$aFiltros["endDateUnloading"]}'\n";
				}
				if(!empty($aFiltros["employeeUnloading"])){
					$e_SQL .= " AND t7u.employeeUnloading = '{$aFiltros["employeeUnloading"]}'\n";
				}
				if(!empty($aFiltros["userUnloading"])){
					$e_SQL .= " AND t7u.userUnloading = '{$aFiltros["userUnloading"]}'\n";
				}
				$stm = $this->pdo->prepare($e_SQL);	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('serialUnloading',$r->serialUnloading);
					$ent->__SET('nameBase',$r->nameBase);
					$ent->__SET('economicBus',$r->economicBus);
					$ent->__SET('employee',$r->employee);
					$ent->__SET('fullNameUser',$r->fullNameUser);
					$ent->__SET('dateUnloading',$r->dateUnloading);
					$ent->__SET('countingOutUnloading',$r->countingOutUnloading);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		////////////  ***asleep***   ////////////////

		public function AsleepAdd(Enti $data){
			try {
				$sql = "INSERT INTO tbg.ta_7_asleep (userAsleep, employeeAsleep,startdateAsleep,enddateAsleep,countingAsleep,commentsAsleep,dateAsleep,timeAsleep) 
					VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$_SESSION["SidUser"],
					$data->__GET('employeeAsleep'),
					$data->__GET('startdateAsleep'),
					$data->__GET('enddateAsleep'),
					$data->__GET('countingAsleep'),
					$data->__GET('commentsAsleep'),
					$data->__GET('dateAsleep'),
					$data->__GET('timeAsleep')
					)
				);
				$idASleep = $this->pdo->lastInsertId();
				return $idASleep;
			} catch (Exception $e) 
			{die($e->getMessage());}
			return $res;
		}
		public function AsleepList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_asleep AS t7s
				INNER JOIN tbg.ta_7_employee As t7e
				ON t7s.employeeAsleep=t7e.idEmployee
				WHERE t7s.statusAsleep = 1 
				ORDER BY t7s.startdateAsleep ASC ");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idAsleep', $r->idAsleep);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
					$ent->__SET('startdateAsleep', $r->startdateAsleep);
					$ent->__SET('enddateAsleep', $r->enddateAsleep);
					$ent->__SET('countingAsleep', $r->countingAsleep);
					$ent->__SET('commentsAsleep', $r->commentsAsleep);
					$ent->__SET('dateAsleep', $r->dateAsleep);
					$ent->__SET('timeAsleep', $r->timeAsleep);
					$ent->__SET('statusAsleep', $r->statusAsleep);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function AsleepListEmploye($idEmployee){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_asleep AS t7s
				INNER JOIN tbg.ta_7_employee As t7e
				ON t7s.employeeAsleep=t7e.idEmployee
				WHERE t7s.statusAsleep = 1 
				AND employeeAsleep = ?
				ORDER BY t7s.startdateAsleep ASC ");	
				$stm->execute(array($idEmployee));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idAsleep', $r->idAsleep);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
					$ent->__SET('startdateAsleep', $r->startdateAsleep);
					$ent->__SET('enddateAsleep', $r->enddateAsleep);
					$ent->__SET('countingAsleep', $r->countingAsleep);
					$ent->__SET('commentsAsleep', $r->commentsAsleep);
					$ent->__SET('dateAsleep', $r->dateAsleep);
					$ent->__SET('timeAsleep', $r->timeAsleep);
					$ent->__SET('statusAsleep', $r->statusAsleep);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function ASleepUpdate(Enti $data){
			try 
			{
				$sql = "UPDATE ta_7_asleep SET 
							datePayAsleep = ?,
							timePayAsleep = ?,
							userPayAsleep = ?,
							settlementPayAsleep = ?,
							statusAsleep = ?
						WHERE idAsleep = ?";
				$res=$this->pdo->prepare($sql)
					->execute(
					array(
						$data->__GET('datePayAsleep'),
						$data->__GET('timePayAsleep'),
						$data->__GET('userPayAsleep'),
						$data->__GET('settlementPayAsleep'),
						$data->__GET('statusAsleep'),
						$data->__GET('idAsleep')
						)
					);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function ASleepReport($aFiltros){
			try 
			{
				$result=array();
				$e_SQL = "SELECT
					CONCAT(t7e.lastnameEmployee, ' ', t7e.mlastnameEmployee, ' ', t7e.namesEmployee) AS employee,
					t7a.countingAsleep,
					t7a.dateAsleep,
					IF(t7a.statusAsleep = 1,'Por cobrar','Pagado') AS statusBullet,
					t7a.datePayAsleep,
					t7a.timePayAsleep,
					t7a.commentsAsleep
				FROM tbg.ta_7_asleep AS t7a
				LEFT JOIN tbg.ta_7_employee AS t7e ON t7a.employeeAsleep = t7e.idEmployee
				WHERE 1=1";
				if(isset($aFiltros["startDateAsleep"]) && isset($aFiltros["endDateAsleep"])){
					$e_SQL .= " AND t7a.dateAsleep BETWEEN '{$aFiltros["startDateAsleep"]}' AND '{$aFiltros["endDateAsleep"]}'\n";
				}
				if(!empty($aFiltros["employeeAsleep"])){
					$e_SQL .= " AND t7a.employeeAsleep = '{$aFiltros["employeeAsleep"]}'\n";
				}
				if(!empty($aFiltros["statusAsleep"])){
					$e_SQL .= " AND t7a.statusAsleep = '{$aFiltros["statusAsleep"]}'\n";
				}
				$stm = $this->pdo->prepare($e_SQL);	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('employee',$r->employee);
					$ent->__SET('countingAsleep',$r->countingAsleep);
					$ent->__SET('dateAsleep',$r->dateAsleep);
					$ent->__SET('statusBullet',$r->statusBullet);
					$ent->__SET('datePayAsleep',$r->datePayAsleep);
					$ent->__SET('timePayAsleep',$r->timePayAsleep);
					$ent->__SET('commentsAsleep',$r->commentsAsleep);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		////////////  ***bullet***   ////////////////

		public function BulletAdd(Enti $data){
			//print_r($data);
			try {
				$sql = "INSERT INTO tbg.ta_7_Bullet (userBullet,employeeBullet,startdateBullet,enddateBullet,
				amountfeeEABullet,amountfeeEEBullet,amountfeeRABullet,
				amountfeeREBullet,amountfeeSABullet,amountfeeSEBullet,
				amountTBullet,commentsBullet,dateBullet,timeBullet) 
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$_SESSION["SidUser"],
					$data->__GET('employeeBullet'),
					$data->__GET('startdateBullet'),
					$data->__GET('enddateBullet'),
					$data->__GET('amountfeeEABullet'),
					$data->__GET('amountfeeEEBullet'),
					$data->__GET('amountfeeRABullet'),
					$data->__GET('amountfeeREBullet'),
					$data->__GET('amountfeeSABullet'),
					$data->__GET('amountfeeSEBullet'),
					$data->__GET('amountTBullet'),
					$data->__GET('commentsBullet'),
					$data->__GET('dateBullet'),
					$data->__GET('timeBullet')
					)
				);
				$idBullet = $this->pdo->lastInsertId();
				return $idBullet;
			} catch (Exception $e) 
			{die($e->getMessage());}
			return $res;
		}
		public function BulletList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_bullet AS t7b
				INNER JOIN tbg.ta_7_employee AS t7e
				ON t7b.employeeBullet=t7e.idEmployee
				WHERE statusBullet = 1 ORDER BY startdateBullet ASC ");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idBullet', $r->idBullet);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
					$ent->__SET('startdateBullet', $r->startdateBullet);
					$ent->__SET('enddateBullet', $r->enddateBullet);
					$ent->__SET('amountfeeEABullet', $r->amountfeeEABullet);
					$ent->__SET('amountfeeEEBullet', $r->amountfeeEEBullet);
					$ent->__SET('amountfeeRABullet', $r->amountfeeRABullet);
					$ent->__SET('amountfeeREBullet', $r->amountfeeREBullet);
					$ent->__SET('amountfeeSABullet', $r->amountfeeSABullet);
					$ent->__SET('amountfeeSEBullet', $r->amountfeeSEBullet);
					$ent->__SET('amountTBullet', $r->amountTBullet);
					$ent->__SET('commentsBullet', $r->commentsBullet);
					$ent->__SET('dateBullet', $r->dateBullet);
					$ent->__SET('timeBullet', $r->timeBullet);
					$ent->__SET('statusBullet', $r->statusBullet);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function BulletListEmploye($idEmployee){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_bullet AS t7b
				INNER JOIN tbg.ta_7_employee AS t7e
				ON t7b.employeeBullet=t7e.idEmployee
				WHERE statusBullet = 1
				AND  employeeBullet = ? 
				ORDER BY startdateBullet ASC ");	
				$stm->execute(array($idEmployee));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idBullet', $r->idBullet);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
					$ent->__SET('startdateBullet', $r->startdateBullet);
					$ent->__SET('enddateBullet', $r->enddateBullet);
					$ent->__SET('amountfeeEABullet', $r->amountfeeEABullet);
					$ent->__SET('amountfeeEEBullet', $r->amountfeeEEBullet);
					$ent->__SET('amountfeeRABullet', $r->amountfeeRABullet);
					$ent->__SET('amountfeeREBullet', $r->amountfeeREBullet);
					$ent->__SET('amountfeeSABullet', $r->amountfeeSABullet);
					$ent->__SET('amountfeeSEBullet', $r->amountfeeSEBullet);
					$ent->__SET('amountTBullet', $r->amountTBullet);
					$ent->__SET('commentsBullet', $r->commentsBullet);
					$ent->__SET('dateBullet', $r->dateBullet);
					$ent->__SET('timeBullet', $r->timeBullet);
					$ent->__SET('statusBullet', $r->statusBullet);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function BulletUpdate(Enti $data){
			try {
				$sql = "UPDATE ta_7_bullet SET 
							datePayBullet = ?,
							timePayBullet = ?,
							userPayBullet = ?,
							settlementPayBullet = ?,
							statusBullet = ?
						WHERE idBullet = ?";
				$res=$this->pdo->prepare($sql)
					->execute(
					array(
						$data->__GET('datePayBullet'),
						$data->__GET('timePayBullet'),
						$data->__GET('userPayBullet'),
						$data->__GET('settlementPayBullet'),
						$data->__GET('statusBullet'),
						$data->__GET('idBullet')
						)
					);
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function BulletReport($aFiltros){
			try 
			{
				$result=array();
				$e_SQL = "SELECT
				  CONCAT(t7e.lastnameEmployee, ' ', t7e.mlastnameEmployee, ' ', t7e.namesEmployee) AS employee,
				  t7b.dateBullet,
				  t7b.amountfeeEABullet,
				  t7b.amountfeeEEBullet,
				  t7b.amountfeeRABullet,
				  t7b.amountfeeREBullet,
				  t7b.amountfeeSABullet,
				  t7b.amountfeeSEBullet,
				  t7b.amountTBullet,
				  IF(t7b.statusBullet = 1,'Por cobrar','Pagado') AS statusBullet,
				  t7b.datePayBullet,
				  t7b.timePayBullet,
				  t7b.commentsBullet
				FROM tbg.ta_7_bullet AS t7b
				LEFT JOIN tbg.ta_7_employee AS t7e ON t7b.employeeBullet = t7e.idEmployee
				WHERE 1=1";
				if(isset($aFiltros["startDateBullet"]) && isset($aFiltros["endDateBullet"])){
					$e_SQL .= " AND t7b.dateBullet BETWEEN '{$aFiltros["startDateBullet"]}' AND '{$aFiltros["endDateBullet"]}'\n";
				}
				if(!empty($aFiltros["employeeBullet"])){
					$e_SQL .= " AND t7b.employeeBullet = '{$aFiltros["employeeBullet"]}'\n";
				}
				if(!empty($aFiltros["statusBullet"])){
					$e_SQL .= " AND t7b.statusBullet = '{$aFiltros["statusBullet"]}'\n";
				}
				$stm = $this->pdo->prepare($e_SQL);	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('employee',$r->employee);
					$ent->__SET('dateBullet',$r->dateBullet);
					$ent->__SET('amountfeeEABullet',$r->amountfeeEABullet);
					$ent->__SET('amountfeeEEBullet',$r->amountfeeEEBullet);
					$ent->__SET('amountfeeRABullet',$r->amountfeeRABullet);
					$ent->__SET('amountfeeREBullet',$r->amountfeeREBullet);
					$ent->__SET('amountfeeSABullet',$r->amountfeeSABullet);
					$ent->__SET('amountfeeSEBullet',$r->amountfeeSEBullet);
					$ent->__SET('amountTBullet',$r->amountTBullet);
					$ent->__SET('statusBullet',$r->statusBullet);
					$ent->__SET('datePayBullet',$r->datePayBullet);
					$ent->__SET('timePayBullet',$r->timePayBullet);
					$ent->__SET('commentsBullet',$r->commentsBullet);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		////////////  ***WithholdingConcept***   ////////////////

		public function WithholdingConceptList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_7_withholdingConcept WHERE statusWithholdingConcept = '1' ORDER BY nameWithholdingConcept ASC");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idWithholdingConcept', $r->idWithholdingConcept);
					$ent->__SET('nameWithholdingConcept', $r->nameWithholdingConcept);
					$ent->__SET('amountWithholdingConcept', $r->amountWithholdingConcept);
					$ent->__SET('termWeekWithholdingConcept', $r->termWeekWithholdingConcept);
					$ent->__SET('importWithholdingConcept', $r->importWithholdingConcept);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		////////////  ***Withholding***   ////////////////

		public function WithholdngSettlement($idemp,$eLista = ''){
			//echo $idemp;
			if($eLista == ''){
				$eGroup = 'c7w.nameWithholdingConcept';
			} else {
				$eGroup = 't7w.idWithholding';
			}
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT *, nameWithholdingConcept,
				SUM(payImportWithholding) AS payImportWithholding,
				SUM(balanceWithholding) AS balanceImportWithholding
				FROM tbg.ta_7_withholding AS t7w
				INNER JOIN tbg.ca_7_withholdingconcept AS c7w
				ON t7w.withholdingConceptWithholding = c7w.idWithholdingConcept
				INNER JOIN tbg.ta_7_employee AS t7e
				ON t7w.employeeWithholding=t7e.idEmployee
				WHERE t7w.employeewithholding = ? AND t7w.statusWithholding = 1
				GROUP BY {$eGroup}
				ORDER BY t7w.dateAplicationWithholding DESC");	
				$stm->execute(array($idemp));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idWithholding', $r->idWithholding);
					$ent->__SET('withholdingConceptWithholding', $r->withholdingConceptWithholding);
					$ent->__SET('nameWithholdingConcept', $r->nameWithholdingConcept);
					$ent->__SET('amountWithholding', $r->amountWithholding);
					$ent->__SET('balanceWithholding', $r->balanceWithholding);
					$ent->__SET('termWithholding', $r->termWithholding);
					$ent->__SET('payPorcentWithholding', $r->payPorcentWithholding);
					$ent->__SET('payImportWithholding', $r->payImportWithholding);
					$ent->__SET('balanceImportWithholding', $r->balanceImportWithholding);
					$result[] = $ent;
				}
				//print_r($result);
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}	

		public function WithholdingAdd(Enti $data){
			//print_r($data);
			try {
				$sql = "INSERT INTO tbg.ta_7_withholding (userWithholding, employeeWithholding,withholdingConceptWithholding,folioWithholding,
				dateAplicationWithholding,amountWithholding,balanceWithholding,termWithholding,payPorcentWithholding,payImportWithholding,
				dateWithholding,timeWithholding) 
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$_SESSION["SidUser"],
					$data->__GET('employeeWithholding'),
					$data->__GET('withholdingConceptWithholding'),
					$data->__GET('folioWithholding'),
					$data->__GET('dateAplicationWithholding'),
					$data->__GET('amountWithholding'),
					$data->__GET('balanceWithholding'),
					$data->__GET('termWithholding'),
					$data->__GET('payPorcentWithholding'),
					$data->__GET('payImportWithholding'),
					$data->__GET('dateWithholding'),
					$data->__GET('timeWithholding')
					)
				);
				$idWithholding = $this->pdo->lastInsertId();
				return $idWithholding;
			} catch (Exception $e) 
			{die($e->getMessage());}
			return $res;
		}
		public function WithholdingList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_withholding AS t7w
				INNER JOIN tbg.ca_7_withholdingconcept AS c7w
				ON t7w.withholdingConceptWithholding = c7w.idWithholdingConcept
				INNER JOIN tbg.ta_7_employee AS t7e
				ON t7w.employeeWithholding=t7e.idEmployee
				WHERE t7w.statusWithholding = 1 ORDER BY t7w.dateAplicationWithholding DESC");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idWithholding', $r->idWithholding);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
					$ent->__SET('idWithholdingConcept', $r->idWithholdingConcept);
					$ent->__SET('nameWithholdingConcept', $r->nameWithholdingConcept);
					$ent->__SET('folioWithholding', $r->folioWithholding);
					$ent->__SET('dateAplicationWithholding', $r->dateAplicationWithholding);
					$ent->__SET('amountWithholding', $r->amountWithholding);
					$ent->__SET('balanceWithholding', $r->balanceWithholding);
					$ent->__SET('termWithholding', $r->termWithholding);
					$ent->__SET('payPorcentWithholding', $r->payPorcentWithholding);
					$ent->__SET('payImportWithholding', $r->payImportWithholding);
					$ent->__SET('statusWithholding', $r->statusWithholding);
					$ent->__SET('dateWithholding', $r->dateWithholding);
					$ent->__SET('timeWithholding', $r->timeWithholding);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function WithholdingReport($aFiltros){
			try 
			{
				$result=array();
				$e_SQL = "SELECT
				  t7w.idWithholding,
				  t7w.dateWithholding,
				  CONCAT(t7e.namesEmployee, ' ', t7e.mlastnameEmployee, ' ', t7e.lastnameEmployee ) AS employee,
				  t7w.folioWithholding,
				  c7w.nameWithholdingConcept,
				  t7w.amountWithholding,
				  t7w.balanceWithholding,
				  t7w.datePayWithholding,
				  t7w.timePayWithholding,
				  IF(t7w.statusWithholding = 1,'Por cobrar','Pagado') AS statusWithholding
				FROM tbg.ta_7_withholding AS t7w
				LEFT JOIN tbg.ta_7_employee AS t7e ON t7w.employeeWithholding = t7e.idEmployee
				LEFT JOIN tbg.ca_7_withholdingconcept AS c7w ON t7w.withholdingConceptWithholding = c7w.idWithholdingConcept
				WHERE 1=1";
				if(isset($aFiltros["startDateWithholding"]) && isset($aFiltros["endDateWithholding"])){
					$e_SQL .= " AND t7w.dateWithholding BETWEEN '{$aFiltros["startDateWithholding"]}' AND '{$aFiltros["endDateWithholding"]}'\n";
				}
				if(!empty($aFiltros["employeeWithholding"])){
					$e_SQL .= " AND t7w.employeeWithholding = '{$aFiltros["employeeWithholding"]}'\n";
				}
				if(!empty($aFiltros["withholdingConcept"])){
					$e_SQL .= " AND t7w.withholdingConceptWithholding = '{$aFiltros["withholdingConcept"]}'\n";
				}
				if(!empty($aFiltros["statusWithholding"])){
					$e_SQL .= " AND statusWithholding = '{$aFiltros["statusWithholding"]}'\n";
				}
				if(!empty($aFiltros["orderWithholding"]) && isset($aFiltros["typeOrderWithholding"])){
					$e_SQL .= " ORDER BY {$aFiltros["orderWithholding"]} {$aFiltros["typeOrderWithholding"]}\n";
				}
				if(!empty($aFiltros["limit"])){
					$e_SQL .= " LIMIT {$aFiltros["limit"]}\n";
				}
				//$e_SQL .= "GROUP BY t7w.employeeWithholding, t7w.withholdingConceptWithholding";
				$stm = $this->pdo->prepare($e_SQL);	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idWithholding',$r->idWithholding);
					$ent->__SET('employee',$r->employee);
					$ent->__SET('dateWithholding',$r->dateWithholding);
					$ent->__SET('nameWithholdingConcept',$r->nameWithholdingConcept);
					$ent->__SET('folioWithholding',$r->folioWithholding);
					$ent->__SET('amountWithholding',$r->amountWithholding);
					$ent->__SET('balanceWithholding',$r->balanceWithholding);
					$ent->__SET('datePayWithholding',$r->datePayWithholding);
					$ent->__SET('timePayWithholding',$r->timePayWithholding);
					$ent->__SET('statusWithholding',$r->statusWithholding);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		////////////  ***settlement***   ////////////////

		public function SettlementList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_asleep ORDER BY startdateAsleep ASC ");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idAsleep', $r->idAsleep);
					$ent->__SET('employeeAsleep', $r->employeeAsleep);
					$ent->__SET('startdateAsleep', $r->startdateAsleep);
					$ent->__SET('endtdateAsleep', $r->endtdateAsleep);
					$ent->__SET('countingAsleep', $r->countingAsleep);
					$ent->__SET('commentsAsleep', $r->commentsAsleep);
					$ent->__SET('dateAsleep', $r->dateAsleep);
					$ent->__SET('timeAsleep', $r->timeAsleep);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function SettlementAdd(Enti $data){
			/*
					$data->__GET('UnloadingSettlement'),//--
					$data->__GET('BusSettlement'),//--
					$data->__GET('userSettlement'),//--
					$data->__GET('codeBaseSettlement'),//--
					$data->__GET('nameBaseSettlement'),//--
					$data->__GET('codeEmployeeSettlement'),//--
					$data->__GET('nameEmployeeSettlement'),//--
					$data->__GET('idSettlement'),//--
					$data->__GET('dateApplicationSettlement'),//--
				*/
			//print_r($data);
			try {
				$sql = "INSERT INTO tbg.ta_7_settlement ( idUnloading, routeSettlement,userSettlement, amountTaxSettlement, amountIMSSSettlement, amountBulletSettlement, amountAsleepSettlement, amountFiaSettlement, amountSegSettlement, amountPreSettlement, amountInfSettlement, amountViaSettlement, amountCountingSettlement, amountEPSSettlement, amountRechargeSettlement, amountCardSettlement, amountTotalSettlement, amountPassageSettlement, guiaSettlement, turnSettlement, KMSettlement, consumptionSettlement, dieselSettlement, dieselLSettlement, whitholdingSettlement, salarySettlement, taxSettlement, perceptionSettlement, cashSettlement, commentsSettlement, dateSettlement, timeSettlement, amountDebtPaySettlement ) 
					VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('idUnloading'),
					$data->__GET('routeSettlement'),
					$data->__GET('userSettlement'),
					$data->__GET('amountTaxSettlement'),
					$data->__GET('amountIMSSSettlement'),
					$data->__GET('amountBulletSettlement'),
					$data->__GET('amountAsleepSettlement'),
					$data->__GET('amountFiaSettlement'),
					$data->__GET('amountSegSettlement'),
					$data->__GET('amountPreSettlement'),
					$data->__GET('amountInfSettlement'),
					$data->__GET('amountViaSettlement'),
					$data->__GET('amountCountingSettlement'),
					$data->__GET('amountEPSSettlement'),
					$data->__GET('amountRechargeSettlement'),
					$data->__GET('amountCardSettlement'),
					$data->__GET('amountTotalSettlement'),
					$data->__GET('amountPassageSettlement'),
					$data->__GET('guiaSettlement'),
					//$data->__GET('guiaFileSettlement'),
					$data->__GET('turnSettlement'),
					$data->__GET('KMSettlement'),
					$data->__GET('consumptionSettlement'),
					$data->__GET('dieselSettlement'),
					$data->__GET('dieselLSettlement'),
					$data->__GET('whitholdingSettlement'),
					$data->__GET('salarySettlement'),
					$data->__GET('taxSettlement'),
					$data->__GET('perceptionSettlement'),
					$data->__GET('cashSettlement'),
					$data->__GET('commentsSettlement'),
					$data->__GET('dateSettlement'),
					$data->__GET('timeSettlement'),
					$data->__GET('amountDebtPaySettlement')
					)
				);
				$idSettelement = $this->pdo->lastInsertId();
				return $idSettelement;
			} catch (Exception $e) 
			{die($e->getMessage());}
			return $res;
		}
		public function SettlementUpdate(Enti $data){
			try 
			{
				if(!empty($data->__GET('dateEndSettlement'))){
					$sql = "UPDATE ta_7_settlement SET 
								datePaySettlement = ?,
								timePaySettlement = ?,
								dateEndSettlement = ?,
								timeEndSettlement = ?,
								userPaySettlement = ?,
								statusSettlement = ?
							WHERE idSettlement = ?";
					$res=$this->pdo->prepare($sql)
						->execute(
						array(
							$data->__GET('datePaySettlement'),
							$data->__GET('timePaySettlement'),
							$data->__GET('dateEndSettlement'),
							$data->__GET('timeEndSettlement'),
							$data->__GET('userPaySettlement'),
							$data->__GET('statusSettlement'),
							$data->__GET('idSettlement')
							)
						);
				} else {
					$sql = "UPDATE ta_7_settlement SET 
								datePaySettlement = ?,
								timePaySettlement = ?,
								userEndSettlement = ?,
								statusSettlement = ?
							WHERE idSettlement = ?";
					$res=$this->pdo->prepare($sql)
						->execute(
						array(
							$data->__GET('datePaySettlement'),
							$data->__GET('timePaySettlement'),
							$data->__GET('userEndSettlement'),
							$data->__GET('statusSettlement'),
							$data->__GET('idSettlement')
							)
						);
				}
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function SettlementChargetUpdate(Enti $data){
			try {
					$sql = "UPDATE ta_7_settlement SET 
								paymentsDebtSettlement = ?,
			                    debtSettlement = ?,
			                    datePayDebtSettlement = ?,
			                    dateEndSettlement = ?,
			                    timeEndSettlement = ?,
			                    statusSettlement = ?
							WHERE idSettlement = ?";
					$res=$this->pdo->prepare($sql)->execute(
						array(
							$data->__GET('paymentsDebtSettlement'),
							$data->__GET('debtSettlement'),
							$data->__GET('datePayDebtSettlement'),
							$data->__GET('dateEndSettlement'),
							$data->__GET('timeEndSettlement'),
							$data->__GET('statusSettlement'),
							$data->__GET('idSettlement')
						)
					);
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function SettlementDebtUpdate(Enti $data){
			try {
					$sql = "UPDATE ta_7_settlement SET 
								paymentsDebtSettlement = ?,
			                    debtSettlement = ?,
			                    settlementPayDebtSettlement = ?,
			                    datePayDebtSettlement = ?
							WHERE idSettlement = ?";
					$res=$this->pdo->prepare($sql)->execute(
						array(
							$data->__GET('paymentsDebtSettlement'),
							$data->__GET('debtSettlement'),
							$data->__GET('settlementPayDebtSettlement'),
							$data->__GET('datePayDebtSettlement'),
							$data->__GET('idSettlement')
						)
					);
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function SettlementUnloadingUpdate(Enti $data){
			try 
			{
				$sql = "UPDATE ta_7_unloading SET 
							statusUnloading = ?
						WHERE idUnloading = ?";
				$res=$this->pdo->prepare($sql)
					->execute(
					array(
						$data->__GET('statusUnloading'),
						$data->__GET('idUnloading')
						)
					);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function SettlementWithholdingUpdate(Enti $data){
			try 
			{
				$sql = "UPDATE ta_7_withholding SET 
							balanceWithholding = ?,
							datePayWithholding =?,
							timePayWithholding =?,
							statusWithholding = ?
						WHERE idWithholding = ?";
				$res=$this->pdo->prepare($sql)
					->execute(
					array(
						$data->__GET('balanceWithholding'),
						$data->__GET('datePayWithholding'),
						$data->__GET('timePayWithholding'),
						$data->__GET('statusWithholding'),
						$data->__GET('idWithholding')
						)
					);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function SettlementData($idSettlement){
			try 
			{
				$e_SQL ="SELECT t7s.*, t1u.nameUser,nameRoute
				FROM tbg.ta_7_settlement AS t7s
				JOIN tbg.ta_1_user AS t1u ON t7s.userSettlement = t1u.idUser
				JOIN tbg.ta_1_route AS t1r ON t7s.routeSettlement = t1r.idRoute
				WHERE idSettlement = ?";
				$result=array();
				$stm = $this->pdo->prepare($e_SQL);	
				$stm->execute(array($idSettlement));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $rSettlement)
				{
					$ent = new Enti();
					$ent->__SET('idUnloading', $rSettlement->idUnloading);
					$ent->__SET('userSettlement', $rSettlement->userSettlement);
					$ent->__SET('nameUser', $rSettlement->nameUser);
					$ent->__SET('routeSettlement', $rSettlement->routeSettlement);
					$ent->__SET('nameRoute', $rSettlement->nameRoute);
					$ent->__SET('amountTaxSettlement', $rSettlement->amountTaxSettlement);
					$ent->__SET('amountIMSSSettlement', $rSettlement->amountIMSSSettlement);
					$ent->__SET('amountBulletSettlement', $rSettlement->amountBulletSettlement);
					$ent->__SET('amountAsleepSettlement', $rSettlement->amountAsleepSettlement);
					$ent->__SET('amountFiaSettlement', $rSettlement->amountFiaSettlement);
					$ent->__SET('amountSegSettlement', $rSettlement->amountSegSettlement);
					$ent->__SET('amountPreSettlement', $rSettlement->amountPreSettlement);
					$ent->__SET('amountInfSettlement', $rSettlement->amountInfSettlement);
					$ent->__SET('amountViaSettlement', $rSettlement->amountViaSettlement);
					$ent->__SET('amountCountingSettlement', $rSettlement->amountCountingSettlement);
					$ent->__SET('amountEPSSettlement', $rSettlement->amountEPSSettlement);
					$ent->__SET('amountRechargeSettlement', $rSettlement->amountRechargeSettlement);
					$ent->__SET('amountCardSettlement', $rSettlement->amountCardSettlement);
					$ent->__SET('amountTotalSettlement', $rSettlement->amountTotalSettlement);
					$ent->__SET('amountPassageSettlement', $rSettlement->amountPassageSettlement);
					$ent->__SET('guiaSettlement', $rSettlement->guiaSettlement);
					$ent->__SET('guiaFileSettlement', $rSettlement->guiaFileSettlement);
					$ent->__SET('turnSettlement', $rSettlement->turnSettlement);
					$ent->__SET('KMSettlement', $rSettlement->KMSettlement);
					$ent->__SET('consumptionSettlement', $rSettlement->consumptionSettlement);
					$ent->__SET('dieselSettlement', $rSettlement->dieselSettlement);
					$ent->__SET('dieselLSettlement', $rSettlement->dieselLSettlement);
					$ent->__SET('whitholdingSettlement', $rSettlement->whitholdingSettlement);
					$ent->__SET('salarySettlement', $rSettlement->salarySettlement);
					$ent->__SET('taxSettlement', $rSettlement->taxSettlement);
					$ent->__SET('perceptionSettlement', $rSettlement->perceptionSettlement);
					$ent->__SET('cashSettlement', $rSettlement->cashSettlement);
					$ent->__SET('commentsSettlement', $rSettlement->commentsSettlement);
					$ent->__SET('dateSettlement', $rSettlement->dateSettlement);
					$ent->__SET('timeSettlement', $rSettlement->timeSettlement);
					$ent->__SET('datePaySettlement', $rSettlement->datePaySettlement);
					$ent->__SET('timePaySettlement', $rSettlement->timePaySettlement);
					$ent->__SET('statusSettlement', $rSettlement->statusSettlement);
					$ent->__SET('debtSettlement', $rSettlement->debtSettlement);
					$ent->__SET('amountDebtPaySettlement', $rSettlement->amountDebtPaySettlement);
					$ent->__SET('paymentsDebtSettlement', $rSettlement->paymentsDebtSettlement);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function SettlementUpdateCut(Enti $data){
			try 
			{
				$sql = "UPDATE ta_7_settlement SET 
							cutSettlement = ?
						WHERE idSettlement = ?";
				$res=$this->pdo->prepare($sql)
					->execute(
					array(
						$data->__GET('cutSettlement'),
						$data->__GET('idSettlement')
						)
					);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function SettlementReport($aFiltros){
			try 
			{
				$result=array();
				$e_SQL = "SELECT
				  t7s.dateSettlement,
				  t7u.employeeUnloading,
				  t7e.namesEmployee,
				  CONCAT(t7e.namesEmployee,' ', t7e.mlastnameEmployee,' ', t7e.lastnameEmployee ) AS nameEmployee,
				  t7u.busUnloading,
				  t1b.economicBus,
				  t7s.routeSettlement,
				  t1r.nameRoute,
				  IF(t7s.statusSettlement = 1,'Por pagar','Pagado') AS statusSettlement,
				  t7s.datePaySettlement,
				  t7s.timePaySettlement
				FROM tbg.ta_7_settlement AS t7s
				LEFT JOIN tbg. ta_7_unloading  AS t7u ON t7s.idUnloading = t7u.idUnloading
				LEFT JOIN tbg.ta_7_employee AS t7e ON t7u.employeeUnloading = t7e.idEmployee
				LEFT JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
				LEFT JOIN tbg.ta_1_route AS t1r ON t7s.routeSettlement = t1r.idRoute
				WHERE 1 = 1";
				if(isset($aFiltros["startDateSettlement"]) && isset($aFiltros["endDateSettlement"])){
					$e_SQL .= " AND dateSettlement BETWEEN '{$aFiltros["startDateSettlement"]}' AND '{$aFiltros["endDateSettlement"]}'\n";
				}
				if(!empty($aFiltros["employeeUnloading"])){
					$e_SQL .= " AND employeeUnloading = '{$aFiltros["employeeUnloading"]}'\n";
				}
				if(!empty($aFiltros["busUnloading"])){
					$e_SQL .= " AND busUnloading = '{$aFiltros["busUnloading"]}'\n";
				}
				if(!empty($aFiltros["routeSettlement"])){
					$e_SQL .= " AND routeSettlement = '{$aFiltros["routeSettlement"]}'\n";
				}
				if(!empty($aFiltros["statusSettlement"])){
					$e_SQL .= " AND statusSettlement = '{$aFiltros["statusSettlement"]}'\n";
				}
				$stm = $this->pdo->prepare($e_SQL);	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('dateSettlement',$r->dateSettlement);
					$ent->__SET('employeeUnloading',$r->employeeUnloading);
					$ent->__SET('nameEmployee',$r->nameEmployee);
					$ent->__SET('busUnloading',$r->busUnloading);
					$ent->__SET('economicBus',$r->economicBus);
					$ent->__SET('routeSettlement',$r->routeSettlement);
					$ent->__SET('nameRoute',$r->nameRoute);
					$ent->__SET('statusSettlement',$r->statusSettlement);
					$ent->__SET('datePaySettlement',$r->datePaySettlement);
					$ent->__SET('timePaySettlement',$r->timePaySettlement);
					/*
					$ent->__SET('idAsleep', $r->idAsleep);
					$ent->__SET('employeeAsleep', $r->employeeAsleep);
					$ent->__SET('startdateAsleep', $r->startdateAsleep);
					$ent->__SET('endtdateAsleep', $r->endtdateAsleep);
					$ent->__SET('countingAsleep', $r->countingAsleep);
					$ent->__SET('commentsAsleep', $r->commentsAsleep);
					$ent->__SET('dateAsleep', $r->dateAsleep);
					$ent->__SET('timeAsleep', $r->timeAsleep);
					*/
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		////////////  ***report***   ////////////////

		public function ReportList($rad,$bu,$buu,$du,$sdu,$edu){
			$e_SQL = "";
			$result=array();
			try 
			{
				// echo " bu: ".$bu;
				// echo " buu: ".$buu;
				// echo " du: ".$du;
				// echo " sdu: ".$sdu;
				// echo " edu: ".$edu;
				// echo " rad: ".$rad;

				/*
				if ($bu ==0 && $buu ==0 && $du ==0 ) {
					$result=array();
					$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_unloading AS t7u
										INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
										INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
										INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
										WHERE t7u.wU = ? AND
										t7u.statusUnloading >= 4
										ORDER BY dateUnloading,timeUnloading ASC ");

										$stm->execute(array($bu));
										foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
										{
											$ent = new Enti();
											$ent->__SET('idUnloading', $r->idUnloading);
											$ent->__SET('serialUnloading', $r->serialUnloading);
											$ent->__SET('nameBase', $r->nameBase);
											$ent->__SET('economicBus', $r->economicBus);
											$ent->__SET('economicBus', $r->economicBus);
											$ent->__SET('namesEmployee', $r->namesEmployee);
											$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
											$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
											$ent->__SET('countingOutUnloading', $r->countingOutUnloading);
											$ent->__SET('countingInUnloading', $r->countingInUnloading);
											$ent->__SET('commentsUnloading', $r->commentsUnloading);
											$ent->__SET('dateUnloading', $r->dateUnloading);
											$ent->__SET('timeUnloading', $r->timeUnloading);
											$ent->__SET('cashUnloadingEPS', $r->cashUnloadingEPS);
											$ent->__SET('cardUnloadingEPS', $r->cardUnloadingEPS);
											$ent->__SET('passageUnloadingEPS', $r->passageUnloadingEPS);
											$ent->__SET('rechargeUnloadingEPS', $r->rechargeUnloadingEPS);
											$ent->__SET('commentsUnloadingEPS', $r->commentsUnloadingEPS);
											$ent->__SET('dateUnloadingEPS', $r->dateUnloadingEPS);
											$ent->__SET('timeUnloadingEPS', $r->timeUnloadingEPS);
											$ent->__SET('countingUnloadingC', $r->countingUnloadingC);
											$ent->__SET('commentsUnloadingC', $r->commentsUnloadingC);
											$ent->__SET('dateUnloadingC', $r->dateUnloadingC);
											$ent->__SET('timeUnloadingC', $r->timeUnloadingC);
											$ent->__SET('countingUnloadingT', $r->countingUnloadingT);
											$ent->__SET('commentsUnloadingT', $r->commentsUnloadingT);
											$ent->__SET('dateUnloadingT', $r->dateUnloadingT);
											$ent->__SET('timeUnloadingT', $r->timeUnloadingT);
											$result[] = $ent;
										}
					
									//print_r($result);
									return $result;
				}
				
				if ($rad=='U') {
					if ($bu != 0 && $buu == 0 && $du == 0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.baseUnloading = ? AND 
											t7u.wU = ? AND
											t7u.wU = ? AND
											t7u.dateUnloading >= ? AND t7u.dateUnloading <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
					if ($bu != 0 && $buu != 0 && $du == 0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.baseUnloading = ? AND 
											t7u.busUnloading = ? AND
											t7u.wU = ? AND
											t7u.dateUnloading >= ? AND t7u.dateUnloading <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
					if ($bu != 0 && $buu != 0 && $du != 0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.baseUnloading = ? AND 
											t7u.busUnloading = ? AND
											t7u.employeeUnloading = ? AND
											t7u.dateUnloading >= ? AND t7u.dateUnloading <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
					if ($bu !=0 && $buu ==0 && $du !=0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.baseUnloading = ? AND 
											t7u.wU = ? AND
											t7u.employeeUnloading = ? AND
											t7u.dateUnloading >= ? AND t7u.dateUnloading <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
					if ($bu ==0 && $buu !=0 && $du ==0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.wU = ? AND 
											t7u.busUnloading = ? AND
											t7u.wU = ? AND
											t7u.dateUnloading >= ? AND t7u.dateUnloading <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
					if ($bu ==0 && $buu !=0 && $du !=0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.wU = ? AND 
											t7u.busUnloading = ? AND
											t7u.employeeUnloading = ? AND
											t7u.dateUnloading >= ? AND t7u.dateUnloading <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
					if ($bu ==0 && $buu ==0 && $du !=0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.wU = ? AND 
											t7u.wU = ? AND
											t7u.employeeUnloading = ? AND
											t7u.dateUnloading >= ? AND t7u.dateUnloading <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
				}

				if ($rad=='V') {
					if ($bu != 0 && $buu == 0 && $du == 0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.baseUnloading = ? AND 
											t7u.wU = ? AND
											t7u.wU = ? AND
											t7u.dateUnloadingC >= ? AND t7u.dateUnloadingC <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
					if ($bu != 0 && $buu != 0 && $du == 0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.baseUnloading = ? AND 
											t7u.busUnloading = ? AND
											t7u.wU = ? AND
											t7u.dateUnloadingC >= ? AND t7u.dateUnloadingC <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
					if ($bu != 0 && $buu != 0 && $du != 0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.baseUnloading = ? AND 
											t7u.busUnloading = ? AND
											t7u.employeeUnloading = ? AND
											t7u.dateUnloadingC >= ? AND t7u.dateUnloadingC <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
					if ($bu !=0 && $buu ==0 && $du !=0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.baseUnloading = ? AND 
											t7u.wU = ? AND
											t7u.employeeUnloading = ? AND
											t7u.dateUnloadingC >= ? AND t7u.dateUnloadingC <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
					if ($bu ==0 && $buu !=0 && $du ==0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.wU = ? AND 
											t7u.busUnloading = ? AND
											t7u.wU = ? AND
											t7u.dateUnloadingC >= ? AND t7u.dateUnloadingC <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
					if ($bu ==0 && $buu !=0 && $du !=0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.wU = ? AND 
											t7u.busUnloading = ? AND
											t7u.employeeUnloading = ? AND
											t7u.dateUnloadingC >= ? AND t7u.dateUnloadingC <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
					if ($bu ==0 && $buu ==0 && $du !=0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.wU = ? AND 
											t7u.wU = ? AND
											t7u.employeeUnloading = ? AND
											t7u.dateUnloadingC >= ? AND t7u.dateUnloadingC <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
				}

				if ($rad=='B') {
					if ($bu != 0 && $buu == 0 && $du == 0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.baseUnloading = ? AND 
											t7u.wU = ? AND
											t7u.wU = ? AND
											t7u.dateUnloadingB >= ? AND t7u.dateUnloadingB <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
					if ($bu != 0 && $buu != 0 && $du == 0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.baseUnloading = ? AND 
											t7u.busUnloading = ? AND
											t7u.wU = ? AND
											t7u.dateUnloadingB >= ? AND t7u.dateUnloadingB <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
					if ($bu != 0 && $buu != 0 && $du != 0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.baseUnloading = ? AND 
											t7u.busUnloading = ? AND
											t7u.employeeUnloading = ? AND
											t7u.dateUnloadingB >= ? AND t7u.dateUnloadingB <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
					if ($bu !=0 && $buu ==0 && $du !=0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.baseUnloading = ? AND 
											t7u.wU = ? AND
											t7u.employeeUnloading = ? AND
											t7u.dateUnloadingB >= ? AND t7u.dateUnloadingB <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
					if ($bu ==0 && $buu !=0 && $du ==0) {
						$$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.wU = ? AND 
											t7u.busUnloading = ? AND
											t7u.wU = ? AND
											t7u.dateUnloadingB >= ? AND t7u.dateUnloadingB <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
					if ($bu ==0 && $buu !=0 && $du !=0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.wU = ? AND 
											t7u.busUnloading = ? AND
											t7u.employeeUnloading = ? AND
											t7u.dateUnloadingB >= ? AND t7u.dateUnloadingB <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
					if ($bu ==0 && $buu ==0 && $du !=0) {
						$e_SQL = "SELECT * FROM tbg.ta_7_unloading AS t7u
											INNER JOIN tbg.ca_1_base AS c1b ON t7u.baseUnloading = c1b.idBase
											INNER JOIN tbg.ta_1_bus AS t1b ON t7u.busUnloading = t1b.idBus
											INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee
											WHERE t7u.wU = ? AND 
											t7u.wU = ? AND
											t7u.employeeUnloading = ? AND
											t7u.dateUnloadingB >= ? AND t7u.dateUnloadingB <= ? AND 
											t7u.statusUnloading = 4
											ORDER BY dateUnloading,timeUnloading ASC ";
					}
				}
				*/
				
				$e_SQL = "SELECT *
				FROM tbg.ta_7_unloading      AS t7u
				INNER JOIN tbg.ca_1_base     AS c1b ON t7u.baseUnloading     = c1b.idBase
				INNER JOIN tbg.ta_1_bus      AS t1b ON t7u.busUnloading      = t1b.idBus
				INNER JOIN tbg.ta_7_Employee AS t1d ON t7u.employeeUnloading = t1d.idEmployee\n";
				switch ($rad) {
					case 'U':
						$e_SQL .= "WHERE t7u.dateUnloading >= ? AND t7u.dateUnloading <= ?\n";
					break;
					case 'V':
						$e_SQL .= "WHERE t7u.dateUnloadingC >= ? AND t7u.dateUnloadingC <= ?\n";
					break;
					case 'B':
						$e_SQL .= "WHERE t7u.dateUnloadingB >= ? AND t7u.dateUnloadingB <= ?\n";
					break;
					default:
						$e_SQL .= "WHERE 1 = 1 #t7u.dateUnloadingB >= ? AND t7u.dateUnloadingB <= ?\n";
					break;
				}
				if($bu != 0){
					$e_SQL .= "AND t7u.baseUnloading = ? \n";
				} else {
					$e_SQL .= "#AND t7u.baseUnloading = ? \n";
				}
				if($bu != 0){
					$e_SQL .= "AND t7u.busUnloading = ? \n";
				} else {
					$e_SQL .= "#AND t7u.busUnloading = ? \n";
				}
				if($bu != 0){
					$e_SQL .= "AND t7u.employeeUnloading = ? \n";
				} else {
					$e_SQL .= "#AND t7u.employeeUnloading = ? \n";
				}
				$e_SQL .= "AND t7u.statusUnloading >= 4
				ORDER BY idUnloading ASC";
				$stm = $this->pdo->prepare($e_SQL);
				$stm->execute(array($sdu,$edu,$bu,$buu,$du));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idUnloading', $r->idUnloading);
					$ent->__SET('serialUnloading', $r->serialUnloading);
					$ent->__SET('nameBase', $r->nameBase);
					$ent->__SET('economicBus', $r->economicBus);
					$ent->__SET('economicBus', $r->economicBus);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
					$ent->__SET('countingOutUnloading', $r->countingOutUnloading);
					$ent->__SET('countingInUnloading', $r->countingInUnloading);
					$ent->__SET('commentsUnloading', $r->commentsUnloading);
					$ent->__SET('dateUnloading', $r->dateUnloading);
					$ent->__SET('timeUnloading', $r->timeUnloading);
					$ent->__SET('countingUnloadingC', $r->countingUnloadingC);
					$ent->__SET('commentsUnloadingC', $r->commentsUnloadingC);
					$ent->__SET('dateUnloadingC', $r->dateUnloadingC);
					$ent->__SET('timeUnloadingC', $r->timeUnloadingC);
					$ent->__SET('countingUnloadingT', $r->countingUnloadingT);
					$ent->__SET('commentsUnloadingT', $r->commentsUnloadingT);
					$ent->__SET('dateUnloadingT', $r->dateUnloadingT);
					$ent->__SET('timeUnloadingT', $r->timeUnloadingT);
					$result[] = $ent;
				}
				//print_r($result);
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		////////////  ***Ticket***   ////////////////

		public function TicketList($idUnloading){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_ticket AS tt
											INNER JOIN tbg.ca_7_concept AS cc ON tt.conceptTicket=cc.idConcept
											WHERE tt.statusTicket = 1 AND unloadingTicket = ?");	
				$stm->execute(array($idUnloading));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idTicket', $r->idTicket);
					$ent->__SET('userTicket', $r->userTicket);
					$ent->__SET('unloadingTicket', $r->unloadingTicket);
					$ent->__SET('baseTicket', $r->baseTicket);
					$ent->__SET('busTicket', $r->busTicket);
					$ent->__SET('employeeTicket', $r->employeeTicket);
					$ent->__SET('conceptTicket', $r->conceptTicket);
					$ent->__SET('codeConcept', $r->codeConcept);
					$ent->__SET('nameConcept', $r->nameConcept);
					$ent->__SET('valueConcept', $r->valueConcept);
					$ent->__SET('quantityTicket', $r->quantityTicket);
					$ent->__SET('totalTicket', $r->totalTicket);
					$ent->__SET('statusTicket', $r->statusTicket);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function TicketAdd(Enti $data){
			//print_r($data);
			try {
				$sql = "INSERT INTO tbg.ta_7_ticket (userTicket,unloadingTicket,baseTicket,
				busTicket,employeeTicket,conceptTicket,quantityTicket,totalTicket) 
					VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('userTicket'),
					$data->__GET('unloadingTicket'),
					$data->__GET('baseTicket'),
					$data->__GET('busTicket'),
					$data->__GET('employeeTicket'),
					$data->__GET('conceptTicket'),
					$data->__GET('quantityTicket'),
					$data->__GET('totalTicket')
					)
				);
				$idTicket = $this->pdo->lastInsertId();
				return $idTicket;
			} catch (Exception $e) 
			{die($e->getMessage());}
			return $res;
		}
		public function TicketDelete(Enti $data){
			//print_r($data);
			try 
			{
				$sql = "UPDATE ta_7_ticket SET 
							statusTicket = ?
						WHERE idTicket = ?";
				$res=$this->pdo->prepare($sql)
					->execute(
					array(
					$data->__GET('statusTicket'),
						$data->__GET('idTicket')
						)
					);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function TicketUpdate(Enti $data){
			//print_r($data);
			try 
			{
				$sql = "UPDATE ta_7_ticket SET 
							statusTicket = ?
						WHERE unloadingTicket = ?";
				$res=$this->pdo->prepare($sql)
					->execute(
					array(
					$data->__GET('statusTicket'),
						$data->__GET('unloadingTicket')
						)
					);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function UnloadingTicketUpdate(Enti $data){
			//print_r($data);
			try 
			{
				$sql = "UPDATE ta_7_unloading SET 
							userUnloadingT = ?,
							countingUnloadingT = ?,
							commentsUnloadingT = ?,
							dateUnloadingT = ?,
							timeUnloadingT = ?,
							statusUnloading = ?
						WHERE idUnloading = ?";
				$res=$this->pdo->prepare($sql)
					->execute(
					array(
						$data->__GET('userUnloadingT'),
						$data->__GET('countingUnloadingT'),
						$data->__GET('commentsUnloadingT'),
						$data->__GET('dateUnloadingT'),
						$data->__GET('timeUnloadingT'),
						$data->__GET('statusUnloading'),
						$data->__GET('idUnloading')
						)
					);
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function TicketListSum(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_7_ticket WHERE statusTicket = 1 ");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idTicket', $r->idTicket);
					$ent->__SET('userTicket', $r->userTicket);
					$ent->__SET('unloadingTicket', $r->unloadingTicket);
					$ent->__SET('baseTicket', $r->baseTicket);
					$ent->__SET('busTicket', $r->busTicket);
					$ent->__SET('employeeTicket', $r->employeeTicket);
					$ent->__SET('conceptTicket', $r->conceptTicket);
					$ent->__SET('quantityTicket', $r->quantityTicket);
					$ent->__SET('totalTicket', $r->totalTicket);
					$ent->__SET('statusTicket', $r->statusTicket);
					$result[] = $ent;
				}
				//print_r($result);
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		////////////  ***concept***   ////////////////
		
		public function ConceptList(){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_7_concept ORDER BY codeConcept ASC ");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idConcept', $r->idConcept);
					$ent->__SET('codeConcept', $r->codeConcept);
					$ent->__SET('nameConcept', $r->nameConcept);
					$ent->__SET('valueConcept', $r->valueConcept);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		
		////////////  *** cut ***   ////////////////

		public function CutListSettlementPay(){
			try 
			{
				$result=array();
				$eDateHow = date('Y-m-d');
				$eSQL ="SELECT
					t7u.idUnloading,
					t7s.idSettlement,
					serialUnloading,
					nameBase,
					codeBus,
					economicBus,
					namesEmployee,
					lastnameEmployee,
					mlastnameEmployee,
					nameRoute,
					perceptionSettlement,
					cashSettlement
				FROM tbg.ta_7_settlement      AS t7s
				INNER JOIN tbg.ta_1_route     AS t1r ON t7s.routeSettlement   = t1r.idRoute
				INNER JOIN tbg.ta_7_unloading AS t7u ON t7s.idUnloading       = t7u.idUnloading
				INNER JOIN tbg.ca_1_base      AS c1b ON t7u.baseUnloading     = c1b.idBase
				INNER JOIN tbg.ta_1_bus       AS t1b ON t7u.busUnloading      = t1b.idBus
				INNER JOIN tbg.ta_7_Employee  AS t1d ON t7u.employeeUnloading = t1d.idEmployee
				WHERE t7s.statusSettlement = '2'
				AND datePaySettlement = ?
				AND cutSettlement IS NULL";
				$stm = $this->pdo->prepare($eSQL);
				$stm->execute(array($eDateHow));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idUnloading', $r->idUnloading);
					$ent->__SET('idSettlement', $r->idSettlement);
					$ent->__SET('serialUnloading', $r->serialUnloading);
					$ent->__SET('nameBase', $r->nameBase);
					$ent->__SET('codeBus', $r->codeBus);
					$ent->__SET('economicBus', $r->economicBus);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
					$ent->__SET('nameRoute', $r->nameRoute);
					$ent->__SET('perceptionSettlement', $r->perceptionSettlement);
					$ent->__SET('cashSettlement', $r->cashSettlement);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function cutAdd(Enti $data){
			try {
				$sql = "INSERT INTO tbg.ta_7_cut (userCut, amountCut, amountCashCut, dateCut, timeCut, statusCut) 
					VALUES (?, ?, ?, ?, ?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('userCut'),
					$data->__GET('amountCut'),
					$data->__GET('amountCashCut'),
					$data->__GET('dateCut'),
					$data->__GET('timeCut'),
					$data->__GET('statusCut')
					)
				);
				$idCut = $this->pdo->lastInsertId();
				return $idCut;
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function cutListSettlement($idCut){
			$result=array();
			$eSQL ="SELECT
				t7u.idUnloading,
				t7s.idSettlement,
				serialUnloading,
				nameBase,
				codeBus,
				economicBus,
				namesEmployee,
				lastnameEmployee,
				mlastnameEmployee,
				nameRoute,
				perceptionSettlement,
				cashSettlement,
				datePaySettlement,
				debtSettlement,
				paymentsDebtSettlement,
				datePayDebtSettlement
			FROM tbg.ta_7_settlement      AS t7s
			INNER JOIN tbg.ta_1_route     AS t1r ON t7s.routeSettlement   = t1r.idRoute
			INNER JOIN tbg.ta_7_unloading AS t7u ON t7s.idUnloading       = t7u.idUnloading
			INNER JOIN tbg.ca_1_base      AS c1b ON t7u.baseUnloading     = c1b.idBase
			INNER JOIN tbg.ta_1_bus       AS t1b ON t7u.busUnloading      = t1b.idBus
			INNER JOIN tbg.ta_7_Employee  AS t1d ON t7u.employeeUnloading = t1d.idEmployee
			WHERE cutSettlement = ?";
			try {
				$stm = $this->pdo->prepare($eSQL);
				$stm->execute(array($idCut));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idUnloading', $r->idUnloading);
					$ent->__SET('idSettlement', $r->idSettlement);
					$ent->__SET('serialUnloading', $r->serialUnloading);
					$ent->__SET('nameBase', $r->nameBase);
					$ent->__SET('codeBus', $r->codeBus);
					$ent->__SET('economicBus', $r->economicBus);
					$ent->__SET('namesEmployee', $r->namesEmployee);
					$ent->__SET('lastnameEmployee', $r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee', $r->mlastnameEmployee);
					$ent->__SET('nameRoute', $r->nameRoute);
					$ent->__SET('perceptionSettlement', $r->perceptionSettlement);
					$ent->__SET('cashSettlement', $r->cashSettlement);
					$ent->__SET('datePaySettlement', $r->datePaySettlement);
					$ent->__SET('debtSettlement', $r->debtSettlement);
					$ent->__SET('paymentsDebtSettlement', $r->paymentsDebtSettlement);
					$ent->__SET('datePayDebtSettlement', $r->datePayDebtSettlement);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e){
				die($e->getMessage());
			}
		}
		public function cutData($idCut){
			$result=array();
			$e_SQL = "SELECT
			  idCut,
			  userCut,
			  nameUser,
			  fullNameUser,
			  amountCut,
			  amountCashCut,
			  dateCut,
			  timeCut
			FROM tbg.ta_7_cut AS t7c
			INNER JOIN tbg.ta_1_user AS t1u ON t7c.userCut = t1u.idUser
			WHERE idCut =  ?";
			try {
				$stm = $this->pdo->prepare($e_SQL);	
				$stm->execute(array($idCut));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idCut', $r->idCut);
					$ent->__SET('userCut', $r->userCut);
					$ent->__SET('nameUser', $r->nameUser);
					$ent->__SET('fullNameUser', $r->fullNameUser);
					$ent->__SET('amountCut', $r->amountCut);
					$ent->__SET('amountCashCut', $r->amountCashCut);
					$ent->__SET('dateCut', $r->dateCut);
					$ent->__SET('timeCut', $r->timeCut);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e){
				die($e->getMessage());
			}
		}
		public function cutList(){
			$result=array();
			$e_SQL = "SELECT
			  idCut,
			  userCut,
			  nameUser,
			  fullNameUser,
			  amountCut,
			  amountCashCut,
			  dateCut,
			  timeCut
			FROM tbg.ta_7_cut AS t7c
			INNER JOIN tbg.ta_1_user AS t1u ON t7c.userCut = t1u.idUser
			WHERE statusCut = '1'";
			try {
				$stm = $this->pdo->prepare($e_SQL);	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idCut', $r->idCut);
					$ent->__SET('userCut', $r->userCut);
					$ent->__SET('nameUser', $r->nameUser);
					$ent->__SET('fullNameUser', $r->fullNameUser);
					$ent->__SET('amountCut', $r->amountCut);
					$ent->__SET('amountCashCut', $r->amountCashCut);
					$ent->__SET('dateCut', $r->dateCut);
					$ent->__SET('timeCut', $r->timeCut);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e){
				die($e->getMessage());
			}
		}

		//////////// *** Credentials *** ////////////////

		public function CredentialAdd(Enti $data){
			try {
				$sql = "INSERT INTO tbg.ta_1_credentials (idCredential, nameCredential, descriptionCredential) VALUES (?, ?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('idCredential'),
					$data->__GET('nameCredential'),
					$data->__GET('descriptionCredential')
					)
				);
				$idCredential = $this->pdo->lastInsertId();
				return $idCredential;
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function CredentialUpdate(Enti $data){
			try {
				$aNewData = array(
					'nameCredential' => $data->__GET('nameCredential'),
					'descriptionCredential' => $data->__GET('descriptionCredential'),
					'idCredential' => $data->__GET('idCredential')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ta_1_credentials','idCredential = '.$data->__GET('idCredential'), 'Credenciales');
				$sql = "UPDATE ta_1_credentials SET
							nameCredential = ?,
							descriptionCredential = ?
						WHERE idCredential = ?";
				$res=$this->pdo->prepare($sql)->execute($aNewValues);
						/*
				$res=$this->pdo->prepare($sql)->execute(array(
						$data->__GET('nameCredential'),
						$data->__GET('descriptionCredential'),
						$data->__GET('idCredential')
					)
				);
				*/
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function CredentialInactive(Enti $data){
			try {
				$aNewData = array(
					'statusCredential' => $data->__GET('statusCredential'),
					'idCredential' => $data->__GET('idCredential')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ta_1_credentials','idCredential = '.$data->__GET('idCredential'), 'Credenciales');
				$sql = "UPDATE ta_1_credentials SET
							statusCredential = ?
						WHERE idCredential = ?";
				$res = $this->pdo->prepare($sql)->execute($aNewValues);
						/*
				$res=$this->pdo->prepare($sql)->execute(array(
						$data->__GET('statusCredential'),
						$data->__GET('idCredential')
					)
				);
				*/
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function CredentialsList(){
			try {
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_1_credentials WHERE statusCredential = '1'");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idCredential', $r->idCredential);
					$ent->__SET('nameCredential', $r->nameCredential);
					$ent->__SET('descriptionCredential', $r->descriptionCredential);
					$ent->__SET('statusCredential', $r->statusCredential);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e){
				die($e->getMessage());
			}
		}
		public function CredentialListU($idCredential){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ta_1_credentials WHERE idCredential = ? ");	
				$stm->execute(array($idCredential));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idCredential', $r->idCredential);
					$ent->__SET('nameCredential', $r->nameCredential);
					$ent->__SET('descriptionCredential', $r->descriptionCredential);
					$ent->__SET('statusCredential', $r->statusCredential);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		//////////// *** Menus  *** ////////////////

		public function MenuAdd(Enti $data){
			try {
				$sql = "INSERT INTO tbg.ca_1_menus (nameMenu, typeMenu, nodeMenu, orderMenu, iconMenu, credentialMenu, sheetMenu) VALUES (?, ?, ?, ?, ?, ?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('nameMenu'),
					$data->__GET('typeMenu'),
					$data->__GET('nodeMenu'),
					$data->__GET('orderMenu'),
					$data->__GET('iconMenu'),
					$data->__GET('credentialMenu'),
					$data->__GET('sheetMenu')
					)
				);
				$idMenu = $this->pdo->lastInsertId();
				return $idMenu;
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function MenuListCategory(){
			try {
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_1_menus WHERE statusMenu = '1' AND typeMenu = 'categoria'");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idMenu', $r->idMenu);
					$ent->__SET('nameMenu', $r->nameMenu);
					$ent->__SET('typeMenu', $r->typeMenu);
					$ent->__SET('nodeMenu', $r->nodeMenu);
					$ent->__SET('orderMenu', $r->orderMenu);
					$ent->__SET('iconMenu', $r->iconMenu);
					$ent->__SET('credentialMenu', $r->credentialMenu);
					$ent->__SET('sheetMenu', $r->sheetMenu);
					$ent->__SET('statusMenu', $r->statusMenu);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e){
				die($e->getMessage());
			}
		}
		public function MenuListU($idMenu){
			try {
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_1_menus WHERE idMenu = ? ");	
				$stm->execute(array($idMenu));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idMenu', $r->idMenu);
					$ent->__SET('nameMenu', $r->nameMenu);
					$ent->__SET('typeMenu', $r->typeMenu);
					$ent->__SET('nodeMenu', $r->nodeMenu);
					$ent->__SET('orderMenu', $r->orderMenu);
					$ent->__SET('iconMenu', $r->iconMenu);
					$ent->__SET('credentialMenu', $r->credentialMenu);
					$ent->__SET('sheetMenu', $r->sheetMenu);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{
				die($e->getMessage());
			}
		}
		public function MenuUpdate(Enti $data){
			try {
				$aNewData = array(
					'nameMenu' => $data->__GET('nameMenu'),
					'typeMenu' => $data->__GET('typeMenu'),
					'nodeMenu' => $data->__GET('nodeMenu'),
					'orderMenu' => $data->__GET('orderMenu'),
					'iconMenu' => $data->__GET('iconMenu'),
					'credentialMenu' => $data->__GET('credentialMenu'),
					'sheetMenu' => $data->__GET('sheetMenu'),
					'idMenu' => $data->__GET('idMenu')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ca_1_menus','idMenu = '.$data->__GET('idMenu'), 'Menus');
				$sql = "UPDATE ca_1_menus SET
							nameMenu = ?,
							typeMenu = ?,
							nodeMenu = ?,
							orderMenu = ?,
							iconMenu = ?,
							credentialMenu = ?,
							sheetMenu = ?
						WHERE idMenu = ?";
				$res = $this->pdo->prepare($sql)->execute($aNewValues);
						/*
				$res=$this->pdo->prepare($sql)->execute(array(
						$data->__GET('nameMenu'),
						$data->__GET('typeMenu'),
						$data->__GET('nodeMenu'),
						$data->__GET('orderMenu'),
						$data->__GET('iconMenu'),
						$data->__GET('credentialMenu'),
						$data->__GET('sheetMenu'),
						$data->__GET('idMenu')
					)
				);
				*/
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function MenuInactive(Enti $data){
			try {
				$aNewData = array(
					'statusMenu' => $data->__GET('statusMenu'),
					'idMenu' => $data->__GET('idMenu')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ca_1_menus','idMenu = '.$data->__GET('idMenu') , 'Menus');
				$sql = "UPDATE ca_1_menus SET
							statusMenu = ?
						WHERE idMenu = ?";
				$res=$this->pdo->prepare($sql)->execute($aNewValues);
						/*
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('statusMenu'),
						$data->__GET('idMenu')
					)
				);
				*/
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}

		//////////// *** ValidateCredentials  *** ////////////////

		public function getPositionCredentials($idUser){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT
				  idUSer,
				  nameUser,
				  positionUser,
				  namePosition,
				  credentialPosition
				FROM tbg.ta_1_user
				JOIN tbg.ca_7_position ON positionUser = idPosition
				WHERE statusUser = '1' AND idUSer  = ?");	
				$stm->execute(array($idUser,));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idUSer', $r->idUSer);
					$ent->__SET('nameUser', $r->nameUser);
					$ent->__SET('positionUser', $r->positionUser);
					$ent->__SET('namePosition', $r->namePosition);
					$ent->__SET('credentialPosition', $r->credentialPosition);
					$eCredentialPosition = $r->credentialPosition;
					//$result[] = $ent;
				}
				$aCrentialsPosition = json_decode($eCredentialPosition,true);
				$eNomCredential = implode('","',$aCrentialsPosition);
				$e_SQL = "SELECT nameCredential FROM tbg.ta_1_credentials WHERE  statusCredential = '1' AND nameCredential IN (\"{$eNomCredential}\")";
				$stm = $this->pdo->prepare("SELECT nameCredential FROM tbg.ta_1_credentials WHERE  statusCredential = '1' AND nameCredential IN (\"{$eNomCredential}\")");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('nameCredential', $r->nameCredential);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{
				//die($e->getMessage());
				return 0;
			}
		}
		public function MenuListNode($idNode){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_1_menus WHERE statusMenu = '1' AND nodeMenu = {$idNode} ORDER BY orderMenu ASC");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idMenu', $r->idMenu);
					$ent->__SET('nameMenu', $r->nameMenu);
					$ent->__SET('typeMenu', $r->typeMenu);
					$ent->__SET('nodeMenu', $r->nodeMenu);
					$ent->__SET('orderMenu', $r->orderMenu);
					$ent->__SET('iconMenu', $r->iconMenu);
					$ent->__SET('credentialMenu', $r->credentialMenu);
					$ent->__SET('sheetMenu', $r->sheetMenu);
					$ent->__SET('statusMenu', $r->statusMenu);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{
				die($e->getMessage());
			}
		}

		//////////// *** Driver  *** ////////////////

		public function DriverList(){
			try {
				$result=array();
				$stm = $this->pdo->prepare("SELECT
				  idDriver,
				  codeDriver,
				  nameDriver,
				  ageDriver,
				  licenseDriver,
				  telDriver,
				  dateDriver,
				  timeDriver,
				  statusDriver
				FROM tbg.ta_1_driver WHERE statusDriver = '1'");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idDriver', $r->idDriver);
					$ent->__SET('codeDriver', $r->codeDriver);
					$ent->__SET('nameDriver', $r->nameDriver);
					$ent->__SET('ageDriver', $r->ageDriver);
					$ent->__SET('licenseDriver', $r->licenseDriver);
					$ent->__SET('telDriver', $r->telDriver);
					$ent->__SET('dateDriver', $r->dateDriver);
					$ent->__SET('timeDriver', $r->timeDriver);
					$ent->__SET('statusDriver', $r->statusDriver);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function DriverListData($idDriver){
			try {
				$result=array();
				$stm = $this->pdo->prepare("SELECT
				  idDriver,
				  codeDriver,
				  nameDriver,
				  ageDriver,
				  licenseDriver,
				  telDriver,
				  dateDriver,
				  timeDriver,
				  statusDriver
				FROM tbg.ta_1_driver WHERE statusDriver = 1 AND idDriver = ? ");	
				$stm->execute(array($idDriver));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idDriver', $r->idDriver);
					$ent->__SET('codeDriver', $r->codeDriver);
					$ent->__SET('nameDriver', $r->nameDriver);
					$ent->__SET('ageDriver', $r->ageDriver);
					$ent->__SET('licenseDriver', $r->licenseDriver);
					$ent->__SET('telDriver', $r->telDriver);
					$ent->__SET('dateDriver', $r->dateDriver);
					$ent->__SET('timeDriver', $r->timeDriver);
					$ent->__SET('statusDriver', $r->statusDriver);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function DriverAdd(Enti $data){
			try {
				$sql = "INSERT INTO tbg.ta_1_driver (nameDriver, ageDriver, licenseDriver, telDriver, dateDriver, timeDriver) VALUES (?, ?, ?, ?, ?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('nameDriver'),
					$data->__GET('ageDriver'),
					$data->__GET('licenseDriver'),
					$data->__GET('telDriver'),
					$data->__GET('dateDriver'),
					$data->__GET('timeDriver')
					)
				);
				$idDriver = $this->pdo->lastInsertId();
				return $idDriver;
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function DriverUpdateCode(Enti $data){
			try {
				$sql = "UPDATE ta_1_driver SET
							codeDriver = ?
						WHERE idDriver = ?";
				$res=$this->pdo->prepare($sql)->execute(array(
						$data->__GET('codeDriver'),
						$data->__GET('idDriver')
					)
				);
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function DriverUpdate(Enti $data){
			try {
				$sql = "UPDATE ta_1_driver SET
							nameDriver = ?,
							ageDriver = ?,
							licenseDriver = ?,
							telDriver = ?
						WHERE idDriver = ?";
				$res=$this->pdo->prepare($sql)->execute(array(
						$data->__GET('nameDriver'),
						$data->__GET('ageDriver'),
						$data->__GET('licenseDriver'),
						$data->__GET('telDriver'),
						$data->__GET('idDriver')
					)
				);
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function DriverInactive(Enti $data){
			try {
				$sql = "UPDATE ta_1_driver SET
							statusDriver = ?
						WHERE idDriver = ?";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('statusDriver'),
						$data->__GET('idDriver')
					)
				);
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}

		//////////// *** BackupUpdate  *** ////////////////

		function currentData($sql){
			try {
				$stmt = $this->pdo->prepare($sql);
				$stmt->execute();
				$currentData = $stmt->fetch(PDO::FETCH_ASSOC);  // Obtener los valores actuales
				return $currentData;
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}

		//////////// *** setttlementWithholdig  *** ////////////////

		public function SetttlementWithholdigAdd(Enti $data){
			//print_r($data);
			try {
				$sql = "INSERT INTO tbg.ta_7_settlement_withholding (settlementWithholdign, amountSettlementWithholdign, withholdignSettlementWithholdign, dateSettlementWithholdign, timeSettlementWithholdign) 
					VALUES ( ?, ?, ?, ?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('settlementWithholdign'),
					$data->__GET('amountSettlementWithholdign'),
					$data->__GET('withholdignSettlementWithholdign'),
					$data->__GET('dateSettlementWithholdign'),
					$data->__GET('timeSettlementWithholdign')
					)
				);
				$idBullet = $this->pdo->lastInsertId();
				return $idBullet;
			} catch (Exception $e) 
			{die($e->getMessage());}
			return $res;
		}
		public function SetttlementWithholdigList($idSettlement){
			try {
				$result=array();
				$stm = $this->pdo->prepare("SELECT
				  c7w.idWithholdingConcept,
				  c7w.nameWithholdingConcept,
				  t7w.amountWithholding,
				  t7sw.amountSettlementWithholdign,
				  t7w.balanceWithholding
				FROM tbg.ta_7_settlement_withholding AS t7sw
				LEFT JOIN tbg.ta_7_withholding AS t7w ON t7sw.withholdignSettlementWithholdign = t7w.idWithholding
				LEFT JOIN tbg.ca_7_withholdingconcept AS c7w ON t7w.withholdingConceptWithholding = c7w.idWithholdingConcept
				WHERE t7w.statusWithholding = 1
				AND t7sw.settlementWithholdign = ?");	
				$stm->execute(array($idSettlement));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r){
					$ent = new Enti();
					$ent->__SET('idWithholdingConcept', $r->idWithholdingConcept);
					$ent->__SET('nameWithholdingConcept', $r->nameWithholdingConcept);
					$ent->__SET('amountWithholding', $r->amountWithholding);
					$ent->__SET('amountSettlementWithholdign', $r->amountSettlementWithholdign);
					$ent->__SET('balanceWithholding', $r->balanceWithholding);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		//////////// *** generalConfig  *** ////////////////

		public function GeneralConfigData(){
			$result=array();
			try 
			{
				$stm = $this->pdo->prepare("SELECT
				  nameConfig,
				  valueConfig
				FROM tbg.ca_7_general_config
				WHERE	statusConfig = '1'");	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('nameConfig', $r->nameConfig);
					$ent->__SET('valueConfig', $r->valueConfig);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function GeneralConfigAdd(Enti $data){
			try {
				$sql = "INSERT INTO tbg.ca_7_general_config (idConfig, nameConfig, valueConfig, dateAddConfig) VALUES (?, ?, ?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('idConfig'),
					$data->__GET('nameConfig'),
					$data->__GET('valueConfig'),
					$data->__GET('dateAddConfig')
					)
				);
				$idConfig = $this->pdo->lastInsertId();
				return $idConfig;
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function GeneralConfigUpdate(Enti $data){
			try {
				$aNewData = array(
					'nameConfig' => $data->__GET('nameConfig'),
					'valueConfig' => $data->__GET('valueConfig'),
					'idConfig' => $data->__GET('idConfig')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ca_7_general_config','idConfig = '.$data->__GET('idConfig'), 'Configuración General');
				$sql = "UPDATE ca_7_general_config SET
							nameConfig = ?,
							valueConfig = ?
						WHERE idConfig = ?";
				$res=$this->pdo->prepare($sql)->execute($aNewValues);
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function GeneralConfigInactive(Enti $data){
			try {
				$aNewData = array(
					'statusConfig' => $data->__GET('statusConfig'),
					'idConfig' => $data->__GET('idConfig')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ca_7_general_config','idConfig = '.$data->__GET('idConfig'), 'Configuración General');
				$sql = "UPDATE ca_7_general_config SET
							statusConfig = ?
						WHERE idConfig = ?";
				$res = $this->pdo->prepare($sql)->execute($aNewValues);
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function GeneralConfigListU($idConfig){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_7_general_config WHERE idConfig = ? ");	
				$stm->execute(array($idConfig));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idConfig', $r->idConfig);
					$ent->__SET('nameConfig', $r->nameConfig);
					$ent->__SET('valueConfig', $r->valueConfig);
					$ent->__SET('statusConfig', $r->statusConfig);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		//////////// *** WithholdingConcept *** ////////////////

		public function WithholdingConceptAdd(Enti $data){
			try {
				$sql = "INSERT INTO tbg.ca_7_withholdingconcept (nameWithholdingConcept, amountWithholdingConcept, termWeekWithholdingConcept ) VALUES (?, ?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('nameWithholdingConcept'),
					$data->__GET('amountWithholdingConcept'),
					$data->__GET('termWeekWithholdingConcept')
					)
				);
				$idWithholdingConcept = $this->pdo->lastInsertId();
				return $idWithholdingConcept;
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function WithholdingConceptUpdate(Enti $data){
			try {
				$aNewData = array(
					'nameWithholdingConcept' => $data->__GET('nameWithholdingConcept'),
					'amountWithholdingConcept' => $data->__GET('amountWithholdingConcept'),
					'termWeekWithholdingConcept' => $data->__GET('termWeekWithholdingConcept'),
					'idWithholdingConcept' => $data->__GET('idWithholdingConcept')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ca_7_withholdingconcept','idWithholdingConcept = '.$data->__GET('idWithholdingConcept'), 'Retenciones');
				$sql = "UPDATE ca_7_withholdingconcept SET
							nameWithholdingConcept = ?,
							amountWithholdingConcept = ?,
							termWeekWithholdingConcept = ?
						WHERE idWithholdingConcept = ?";
				$res=$this->pdo->prepare($sql)->execute($aNewValues);
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function WithholdingConceptInactive(Enti $data){
			try {
				$aNewData = array(
					'statusWithholdingConcept' => $data->__GET('statusWithholdingConcept'),
					'idWithholdingConcept' => $data->__GET('idWithholdingConcept')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ca_7_withholdingconcept','idWithholdingConcept = '.$data->__GET('idWithholdingConcept'), 'Retenciones');
				$sql = "UPDATE ca_7_withholdingconcept SET
							statusWithholdingConcept = ?
						WHERE idWithholdingConcept = ?";
				$res = $this->pdo->prepare($sql)->execute($aNewValues);
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function WithholdingConceptListU($idWithholdingConcept){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_7_withholdingconcept WHERE idWithholdingConcept = ? ");	
				$stm->execute(array($idWithholdingConcept));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idWithholdingConcept', $r->idWithholdingConcept);
					$ent->__SET('nameWithholdingConcept', $r->nameWithholdingConcept);
					$ent->__SET('amountWithholdingConcept', $r->amountWithholdingConcept);
					$ent->__SET('termWeekWithholdingConcept', $r->termWeekWithholdingConcept);
					$ent->__SET('statusWithholdingConcept', $r->statusWithholdingConcept);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		//////////// *** Configbase *** ////////////////

		public function BaseAdd(Enti $data){
			try {
				$sql = "INSERT INTO tbg.ca_1_base (nameBase, prefixBase ) VALUES (?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('nameBase'),
					$data->__GET('prefixBase')
					)
				);
				$idBase = $this->pdo->lastInsertId();
				return $idBase;
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function BaseUpdate(Enti $data){
			try {
				$aNewData = array(
					'nameBase' => $data->__GET('nameBase'),
					'prefixBase' => $data->__GET('prefixBase'),
					'idBase' => $data->__GET('idBase')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ca_1_base','idBase = '.$data->__GET('idBase'), 'Base');
				$sql = "UPDATE ca_1_base SET
							nameBase = ?,
							prefixBase = ?
						WHERE idBase = ?";
				$res=$this->pdo->prepare($sql)->execute($aNewValues);
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function BaseInactive(Enti $data){
			try {
				$aNewData = array(
					'statusBase' => $data->__GET('statusBase'),
					'idBase' => $data->__GET('idBase')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ca_1_base','idBase = '.$data->__GET('idBase'), 'Base');
				$sql = "UPDATE ca_1_base SET
							statusBase = ?
						WHERE idBase = ?";
				$res = $this->pdo->prepare($sql)->execute($aNewValues);
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function BaseListU($idWithholdingConcept){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_1_base WHERE idBase = ? ");	
				$stm->execute(array($idWithholdingConcept));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idBase', $r->idBase);
					$ent->__SET('nameBase', $r->nameBase);
					$ent->__SET('prefixBase', $r->prefixBase);
					$ent->__SET('statusBase', $r->statusBase);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		//////////// *** ConceptTicketing *** ////////////////

		public function ConceptTicketingAdd(Enti $data){
			try {
				$sql = "INSERT INTO tbg.ca_7_concept (codeConcept, nameConcept, comissionConcept, valueConcept ) VALUES (?, ?, ?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('codeConcept'),
					$data->__GET('nameConcept'),
					$data->__GET('comissionConcept'),
					$data->__GET('valueConcept')
					)
				);
				$idConcept = $this->pdo->lastInsertId();
				return $idConcept;
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function ConceptTicketingUpdate(Enti $data){
			try {
				$aNewData = array(
					'codeConcept' => $data->__GET('codeConcept'),
					'nameConcept' => $data->__GET('nameConcept'),
					'comissionConcept' => $data->__GET('comissionConcept'),
					'valueConcept' => $data->__GET('valueConcept'),
					'idConcept' => $data->__GET('idConcept')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ca_7_concept','idConcept = '.$data->__GET('idConcept'), 'Boletos');
				$sql = "UPDATE ca_7_concept SET
							codeConcept = ?,
							nameConcept = ?,
							comissionConcept = ?,
							valueConcept = ?
						WHERE idConcept = ?";
				$res=$this->pdo->prepare($sql)->execute($aNewValues);
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function ConceptTicketingInactive(Enti $data){
			try {
				$aNewData = array(
					'statusConcept' => $data->__GET('statusConcept'),
					'idConcept' => $data->__GET('idConcept')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ca_7_concept','idConcept = '.$data->__GET('idConcept'), 'Boletos');
				$sql = "UPDATE ca_7_concept SET
							statusConcept = ?
						WHERE idConcept = ?";
				$res = $this->pdo->prepare($sql)->execute($aNewValues);
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
		public function ConceptTicketingListU($idWithholdingConcept){
			try 
			{
				$result=array();
				$stm = $this->pdo->prepare("SELECT * FROM tbg.ca_7_concept WHERE idConcept = ? ");	
				$stm->execute(array($idWithholdingConcept));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('idConcept', $r->idConcept);
					$ent->__SET('nameConcept', $r->nameConcept);
					$ent->__SET('codeConcept', $r->codeConcept);
					$ent->__SET('comissionConcept', $r->comissionConcept);
					$ent->__SET('valueConcept', $r->valueConcept);
					$ent->__SET('statusConcept', $r->statusConcept);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		//////////// *** Session *** ////////////////

		public function sessionUserEnd(Enti $data){
			try {
				$sql = "UPDATE ta_1_sessions SET
					dateEndSession = ?,
					statusSession = ?
				WHERE idUser = ?
				AND statusSession > 0";
				
				$aValues = array($data->__GET('dateEndSession'));
				$aValues[] = $data->__GET('statusSession');
				$aValues[] = $data->__GET('idUser');
				$res=$this->pdo->prepare($sql)->execute($aValues);
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}

		//////////// *** Kilometers *** ////////////////

		public function KilometersReport($aFiltros){
			try 
			{
				$result=array();
				$e_SQL = "SELECT
					t7u.busUnloading,
					CONCAT(t1b.codeBus,'->',t1b.economicBus) AS bus,
					t7u.employeeUnloading,
					t7e.namesEmployee,
					t7e.lastnameEmployee,
					t7e.mlastnameEmployee,
					t7u.serialUnloading,
					t7s.dateSettlement,
					t7s.routeSettlement,
					t1r.nameRoute,
					t7s.turnSettlement,
					t7s.KMSettlement,
					t7s.consumptionSettlement,
					t7s.dieselSettlement,
					t7s.dieselLSettlement
				FROM tbg.ta_7_settlement      AS t7s
				INNER JOIN tbg.ta_7_unloading AS t7u ON t7s.idUnloading       = t7u.idUnloading
				JOIN tbg.ta_1_bus             AS t1b ON t7u.busUnloading      = t1b.idBus
				JOIN tbg.ta_7_employee        AS t7e ON t7u.employeeUnloading = t7e.idEmployee
				JOIN tbg.ta_1_route           AS t1r ON t7s.routeSettlement   = t1r.idRoute
				WHERE 1 = 1";
				if(isset($aFiltros["startDateUnloading"]) && isset($aFiltros["endDateUnloading"])){
					$e_SQL .= " AND t7s.dateSettlement BETWEEN '{$aFiltros["startDateUnloading"]}' AND '{$aFiltros["endDateUnloading"]}'\n";
				}
				if(!empty($aFiltros["busUnloading"])){
					$e_SQL .= " AND t7u.busUnloading = '{$aFiltros["busUnloading"]}'\n";
				}
				if(!empty($aFiltros["routeSettlement"])){
					$e_SQL .= " AND t7s.routeSettlement = '{$aFiltros["routeSettlement"]}'\n";
				}
				if(!empty($aFiltros["employeeUnloading"])){
					$e_SQL .= " AND t7u.employeeUnloading = '{$aFiltros["employeeUnloading"]}'\n";
				}
				$stm = $this->pdo->prepare($e_SQL);	
				$stm->execute();
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
				{
					$ent = new Enti();
					$ent->__SET('busUnloading',$r->busUnloading);
					$ent->__SET('bus',$r->bus);
					$ent->__SET('employeeUnloading',$r->employeeUnloading);
					$ent->__SET('namesEmployee',$r->namesEmployee);
					$ent->__SET('lastnameEmployee',$r->lastnameEmployee);
					$ent->__SET('mlastnameEmployee',$r->mlastnameEmployee);
					$ent->__SET('serialUnloading',$r->serialUnloading);
					$ent->__SET('dateSettlement',$r->dateSettlement);
					$ent->__SET('routeSettlement',$r->routeSettlement);
					$ent->__SET('nameRoute',$r->nameRoute);
					$ent->__SET('turnSettlement',$r->turnSettlement);
					$ent->__SET('KMSettlement',$r->KMSettlement);
					$ent->__SET('consumptionSettlement',$r->consumptionSettlement);
					$ent->__SET('dieselSettlement',$r->dieselSettlement);
					$ent->__SET('dieselLSettlement',$r->dieselLSettlement);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}

		//////////// *** VacationEmployee *** ////////////////

		public function EmployeeVacationData($idE){
			try {
				$result=array();
				/*
				$e_SQL = "SELECT
					idEmployee,
					dateadmissionEmployee,
					(SELECT SUM(numDayVacationEmployee) FROM tbg.ta_7_vacation_employee WHERE idEmployeeVacation = idEmployee AND statusVacationEmployee = '1') AS dayUsed
				FROM tbg.ta_7_employee AS t7e
				WHERE idEmployee = ?";
				*/
				$e_SQL = "SELECT
				    idEmployee,
				    dateadmissionEmployee,
				    (SELECT SUM(numDayVacationEmployee)
				     FROM tbg.ta_7_vacation_employee
				     WHERE idEmployeeVacation = idEmployee
				       AND statusVacationEmployee = '1'
				       AND dateEndVacationEmployee BETWEEN 
				            CONCAT(
				               IF(
				                   CONCAT(YEAR(CURDATE()), '-', LPAD(MONTH(dateadmissionEmployee), 2, '0'), '-', LPAD(DAY(dateadmissionEmployee), 2, '0')) >= CURDATE(), 
				                   YEAR(CURDATE()) -1, YEAR(CURDATE())
				               ),
				               '-',
				               LPAD(MONTH(dateadmissionEmployee), 2, '0'),
				               '-',
				               LPAD(DAY(dateadmissionEmployee), 2, '0')
				           )
				           AND CONCAT(
				               IF(
				                   CONCAT(YEAR(CURDATE()), '-', LPAD(MONTH(dateadmissionEmployee), 2, '0'), '-', LPAD(DAY(dateadmissionEmployee), 2, '0')) >= CURDATE(), 
				                   YEAR(CURDATE()), YEAR(CURDATE())+1
				               ),
				               '-',
				               LPAD(MONTH(dateadmissionEmployee), 2, '0'),
				               '-',
				               LPAD(DAY(dateadmissionEmployee), 2, '0')
				           )
				    ) AS dayUsed
				FROM tbg.ta_7_employee AS t7e
				WHERE idEmployee = ?";
				$stm = $this->pdo->prepare($e_SQL);	
				$stm->execute(array($idE));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r){
					$ent = new Enti();
					$ent->__SET('idEmployee', $r->idEmployee);
					$ent->__SET('dateadmissionEmployee', $r->dateadmissionEmployee);
					$ent->__SET('dayUsed', $r->dayUsed);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function VacationLaw($year){
			try {
				$result=array();
				$stm = $this->pdo->prepare("SELECT dayVacation
				FROM tbg.ca_7_vacation_law
				WHERE statusVacation = '1'
				AND yearVacation = ?");	
				$stm->execute(array($year));
				foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r){
					$ent = new Enti();
					$ent->__SET('dayVacation', $r->dayVacation);
					$result[] = $ent;
				}
				return $result;
			} catch (Exception $e)
			{die($e->getMessage());}
		}
		public function VacationAdd(Enti $data){
			try {
				date_default_timezone_set('America/Mexico_City');
				$sql = "INSERT INTO tbg.ta_7_vacation_employee (idEmployeeVacation, dateStartVacationEmployee, dateEndVacationEmployee, numDayVacationEmployee, userAddVacationEmployee, dateAddVacationEmployee) VALUES (?, ?, ?, ?, ?, ?)";
				$res=$this->pdo->prepare($sql)->execute(array(
					$data->__GET('idEmployeeVacation'),
					$data->__GET('dateStartVacationEmployee'),
					$data->__GET('dateEndVacationEmployee'),
					$data->__GET('numDayVacationEmployee'),
					$_SESSION["SidUser"],
					date('Y-m-d H:i:s')
					)
				);
				$idMenu = $this->pdo->lastInsertId();
				return $idMenu;
			} catch (Exception $e) 
			{
				die($e->getMessage());
			}
			return $res;
		}
		public function VacationInactive(Enti $data){
			try {
				$aNewData = array(
					'statusVacationEmployee' => $data->__GET('statusVacationEmployee'),
					'idVacationEmployee' => $data->__GET('idVacationEmployee')
				);
				$aNewValues = array_values($aNewData);
				$this->backupUpdate($aNewData,'ta_7_vacation_employee','idVacationEmployee = '.$data->__GET('idVacationEmployee') , 'Vacaciones');
				$sql = "UPDATE ta_7_vacation_employee SET
							statusVacationEmployee = ?
						WHERE idVacationEmployee = ?";
				$res=$this->pdo->prepare($sql)->execute($aNewValues);
			} catch (Exception $e) {
				die($e->getMessage());
			}
			return $res;
		}
    }
?>