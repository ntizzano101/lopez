<?php
class Nota_de_credito_model extends CI_Model {
        public $recibo;
        public $nc;
        public $medios_pagos;

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }
        public function borrar(){
             return true;
             $this->db->where('id',$this->nc->id);
             $this->db->delete('cc_pago');
             
     }
     public function refrescar_nc($id){
            $r=$this->db->get_where('cc_pago',array("id"=>$id));
            $r=$r->result();
            $this->nc=$r[0];
     }
     public function anular($id){
            //Actualizamos La cuenta Corriente//
            $sql="update alumnos_cc cc inner join
             cc_pago_factura ccp on cc.id=ccp.id_alumno_cc and ccp.id_pago=?
             set cc.capital=cc.capital - ccp.importe,cc.pendiente=cc.pendiente + ccp.importe
             ";
            $query=$this->db->query($sql,array($id));
            //$query->result();
            
            //Ahora Generamos los Movimientos de Caja
            $query=$this->db->get_where('cc_pago_medios',array("id_pago"=>$id));
            $this->medio_pago=$query->result();
            foreach($this->medio_pago as $p){
                  $p->id='';
                  $p->id_pago=$this->nc->id;
                  $this->db->insert('cc_pago_medios',$p);
            }
            //
            //
     }
        public function verificar_anulado($id){
            //siguiente
            $sql="select ifnull(ultima_nc,0) + 1 as siguiente from facturante";
            $query=$this->db->query($sql);
            $query=$query->result();
            $siguienteNC=$query[0]->siguiente;
            //
            /*

            */
            $sql="Select * from cc_pago where id=?  and  id_anula   is null
            UNION
            select * from cc_pago where id_anula=?";
           	$query=$this->db->query($sql,array($id,$id));
           	$query=$query->result();
            if(count($query)==0)
                               $error=array(0,"Los Datos Suministrados Son Erroneos. No Existe El Pago  ");
            else{
                 $this->recibo=$query[0];

                 if(empty($query[1])){
                     $error=array();
                     $this->nc = $this->recibo;
                     $this->nc->id='';
                     $this->nc->fecha=date('Y-m-d');
                     $this->nc->comprobante=$siguienteNC;
                     $this->nc->id_anula=$id;
                     $this->nc->barranro='';
                     $this->nc->barraimagen='';
                     $this->nc->errores='';
                     $this->nc->cae='';
                     $this->nc->vto=Null;
                     $this->nc->tipo_comp='13'; //NC
                     $this->db->insert('cc_pago',$this->nc);
                     $this->nc->id=$this->db->insert_id();
                     }
                 else{
                    $this->nc=$query[1];
                     $error=array(1,"Ya Hay Nota De Credito");
                     }
            }
            return $error;

        }

        /*

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
        ) on a.id_alumno=d.id_alumno and a.id=c.id_alumno_cc
			where a.id_alumno=?
				order by vto,fe_pago";
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
				round(a.pendiente + a.pendiente * (x.valor/100)* datediff(CURRENT_DATE(),a.fecha_saldo)* ". $interes ." ,2)  
			else
				round(a.pendiente - a.pendiente * (y.valor/100) * ".$interes ." ,2) 
			end
			as debe,
			sum(ifnull(c.importe,0)) as haber 
					from alumnos_cc a 
						inner join 
						conceptos b on a.id_concepto=b.id 
							inner join configuracion x on x.codigo='INTERES_DIARIO' 
							inner join configuracion y on y.codigo='PRONTO_PAGO'
								left join ( cc_pago_factura c inner join cc_pago d on c.id_pago=d.id ) on a.id_alumno=d.id_alumno and a.id=c.id_alumno_cc 
								where a.id_alumno=? group by a.id,b.nombre,vto,a.pendiente ";
			$query=$this->db->query($sql,array($id));
			return $query->result();
			
			
			}	
			
		public function guardar_pago($data){
				$importes=$data["importes"];
				$id_alumno=$data["id_alumno"];
				$id_pago=0;
				//Total
				$total=0;
   //var_dump($importes);die;
				foreach($importes as $im)
				     if($im["importe"]>0) 
							$total=$total + $im["importe"];
				if($total>0){			
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
			    //Agrego los items del pago
			    foreach($importes as $im){
						//creo el item
					  if($im["importe"]>0) {	
						$cc_p=new stdClass;
						$cc_p->id=Null;
						$cc_p->id_alumno_cc=$im["id"];
						$cc_p->id_pago=$cc_pago->id;
						$cc_p->importe=$im["importe"];
						$this->db->insert('cc_pago_factura',$cc_p);
						//aca tengo que actualizar el capital y el pendiente.
						$deuda=$this->db->get_where('alumnos_cc',array("id"=>$cc_p->id_alumno_cc));
						$deuda=$deuda->result();
						$deuda=$deuda[0];
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
      }*/
 }
?>
