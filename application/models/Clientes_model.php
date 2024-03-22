<?php
class Clientes_model extends CI_Model {

       

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

         public function listado()
        {
			return $this->buscar('','');
        }
		public function buscar($p,$p1)
        {
			$consaldo="";
			if($p1<>'')
				$consaldo=" HAVING SUM(tot) < 0 ";
			$sql="select T1.nombre,T1.id,T1.baja,SUM(tot) as tot from(
select clie.nombre,clie.id,clie.baja,sum(pedido.totalv * -1) as tot from clie left join pedido on clie.id=pedido.idc group by  clie.nombre,clie.id,clie.baja
UNION
select clie.nombre,clie.id,clie.baja,sum(cc_pago.monto) from clie left join cc_pago on clie.id=cc_pago.idcp and cc_pago.tipo='C' group by  clie.nombre,clie.id )
as  T1 where T1.nombre like ? and T1.baja=1  group by T1.nombre,T1.baja,T1.id ".$consaldo;	
		    $query = $this->db->query($sql,array($p.'%'));
			// echo $this->db->queries[0];die;
		   return $query->result();	
				
        }
        public function editar($p)
        {
				$query=$this->db->get_where('clie',array("id"=>$p));
				//echo $this->db->queries[0];die;
				return $query->result();
        }
         public function guardar($p)
        {
				$object=$p["cliente"];
				$x=trim(strtoupper($object->nombre));
				$query=$this->db->get_where('clie',array("upper(nombre)"=>$x));
				$b=$query->result();
				if(count($b)>0){
					return false;
				}
				if($object->id > 0){
					$this->db->where('id',$object->id);
					$this->db->update('clie', $object);
				    //var_dump($object);die;
				}
				else{
					$query=$this->db->insert('clie', $object);
						
				}	
			return true;
        }
          public function eliminar($p)
        {
				
				//verificar si esta en pagos
				$query=$this->db->get_where('cc_pago',array("idcp"=>$p,"tipo"=>'C'),1);
				$b=$query->result();
				//echo $this->db->queries[0];die;
				//verificar si esta en pedido
				$query=$this->db->get_where('pedido',array("idc"=>$p),1);
				$c=$query->result();
				//verificar si esta en clientes
				$query=$this->db->get_where('clie',array("id"=>$p),1);
				$d=$query->result();
				return array("cc_pago"=>$b,"pedido"=>$c,"clie"=>$d);
        }
         public function delete($p)
        {
				//$this->db->where('id', $p);
				//$this->db->delete("clie");
                 $sql="update clie set baja=10 where id=?";
                 $query = $this->db->query($sql,$p);
                 var_dump($query);
                 die;
                 $d=$query->result();
        }
            public function cmbClientes($p)
        {
		    
		   $sql="select * from clie where nombre like ? and baja=1 Order by nombre";
		   $query = $this->db->query($sql,array($p));
		  // echo $this->db->queries[0];die;
		   $rta=$query->result_array();
		   $t=array();
		   foreach($rta as $r){
					$t[$r["id"]]=$r["nombre"];
			   }
		   return $t;	   
        }
}
?>
