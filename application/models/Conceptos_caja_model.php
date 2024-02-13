<?php
class Conceptos_caja_model extends CI_Model {

       

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

         public function listado()
        {
				$query=$this->db->get_where('conceptos_caja');
				return $query->result();
        }
		public function buscar($p)
        {
				$query=$this->db->get_where('conceptos_caja',array("nombre like"=>$p.'%'));
				//echo $this->db->queries[0];die;
				return $query->result();
        }
        public function editar($p)
        {
				$query=$this->db->get_where('conceptos_caja',array("id"=>$p));
				//echo $this->db->queries[0];die;
				return $query->result();
        }
         public function guardar($p)
        {
				$object=$p["concepto"];
				
				if($object->id > 0){
					$this->db->where('id',$object->id);
					$this->db->update('conceptos_caja', $object);
				    //var_dump($object);die;
				}
				else{
					$query=$this->db->insert('conceptos_caja', $object);
						
				}	
			return true;
        }
      public function eliminar($p)
        {
				$query=$this->db->get_where('caja_movimiento',array("concepto_id"=>$p));
				$a=$query->result();
				if(count($a)==0){
					$this->db->where('id', $p);
					$this->db->delete('conceptos_caja');
					return "OK";
				}
				else
				{
					return "NO";
				}
        }
}
?>
