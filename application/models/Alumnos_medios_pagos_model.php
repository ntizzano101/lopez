<?php
class Alumnos_medios_pagos_model extends CI_Model {
		
		public $id;
		public $id_pago;
		public $fecha_acreditacion;
		public $tipo_pago;
		public $cuenta;
		public $banco;
		public $nro_cheque;
		public $nro_deposito;
		public $fecha_vencimiento;
		public $cupon_tarjeta;
		public $importe;
		public $id_cierre;
		
       

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }
		
		public function agregar($item){
				
				if($item->id_cierre=='' or $item->id_cierre==0){
					//hay que poner el ultimo 
				    $sql="select * from cierre_caja order by id DESC limit 1";
					$query=$this->db->query($sql);
					$query=$query->result();
					$rta=$query[0];
					$item->id_cierre=$rta->id;	
					 //Hay que poner el Id_cierre 
				}
				
				$this->db->insert('cc_pago_factura',$item);
			    
			
			
	}
	public function cc_pago($id){
				$query=$this->db->select('cc_pago',array("id"=>$id));
				$rta=$query->result();
				return $rta[0];
		
	}
	public function alumno($id_pago){
				$sql="select alumnos.*,cc_pago.comprobante,cc_pago.importe from alumnos inner join cc_pago on alumnos.id=cc_pago.id_alumno where cc_pago.id=?";
				$query=$this->db->query($sql,array($id_pago));
				$rta=$query->result();
				return $rta[0];
	}
	public function alumno_curso($id_pago){
				$sql="select alumnos.*,cursos.curso,cc_pago.comprobante,cc_pago.importe,
				date_format(cc_pago.fecha,'%d/%m/%Y') as fecha,cc_pago.cae,cc_pago.vto,cc_pago.barranro,cc_pago.barraimagen,
				cc_pago.tipo_comp,cc_pago.id_anula,ccp.comprobante as NC
				 from alumnos inner join cc_pago on alumnos.id=cc_pago.id_alumno 
				 left join cursos on cursos.id_curso=alumnos.id_curso
				 LEFT JOIN cc_pago ccp on ccp.id=cc_pago.id_anula
				 where cc_pago.id=?";
				$query=$this->db->query($sql,array($id_pago));
				$rta=$query->result();
				return $rta[0];
	}
	public function recibo_items($id_pago){
				$sql="select cc_pago_factura.*,
				conceptos.nombre as descripcion,cc_pago_factura.importe,alumnos_cc.periodo,alumnos_cc.original
				from cc_pago_factura inner join alumnos_cc on cc_pago_factura.id_alumno_cc=alumnos_cc.id
				inner join conceptos on   conceptos.id=alumnos_cc.id_concepto
				where cc_pago_factura.id_pago=?";
				$query=$this->db->query($sql,array($id_pago));
				$rta=$query->result();
				return $rta;
	}
	public function pagos_ingresados($id_pago){
		//nos dice cuando se ha ingresado de un pago..tenemos que cancelar el total
				$this->db->select('CASE WHEN SUM(importe) is NULL then 0.00 else SUM(importe) END as importe');
				$this->db->from('cc_pago_medios');
				$this->db->where('id_pago='.$id_pago);
				$query = $this->db->get();
				$rta=$query->result();	
				return $rta[0];
		}
	public function guardar($item){
				
				$sql="select * from cierre_caja order by id DESC limit 1";
				$query=$this->db->query($sql);
				$query=$query->result();
				$rta=$query[0];
				$item->id_cierre=$rta->id;	
				$this->db->insert('cc_pago_medios',$item);
				return $this->alumnos_medios_pagos_model->pagos_ingresados($item->id_pago);
	}
	public function ver_medios($item){
				//$item==$idPago
				//Datos del Alumno y Recibo
				$alumnoyrecibo=$this->alumnos_medios_pagos_model->alumno($item);		
				//Datos de los pagos
				$query=$this->db->get_where('cc_pago_medios',array("id_pago"=>$item));
				$pagos=$query->result();
				$datos=array('alumnoyrecibo'=>$alumnoyrecibo,'pagos'=>$pagos,'id_pago'=>$item);
				return $datos;
	}
}
