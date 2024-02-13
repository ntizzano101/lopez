<?php
class Proveedores_model extends CI_Model {

       

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

         public function listado()
        {
		
				return $this->buscar('');
        }
		public function buscar($p)
        {
				
		$sql="select T1.nombre,T1.id,SUM(tot) as tot from(
select prove.nombre,prove.id,sum(pedido.totalc * -1) as tot from prove left join pedido on prove.id=pedido.idp group by  prove.nombre,prove.id
UNION
select prove.nombre,prove.id,sum(cc_pago.monto) from prove left join cc_pago on prove.id=cc_pago.idcp and cc_pago.tipo='P' group by  prove.nombre,prove.id )
as  T1 where T1.nombre like ? group by T1.nombre,T1.id";	
		    $query = $this->db->query($sql,array($p.'%'));
			// echo $this->db->queries[0];die;
		   return $query->result();	
				
        }
        public function editar($p)
        {
				$query=$this->db->get_where('prove',array("id"=>$p));
				//echo $this->db->queries[0];die;
				return $query->result();
        }
         public function eliminar($p)
        {
				
				//verificar si esta en pagos
				$query=$this->db->get_where('cc_pago',array("idcp"=>$p,"tipo"=>'P'),1);
				$b=$query->result();
				//echo $this->db->queries[0];die;
				//verificar si esta en pedido
				$query=$this->db->get_where('pedido',array("idp"=>$p),1);
				$c=$query->result();
				//verificar si esta en proveedor 
				$query=$this->db->get_where('prove',array("id"=>$p),1);
				$d=$query->result();
				return array("cc_pago"=>$b,"pedido"=>$c,"prove"=>$d);
        }
         public function guardar($p)
        {
				$object=$p["proveedor"];
				
				if($object->id > 0){
					$this->db->where('id',$object->id);
					$this->db->update('prove', $object);
				    //var_dump($object);die;
				}
				else{
					$query=$this->db->insert('prove', $object);
						
				}	
			return true;
        }
         public function delete($p)
        {
				$this->db->where('id', $p);
				$this->db->delete("prove");
        }
            public function cmbProveedores($p)
        {
		    
		   $sql="select * from prove where nombre like ? Order by nombre";
		   $query = $this->db->query($sql,array($p));
		   //echo $this->db->queries[0];die;
		   $rta=$query->result_array();
		   $t=array();
		   foreach($rta as $r){
					$t[$r["id"]]=$r["nombre"];
			   }
		   return $t;	   
        }
}
?>
