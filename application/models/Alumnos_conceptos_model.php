<?php
class Alumnos_conceptos_model extends CI_Model {
		
		public $id;
		public $id_concepto;
		public $desde;
		public $hasta;
		public $id_alumno;
       

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

        public function guardar($item){
					if($item->id > 0 ){
								$this->db->where('id',$item->id);
								$this->db->update('alumnos_conceptos', $item);
							
					}
					else{
							$this->db->insert('alumnos_conceptos',$item);
						
					}		
			
			}
		
		 public function eliminar($id){
					$this->db->where('id', $id);
					$this->db->delete('alumnos_conceptos');
		
		}			
			 	 
}
?>
