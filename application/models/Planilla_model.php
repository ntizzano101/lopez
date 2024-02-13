<?php
class Planilla_model extends CI_Model {

       public $id;
       public $idc;
       public $idp;
       public $fecha;
       public $catres;
       public $sumares;
       public $preciov;
       public $precioc;
       public $kilos;
       public $totalv;
       public $totalc;
       public $obs;
       public $suma;
       public $descuento;

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

         public function listado($f1='1990-01-01',$f2='2050-01-01',$cli='%',$prov='%')
        {
				$aux=array($f1,$f2,$cli,$prov);
				foreach($aux as $o)
						if (strlen($o) > 12 )
							 die("Error en los parametros Pasados en la consulta");
				if($cli=="")
					$cli="%";
				else
					$cli=strtoupper($cli) . '%';	
				if($prov=="")
					$prov="%";
				else
					$prov=strtoupper($prov) . '%';	
				$sql="select T1.*,T2.cobrado,T3.pagado from 
				(SELECT pedido.id,pedido.fecha,date_format(fecha,'%d/%m/%Y') as fecham,clie.nombre as cli,pedido.kilos,
				prove.nombre as prov,pedido.totalc,pedido.totalv,pedido.totalv - pedido.totalc as ganancia ,pedido.catres
				,pedido.preciov,pedido.precioc
				from pedido inner join clie on clie.id = pedido.idc inner join prove on prove.id=pedido.idp ) as T1 
				left join (select cc_pago_det.idpedido,sum(monto) as cobrado from cc_pago_det where tipo='C' group by idpedido) as T2 on T1.id=T2.idpedido 
				left join (select cc_pago_det.idpedido,sum(monto) as pagado from cc_pago_det where tipo='P' group by idpedido) as T3 on T1.id=T3.idpedido
				where T1.fecha between ? and ? and upper(T1.cli) like ? and upper(T1.prov) like ?
				order by T1.fecha desc ";
				/*"
				$sql="SELECT pedido.*,date_format(fecha,'%d/%m/%Y') as fecham,clie.nombre as cli,prove.nombre as prov,pedido.totalc,pedido.totalv,pedido.totalv - pedido.totalc as ganancia from pedido inner join clie on clie.id = pedido.idc inner join prove on prove.id=pedido.idp 
				where fecha between ? and ? and upper(clie.nombre) like ? and upper(prove.nombre) like ?
				order by pedido.id desc ";
				*/
				$query = $this->db->query($sql,array($f1,$f2,$cli,$prov));
				// echo $this->db->queries[0];die;
				
					
				return $query->result();
        }
		public function buscar($p)
        {
				
				$this->db->select('*');
				$this->db->order_by("nombre","asc");
				$this->db->from('clie');
				//Si sacas el inner JOIN para mostrar alumnos sin cursos, habilita decomenta el foreach en alumnos/listado()
				$this->db->where(array("nombre like"=>$p.'%'));
				$query=$this->db->get();
				return $query->result();
				
        }
        public function editar($p)
        {
				
				$this->db->select("*,date_format(pedido.fecha,'%d/%m/%Y') as fecham");
				$this->db->from('pedido');
				$this->db->where(array("id"=>$p));
				$query=$this->db->get();
				return $query->result();
				
        }
         public function guardar($p)
        {
				$object=$p;
				$id=$object->id;
				$this->session->set_userdata("fecha",$object->fecha);
				if($object->id > 0){
					$this->db->where('id',$object->id);
					$this->db->update('pedido', $object);
				    var_dump($object);die;

					}
				else{
					$query=$this->db->insert('pedido', $object);
					$id=$this->db->insert_id();			
				}	
				
			return $id;
        }
          public function eliminar($p)
        {
				
				//verificar si esta en pagos
				$query=$this->db->get_where('cc_pago_det',array("idpedido"=>$p),1);
				$b=$query->result();
				//echo $this->db->queries[0];die;
				//verificar si esta en pedido
				$query=$this->db->get_where('pedido',array("id"=>$p),1);
				$c=$query->result();
				return array("cc_pago"=>$b,"pedido"=>$c);
        }
         public function delete($p)
        {
				$this->db->where('id', $p);
				$this->db->delete("pedido");
        }
                  public function cmbProveedores($p)
        {
		    
		   $sql="select * from proveedores where nombre like '%?%' Order by nombre";
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
