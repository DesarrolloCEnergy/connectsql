<?php
    class Enti
    {
		public $idUser;
		public $nameUser;
		public $passwordUser;
		public $emailUser;
		public $phoneUser;
		public $companyUser;
		public $profileUser;
		public $statusUser;
		public $dateUser;
		public $timeUser;
		public $positionUser;
		public $fullNameUser;
		public $userCountPosition;

		public $idEmployee;
		public $codeEmployee;
		public $namesEmployee;
		public $lastnamesEmployee;
		public $mlastnamesEmployee;
		public $companyEmployee;
		public $baseEmployee;
		public $nssEmployee;
		public $rfcEmployee;
		public $curpEmployee;
		public $departamentEmployee;
		public $positionEmployee;
		public $dateadmissionEmployee;
		public $phoneEmployee;
		public $emailEmployee;
		public $countryEmployee;
		public $stateEmployee;
		public $municipalityEmployee;
		public $colonyEmployee;
		public $streeEmployee;
		public $cpEmployee;
		public $birthdaydateEmployee;
		public $ageEmployee;
		public $genderEmployee;
		public $maritalEmployee;
		public $nationalityEmployee;
		public $statebirthEmployee;
		public $municipalitybirthEmployee;
		public $licenseDriverEmployee;
		public $companyemailEmployee;
		public $bankEmployee;
		public $accountEmployee;
		public $interbankcodeEmployee;
		public $beneficiaryEmployee;
		public $beneficiaryrelationshipEmployee;
		public $benefeciaryrfcEmployee;
		public $beneficiarycountryEmployee;
		public $beneficiarystateEmployee;
		public $beneficiarymunicipalityEmployee;
		public $beneficiarycolonyEmployee;
		public $beneficiarystreeEmployee;
		public $beneficiarycpEmployee;
		public $ifinfonavitcreditEmployee;
		public $creditnumberinfonavitEmployee;
		public $discounttypeInfoEmployee;
		public $discountfactorEmployee;
		public $iffonacotcreditEmployee;
		public $creditnumberfonacotEmployee;
		public $monthlydiscountEmployee;
		public $grossmonthlysalaryEmployee;
		public $socialpreventionEmployee;
		public $indasaEmployee;
		public $bonusdaysEployee;
		public $holidaysEmployee;
		public $vacationbonusporEmployee;
		public $imgEmployee;
		public $statusEmployee;
		public $dateEmployee;
		public $timeEmployee;
		public $nameProfile;

        public function __GET($k){ return $this->$k; }
	    public function __SET($k, $v){ return $this->$k = ($v); }
    }
?>

