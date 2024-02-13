<?php
class Movimientos_caja_model extends CI_Model {
		//caja_movimiento
		/**/
		public $id;
		public $fecha;
		public $monto;
		public $concepto_id;
		public $descripcion;
		public $cierre_caja_id;
		public $usuario_id;
		public $tipo;
	
		
       

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }
		
		public function agregar($item){
				$this->db->insert('caja_movimiento',$item);
	}	
		 public function eliminar($id){
					$this->db->where('id', $id);
					$this->db->delete('caja_movimiento');
		
		}
public function listar($d,$h){
					$sql="
					SELECT T1.* from (		
					SELECT cm.id, cm.monto, cm.descripcion, cm.tipo, date_format( cm.fecha, '%d/%m/%Y' ) AS fechaM, ifnull( cc.nombre, 'Sin Concepto' ) AS concepto,cm.fecha as fecha
					FROM caja_movimiento cm
					LEFT JOIN conceptos_caja cc ON cm.concepto_id = cc.id
					LEFT JOIN usuario u ON cm.usuario_id = u.id
					UNION SELECT cpm.id, cpm.importe, ccp.comprobante,
					CASE ccp.tipo_comp
					WHEN '11'
					THEN 'D'
					WHEN '13'
					THEN 'H'
					END , date_format( cpm.fecha_acreditacion, '%d/%m/%Y' ) , 'Cobro Cuota',cpm.fecha_acreditacion
					FROM cc_pago_medios cpm
					INNER JOIN cc_pago ccp ON cpm.id_pago = ccp.id) as T1 where T1.fecha between ? and ? order by fecha,id
					";
					//die($sql);
					$query=$this->db->query($sql,array($d,$h));
					return $query->result();
	 
	 } 
		
public function listar_una_caja($id){
					$sql="
					SELECT T1. * FROM 
					(
						SELECT cm.id, cm.monto,'' as nombreA, cm.descripcion, cm.tipo, date_format( cm.fecha, '%d/%m/%Y' ) AS fechaM, ifnull( cc.nombre, 'Sin Concepto' ) AS concepto, cm.fecha AS fecha
						FROM caja_movimiento cm
						LEFT JOIN conceptos_caja cc ON cm.concepto_id = cc.id
						LEFT JOIN usuario u ON cm.usuario_id = u.id
						WHERE cm.cierre_caja_id =?
						UNION SELECT cpm.id, cpm.importe,concat(a.apellido,',',a.nombre),ccp.comprobante,
						CASE ccp.tipo_comp
						WHEN '11'
						THEN 'D'
						WHEN '13'
						THEN 'H'
						END , date_format( cpm.fecha_acreditacion, '%d/%m/%Y' ) , 'Cobro Cuota', cpm.fecha_acreditacion
						FROM cc_pago_medios cpm
						INNER JOIN cc_pago ccp ON cpm.id_pago = ccp.id
						INNER JOIN alumnos a on a.id=ccp.id_alumno
						WHERE cpm.id_cierre =?
					) 
						AS T1
						ORDER BY nombreA,descripcion,fecha, id
					";
					//die($sql);
					$query=$this->db->query($sql,array($id,$id));
					$rta["movimientos"]=$query->result();
					$sql="select *,date_format(cierre_caja.fecha_apertura, '%d/%m/%Y' ) AS fecha_aperturaM,
					case when fecha_cierre='' or fecha_cierre='0000-00-00' then '' else
		date_format(fecha_cierre,'%d/%m/%Y') end  as fecha_cierreM  from cierre_caja where id=?";
					$query=$this->db->query($sql,array($id));
					$rta["cierre"]=$query->result();
			return $rta;
	 } 
public function borrar($id)
		{
					//tenemos que ver si el item pertenece a una caja abierta sino nos se puede borrar
		$sql="select * from cierre_caja order by id DESC limit 1";
		$query=$this->db->query($sql);
		$query=$query->result();
		$caja=$query[0];
		$sql="select * from caja_movimiento where id=?";
		$query1=$this->db->query($sql,array($id));
		$query1=$query1->result();
		$mov=$query1[0];
		if($caja->id==$mov->cierre_caja_id)
		{
			$this->db->where("id=".$id);
			$this->db->delete('caja_movimiento');
			return true;	
		}
		else
			return false;	
	}
	 public function conceptos()
        {
		    $sql="select * from conceptos_caja order by nombre ";
		   $query = $this->db->query($sql);
		   //echo $this->db->queries[0];die;
		   $rta=$query->result_array();
		   $t=array();
		   foreach($rta as $r){
					$t[$r["id"]]=$r["nombre"];
			   }
		   return $t;	   
        }
}
