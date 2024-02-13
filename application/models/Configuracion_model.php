<?php
class Configuracion_model extends CI_Model {
		
		public $id;
		public $codigo;
		public $descripcion;
		public $valor;
		

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

        public function valor($p){
				$query=$this->db->get_where('configuracion',array("codigo like"=>$p));
				$rta=$query->result();
				if(count($rta)==1)
					return $rta[0]->valor;
				else
					die('No Se Encuentra El Parametro:'.$p);
		}		 	 
}
?>
