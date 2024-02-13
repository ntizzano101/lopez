<?php
class Cierre_de_caja_model extends CI_Model {
		
		public $id;
		public $fecha_apertura;
		public $fecha_cierre;
		public $monto_inicial;
		public $monto_final;
		public $usuario_apertura_id;
		public $usuario_cierre_id;
		public $observaciones;
		public $arqueo;
		public $cheques;
	
		
       

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }
		public function buscar($id){				
				
				 $r= $this->db->get_where('cierre_caja',array('id'=>$id));
          $rta=$r->result();
          return $rta[0];
	}
		public function agregar($item){				
				$this->db->insert('cierre_caja',$item);			
	}
	public function actualizar($item,$continuar=0){
			    if($continuar!=null){
							$sql="update cierre_caja set observaciones=?,cheques=?,monto_inicial=?,monto_final=? where id=?";
							$query=$this->db->query($sql,array($item->observaciones,$item->cheques,$item->monto_inicial,$item->monto_final,$item->id));
				}
				else
						$this->db->replace('cierre_caja',$item);			
	}
	 public function verificar(){
		/*
		Verificar hay un cierre de caja sin cerrar..
		si la caja esta habierta con fecha de hoy esta  estado 1.
		si la caja esta habierta con otra fecha entonces obligar a cerrarla 2.
		si no hay caja o esta cerrada o no hay entonces hay que abriarla 3.
		Estado 1: Sigue normal.
		Estado 2: Pantalla Para Cerrar Caja. y pasar a estado 3.
		Estado 3: Pantalla Para Abrir la Caja. 
		*/
		//Estado1.
		$id=0;
		$sql="select * from cierre_caja order by id DESC limit 1";
		$query=$this->db->query($sql);
		$query=$query->result();
	    if(count($query)>0){
			//hay in cierre
			$rta=$query[0];
			if($rta->fecha_cierre=="" or $rta->fecha_cierre=="0000-00-00"){
				//no hay cierre
				//controlar la fecha
				if($rta->fecha_apertura==date('Y-m-d')){
					//todo OK!! sigue
					$estado=1;
					$id=$rta->id;		
				}
				else{ 
				//no es fecha de hoy debe cerrar la caja
					$estado=2;
				    $id=$rta->id;
				}
			}
			else
				$estado=3;
		
		}
		else 
			$estado=3;
	   $rta=array("estado"=>$estado,"id"=>$id);	
	   return $rta; 
	 }
	public function listar(){
		$sql="select *,date_format(fecha_apertura,'%d/%m/%Y') as fecha_aperturaM,
		case when fecha_cierre='' or fecha_cierre='0000-00-00' then '' else
		date_format(fecha_cierre,'%d/%m/%Y') end  as fecha_cierreM from cierre_caja order by id DESC";
		$query=$this->db->query($sql);
		$query=$query->result();
	     return $query;
	}
}
