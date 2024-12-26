<?php
    class Sesion
    {
	    public $idUser;
		public $nameUser;
		public $fullNameUser;
		public $passwordUser;
		public $emailUser;
		public $phoneUser;
		public $companyUser;
		public $nameCompany;
		public $profileUser;
		public $statusUser;
		public $dateUser;
		public $timeUser;
		public $idSession;
		public $dateStartSession;
		public $datEndSession;
		public $statusSession;
		public $dateEndSession;
		public $sesionUser;
		public $session;
		public $typeGet;
		public $idUSer;
		public $idProfile;
		public $nameProfile;
		public $credentialProfile;
		public $wUser;

        public function __GET($k){ return $this->$k; }
	    public function __SET($k, $v){ return $this->$k = ($v); }
    }
?>