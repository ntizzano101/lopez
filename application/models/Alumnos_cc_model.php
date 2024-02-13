<?php
class Alumnos_cc_model extends CI_Model {
		
		public $id;
		public $id_alumno;
		public $fecha_origen;
		public $fecha_vto;
		public $id_concepto;
		public $periodo;
		public $capital;
		public $original;
		public $pendiente;
		public $fecha_saldo;
        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }

        public function guardar($item){
					
					
					if($item->id > 0 ){
								$this->db->where('id',$item->id);
								$this->db->update('alumnos_cc', $item);
								
					}
					else{
							$item->fecha_origen=date('Y-m-d');
							$item->fecha_saldo=$item->fecha_vto;
							$this->db->insert('alumnos_cc',$item);
						
					}		
			
			}
		
		 public function eliminar($id){
					$this->db->where('id', $id);
					$this->db->delete('alumnos_cc');
		
		}	
		public function cc($id){
			$sql="select a.id,b.nombre,
				date_format(a.fecha_vto,'%d/%m/%Y') as vto,
				a.periodo ,
				case when a.fecha_saldo <= CURRENT_DATE() then 
					round((a.capital+a.pendiente + a.pendiente * (x.valor/100)* datediff(CURRENT_DATE(),a.fecha_saldo)),2) 
				else	
					round(a.capital+a.pendiente - a.pendiente * (y.valor/100),2) 
				end
				as debe,
				c.importe as haber,d.comprobante,d.id as id_pago,date_format(d.fecha,'%d/%m/%Y') as fe_pago
				from  alumnos_cc a inner join conceptos b on a.id_concepto=b.id
                inner join configuracion x on x.codigo='INTERES_DIARIO'
                inner join configuracion y on y.codigo='PRONTO_PAGO'
				left join ( cc_pago_factura c  inner join cc_pago d on c.id_pago=d.id  
								and d.id NOT
								IN (
								SELECT DISTINCT id_anula
								FROM cc_pago
								WHERE id_anula >0
								)				
        ) on a.id_alumno=d.id_alumno and a.id=c.id_alumno_cc
			where a.id_alumno=? 
				order by a.fecha_vto,fe_pago";
			$query=$this->db->query($sql,array($id));
			return $query->result();
			
			
			}		
		public function a_cancelar($id,$interes=1){
			//Si calcula intereses y descuento la variable parametro interes toma el valor 1
			//sino 0 
			$sql="select a.id, 
			b.nombre, 
			date_format(a.fecha_vto,'%d/%m/%Y') as vto, 
			a.periodo , 
			case when a.fecha_saldo <= CURRENT_DATE() then 
				ceil(a.pendiente + a.pendiente * (x.valor/100)* datediff(CURRENT_DATE(),a.fecha_saldo)* ". $interes .")  
			else
				ceil(a.pendiente - a.pendiente * (y.valor/100) * ".$interes .") 
			end
			as debe,
			sum(ifnull(c.importe,0)) as haber 
					from alumnos_cc a 
						inner join 
						conceptos b on a.id_concepto=b.id 
							inner join configuracion x on x.codigo='INTERES_DIARIO' 
							inner join configuracion y on y.codigo='PRONTO_PAGO'
								left join ( cc_pago_factura c inner join cc_pago d on c.id_pago=d.id 
								and d.id NOT
								IN (
								SELECT DISTINCT id_anula
								FROM cc_pago
								WHERE id_anula >0
								)				
								) on a.id_alumno=d.id_alumno and a.id=c.id_alumno_cc 
								where a.id_alumno=? group by a.id,b.nombre,vto,a.pendiente ";
			$query=$this->db->query($sql,array($id));
			return $query->result();
			
			
			}	
			
		public function guardar_pago($data,$id_pago=0){
				$importes=$data["importes"];
				$id_alumno=$data["id_alumno"];
				//Total
				$total=0;
   //var_dump($importes);die;
				foreach($importes as $im)
				     if($im["importe"]>0) 
							$total=$total + $im["importe"];
				if($total>0){			
					if($id_pago==0){	
					$query=$this->db->query("select ultimo + 1  as nro from facturante");
					$rta=$query->result();				
					//Preparo El Pago en CC_pago
					$cc_pago = new stdClass;
					$cc_pago->id=Null;
					$cc_pago->fecha=date('Y-m-d');
					$cc_pago->id_alumno=$id_alumno;
					$cc_pago->importe=$total;
					$cc_pago->comprobante=$rta[0]->nro;
					//agrego
					$this->db->insert('cc_pago',$cc_pago);
					//devuelvo id
					$cc_pago->id=$this->db->insert_id();
					$id_pago=$cc_pago->id;
					return $id_pago;
					exit;
					}
					else					{
						$aux=$this->db->get_where('cc_pago',array("id"=>$id_pago));
						$aux=$aux->result();
						$cc_pago=$aux[0];
					}
					//Agrego los items del pago
			    foreach($importes as $im){
						//creo el item
					  if($im["importe"]>0) {	
						$cc_p=new stdClass;
						$cc_p->id=Null;
						$cc_p->id_alumno_cc=$im["id"];
						$cc_p->id_pago=$cc_pago->id;
						$cc_p->importe=$im["importe"];
						//**nuevo
						$deuda=$this->db->get_where('alumnos_cc',array("id"=>$cc_p->id_alumno_cc));
						$deuda=$deuda->result();
						$deuda=$deuda[0];						
						$cc_p->pendiente=$deuda->pendiente;
						$cc_p->debe=$im["debe"];
						if($deuda->pendiente > $cc_p->debe){
							$cc_p->descuento=$deuda->pendiente - $cc_p->debe;
						}
						elseif($deuda->pendiente < $cc_p->debe){
								$cc_p->interes=$cc_p->debe - $deuda->pendiente;
						}
						//**nuevo
						$cc_p->saldo=$cc_p->debe - $cc_p->importe;
						$this->db->insert('cc_pago_factura',$cc_p);
						
						//aca tengo que actualizar el capital y el pendiente.
						/*$deuda=$this->db->get_where('alumnos_cc',array("id"=>$cc_p->id_alumno_cc));
						$deuda=$deuda->result();
						$deuda=$deuda[0];*/
						
						$deuda->capital=$deuda->capital + $cc_p->importe;;
						$deuda->pendiente=$im["debe"]-$cc_p->importe;
						$deuda->fecha_saldo=date('Y-m-d');
						if($deuda->pendiente<0)
								$deuda->pendiente=0.00;
						//$this->db->where('id',$cc_p->id_pago);
						$this->db->replace('alumnos_cc', $deuda);
					}	
				}
			    
			}
		return $id_pago;
		}
	public function cc_pago($id_pago){
          $r= $this->db->get_where('cc_pago',array('id'=>$id_pago));
          $rta=$r->result();
          return $rta[0];
      }
	 public function listar_facturas($d,$h){
		$sql="select cc_pago.*,date_format(cc_pago.fecha,'%d/%m/%Y') as fecha,alumnos.nombre,alumnos.apellido,alumnos.dni 
		,T5.id_anula as anulada,cc_pago.fecha as ff
		from cc_pago inner join 
		alumnos on cc_pago.id_alumno=alumnos.id 
		left join (select distinct id_anula from cc_pago where id_anula >0 ) as T5 on cc_pago.id=T5.id_anula
		where cc_pago.fecha between ? and ? order by ff,tipo_comp,comprobante ";
			$query=$this->db->query($sql,array($d,$h));
			return $query->result();
	 
	 } 
	 public function listar_ingresos($d,$h){
		$sql="select cc_pago_medios.*,date_format(cc_pago_medios.fecha_acreditacion,'%d/%m/%Y') as fecha,alumnos.nombre,alumnos.apellido,alumnos.dni,cc_pago.comprobante,cc_pago.tipo_comp from cc_pago inner join alumnos on cc_pago.id_alumno=alumnos.id  inner join cc_pago_medios on cc_pago_medios.id_pago=cc_pago.id where cc_pago_medios.fecha_acreditacion between ? and ? order by fecha_acreditacion,comprobante";
			$query=$this->db->query($sql,array($d,$h));
			$rta=$query->result();
			return $rta;
	 } 
	 
	  public function borradeuda($id){
			$deuda=$this->db->get_where('alumnos_cc',array("id"=>$id));
			//print_r($deuda);die;
			$deuda=$deuda->result();
			$deuda=$deuda[0];
			if($deuda->capital==0){									
					$this->db->where('id', $id);
					$this->db->delete('alumnos_cc');		
			 }
			return $deuda;	
			
	 } 
	 public function borrarpago($id){
											
					$this->db->where('id', $id);
					$this->db->delete('cc_pago');		
			
		
			
	 } 
	 public function traer_ultimo_ccpago(){
	 //TRAE  el cc_pago que no haya pasado por afip o que no este completo o 
	 //si tiene errores lo mostramos ..sino tenemos que preguntar si lo borra porque puede que otro usuario este intentando hacer un 
	 //recibo
			$sql="select cc_pago.*,alumnos.nombre,alumnos.apellido from cc_pago left join alumnos on cc_pago.id_alumno=alumnos.id
			where cc_pago.cae='' or vto=''
			";
			$query=$this->db->query($sql);
			return $query->result();
	  
	 }
	 public function verificar_factura(){
		//verifica el ultimo numero de factura del facturante sea igual
		//a la ultima facura enviada a la afip
			$sql="SELECT ifnull(max( comprobante ),0) as rta
						FROM cc_pago
						WHERE cae <> ''
						AND vto <> '' 
			";
			$query=$this->db->query($sql);
			$query=$query->result();
			if(count($query)==0){
				$ucomp=0;
			}
			else
			{	
				$ucomp=$query[0];
				$ucomp=$ucomp->rta;
			}	
			$sql="SELECT ultimo from facturante";
			$query=$this->db->query($sql);
			$query=$query->result();
			$ufac=$query[0];
			$ufac=$ufac->ultimo;
			if($ucomp==$ufac)
				return true;
			else
				return false;
	 }
	 
	 public function controlar($deuda){
	 //vamos a controlar que no existe ya la deuda
	  $sql="SELECT * from alumnos_cc where id_alumno=? and id_concepto=? and periodo=? ";
	  $query=$this->db->query($sql,array($deuda->id_alumno,$deuda->id_concepto,$deuda->periodo));
	  $rta=$query->result();
	  return(count($rta));
	 }
    }
?>
