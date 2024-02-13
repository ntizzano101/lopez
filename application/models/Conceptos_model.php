<?php
class Conceptos_model extends CI_Model {

       

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

         public function listado()
        {
				$query=$this->db->get_where('conceptos');
				return $query->result();
        }
		public function buscar($p)
        {
				$query=$this->db->get_where('conceptos',array("nombre like"=>$p.'%'));
				//echo $this->db->queries[0];die;
				return $query->result();
        }
        public function editar($p)
        {
				$query=$this->db->get_where('conceptos',array("id"=>$p));
				//echo $this->db->queries[0];die;
				return $query->result();
        }
         public function guardar($p)
        {
				$object=$p["concepto"];
				
				if($object->id > 0){
					$this->db->where('id',$object->id);
					$this->db->update('conceptos', $object);
				    //var_dump($object);die;
				}
				else{
					$query=$this->db->insert('conceptos', $object);
						
				}	
			return true;
        }
       public function listarconceptosalumno($p){
		   $sql="select alumnos.id,alumnos.nombre , alumnos.apellido, cursos.curso , count(conceptos.id) as total from alumnos 
		   LEFT JOIN cursos on alumnos.id_curso=cursos.id_curso
		   LEFT join alumnos_conceptos on alumnos.id=alumnos_conceptos.id_alumno LEFT join conceptos on conceptos.id=alumnos_conceptos.id_concepto where apellido like ? group by alumnos.apellido,alumnos.nombre ";
		   $query = $this->db->query($sql,array($p.'%'));
		   //echo $this->db->queries[0];die;
		   return $query->result();
		} 
		     public function conceptos($p)
        {
			$sql="select alumnos_conceptos.id,DATE_FORMAT(alumnos_conceptos.desde,'%d/%m/%Y') as desde,DATE_FORMAT(alumnos_conceptos.hasta,'%d/%m/%Y') as hasta,conceptos.nombre as concepto,conceptos.monto from conceptos INNER JOIN  alumnos_conceptos on conceptos.id=alumnos_conceptos.id_concepto  where id_alumno = ? order by conceptos.nombre ";
		   $query = $this->db->query($sql,array($p.'%'));
		   //echo $this->db->queries[0];die;
		   return $query->result();
        }
             public function conceptosDisponibles($p)
        {
		    $sql="select conceptos.id,conceptos.nombre from conceptos inner join  alumnos_conceptos on conceptos.id= alumnos_conceptos.id_concepto where id_alumno = ?   order by conceptos.nombre ";
		   $query = $this->db->query($sql,array($p));
		   //echo $this->db->queries[0];die;
		   $rta=$query->result_array();
		   $t=array();
		   foreach($rta as $r){
					$t[$r["id"]]=$r["nombre"];
			   }
		   return $t;	   
        }
            public function conceptosParaAgregar($p)
        {
		    $sql="select conceptos.id,conceptos.nombre from conceptos left join  alumnos_conceptos on conceptos.id= alumnos_conceptos.id_concepto and id_alumno = ?  
		    where 
		     alumnos_conceptos.id_concepto IS NULL 
		      order by conceptos.nombre ";
		   $query = $this->db->query($sql,array($p));
		   //echo $this->db->queries[0];die;
		   $rta=$query->result_array();
		   $t=array();
		   foreach($rta as $r){
					$t[$r["id"]]=$r["nombre"];
			   }
		   return $t;	   
        }
        
        
         public function alumno($p)
        {
				$query=$this->db->get_where('alumnos',array("id"=>$p));
				 
				return $query->result();
        }
         public function importe($p)
        {
				$query=$this->db->get_where('conceptos',array("id"=>$p));
				 
				return $query->result();
        }
}
?>
