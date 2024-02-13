<?php
class Cuentacorriente_model extends CI_Model {

    public $id;
    public $idcp;       
    public $fecha;
	public $monto;
	public $obs;
	public $tipo;
       
    public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }
        public function nombre($p,$t=''){
				if($t==''){
						$t='C';
						$query=$this->db->get_where('clie',array("id"=>$p));
				//echo $this->db->queries[0];die;
				$a= $query->result();
				if(count($a)>0)
					return $a[0]->nombre;
				return "No Existe Cliente";
					}	
				else{
						$t='P';
						$query=$this->db->get_where('prove',array("id"=>$p));
				//echo $this->db->queries[0];die;
				$a= $query->result();
				if(count($a)>0)
					return $a[0]->nombre;
				return "No Existe Proveedor";
				}
			  		
			}
				

        public function trae($p='',$t='')
        {
				//id Cliente o Proveedor
				//cliente o proveedor
				//monto de cc_pago
				//totalv de Pedido.
		 if($t=='C'){
			/*$sql="Select pedido.fecha , date_format(pedido.fecha,'%d/%m/%Y') as Fe ,
			pedido.totalv as debe ,case when sum(cc_pago_det.monto) is null then 0 else sum(cc_pago_det.monto) end as haber , pedido.id ,concat(prove.nombre,' ', pedido.obs ) descripcion 
			from pedido inner join prove on pedido.idp=prove.id 
			left join cc_pago_det on cc_pago_det.idpedido=pedido.id and cc_pago_det.tipo='C' where pedido.idc=? 
			 group by pedido.fecha,prove.nombre,pedido.totalv,pedido.id,pedido.obs " ;*/
		$sql="SELECT T1.* from  (
			Select 0 linea, pedido.fecha , date_format(pedido.fecha,'%d/%m/%Y') as Fe ,
			pedido.totalv as debe , 0 as haber,pedido.id ,concat(substr(prove.nombre,1,3),' ', pedido.obs ,' ', pedido.kilos,'Kgx$',pedido.preciov) descripcion from  pedido inner join prove on pedido.idp=prove.id  where pedido.idc=?
			Union
			Select cc_pago.id,cc_pago.fecha , date_format(cc_pago.fecha,'%d/%m/%Y')  ,
			0,cc_pago.monto ,cc_pago.id,cc_pago.obs from cc_pago where cc_pago.idcp=? and cc_pago.tipo='C') as T1 order by fecha,id
			" ;	 	
			$query = $this->db->query($sql,array($p,$p));
		}
		else{
		$sql="select T1.*,T2.cobrado 
		from ( Select pedido.fecha , date_format(pedido.fecha,'%d/%m/%Y') as Fe ,concat(clie.nombre,' ', pedido.obs ) descripcion , pedido.totalc as debe ,sum(cc_pago_det.monto) as haber , pedido.id ,pedido.totalv,pedido.totalv - pedido.totalc as gcia1 from pedido inner join clie on pedido.idc=clie.id left join cc_pago_det 
		on cc_pago_det.idpedido=pedido.id and cc_pago_det.tipo='P' where pedido.idp=? group by pedido.fecha,clie.nombre,pedido.totalc,pedido.id,pedido.obs )
		as T1 left join (select idpedido,sum(monto) as cobrado from cc_pago_det where tipo='C' GROUP by idpedido)
		as T2 on T1.id=T2.idpedido" ;	
		 	$query = $this->db->query($sql,array($p));
			}		
			
		 //  echo $this->db->queries[0];die;
		   $rta=$query->result();
		   return $rta;
		}
		
	public function acancelar($p='',$t='')
        {
				//id Cliente o Proveedor
				//cliente o proveedor
				//monto de cc_pago
				//totalv de Pedido.
		 if($t=='C'){
		 $sql="select T1.fecha,T1.Fe,T1.debe,T1.haber,T1.id,T1.descripcion from ( select fecha , date_format(fecha,'%d/%m/%Y') as Fe , totalv as debe , case when SUM(cc_pago_det.monto) is null then 0 else SUM(cc_pago_det.monto) end as haber , pedido.id , concat(prove.nombre , ' ' , kilos,' Kg') as descripcion from pedido inner join prove on pedido.idp=prove.id left join cc_pago_det on cc_pago_det.idpedido=pedido.id and tipo='C'  where idc=?  group by pedido.id,pedido.fecha,pedido.totalv,prove.nombre,pedido.kilos ) as T1 where T1.debe > T1.haber";	
		}
		else{
			
		$sql="select T1.fecha,T1.Fe,T1.debe,T1.haber,T2.cobrado,T1.id,T1.descripcion from ( select fecha , date_format(fecha,'%d/%m/%Y') as Fe , totalc as debe , case when SUM(cc_pago_det.monto) is null then 0 else SUM(cc_pago_det.monto) end as haber , pedido.id , concat(clie.nombre , ' ' , kilos,' Kg') as descripcion from pedido inner join clie on pedido.idc=clie.id left join cc_pago_det on cc_pago_det.idpedido=pedido.id and tipo='P' where idp=? group by pedido.id,pedido.fecha,pedido.totalc,clie.nombre,pedido.kilos ) as T1 left join (select cc_pago_det.idpedido,sum(cc_pago_det.monto) as cobrado from cc_pago_det where tipo='C' group by cc_pago_det.idpedido) as T2 on T2.idpedido=T1.id where T1.debe > T1.haber ";
		//"select T1.fecha,T1.Fe,T1.debe,T1.haber,T1.id,T1.descripcion from ( select fecha , date_format(fecha,'%d/%m/%Y') as Fe , totalc as debe , case when SUM(cc_pago_det.monto) is null then 0 else SUM(cc_pago_det.monto) end as haber , pedido.id , concat(clie.nombre , ' ' , kilos,' Kg') as descripcion from pedido inner join clie on pedido.idc=clie.id left join cc_pago_det on cc_pago_det.idpedido=pedido.id and tipo='P' where idp=?  group by pedido.id,pedido.fecha,pedido.totalc,clie.nombre,pedido.kilos ) as T1 where T1.debe > T1.haber";		
			}		
			$query = $this->db->query($sql,array($p));
		 //  echo $this->db->queries[0];die;
		   $rta=$query->result();
		   return $rta;
		}
	 public function eliminar($p)
        {
				$this->db->where('id', $p);
				$this->db->delete("cc_pago");
				$this->db->where('idpago', $p);
				$this->db->delete("cc_pago_det");
        }	
	public function guardopagocliente($p)
	{
	//a cancelar
	$c=$p[2];
	//Saco Total
	$total=0;
	for($x=0;$x<count($c);$x++)
			$total=$total+$c[$x];
	//ids de las planillas
	$i=$p[1];
	//Consulto la primer planilla para sacar el id de cliente 
	$query=$this->db->get_where('pedido',array("id"=>$i[0]),1);
	$b=$query->result();
	if($p["4"]=='C')
		$idCliente=$b[0]->idc;
	else	
		$idCliente=$b[0]->idp;
	//Creo El Objeto para insercion
	$pago = new StdClass;
	$pago->id=Null;
    $pago->idcp=$idCliente;       
    $pago->fecha=date('Y-m-d');
	$pago->monto=$total;
	$pago->obs=$p[3];  //$obs;
	$pago->tipo=$p[4];
	//inserto registro	en cc_pa;go
	$query=$this->db->insert('cc_pago', $pago);
	//Devolvemos ID
	$lastid = $this->db->insert_id();
	//inserto detalles
	$this->load->model('cuentacorrientedetalle_model');
	for($x=0;$x<count($c);$x++){
			$obj=new stdClass;
			$obj->id=null;
			$obj->idpedido=$i[$x];
			$obj->idpago=$lastid;
			$obj->monto=$c[$x];
			$obj->tipo=$p[4];
			$this->cuentacorrientedetalle_model->guardar($obj);
		}
	//Devuelvo mensaje	
	return(array($lastid,$total));
	}	


public function traepagos($p,$t){
		//primero traigo el idcliente del idpedido.
		//$query=$this->db->get_where('cc_pago_det',array("idpedido"=>$p),1);
		//$b=$query->result();
		//$b=$b[0];
		//en b quedo el idpago, ahora tengo que traer de ese pedido el idc o idp
	if($t=='P'){	
		$query=$this->db->get_where('pedido',array("id"=>$p),1);
		$x=$query->result();
		$x=$x[0];
		$id=$x->idp;
		$sql="select date_format(cc_pago.fecha,'%d/%m/%Y') as fecham,cc_pago.* from cc_pago 
		where cc_pago.idcp=? and cc_pago.tipo=? order by fecha desc , id asc";
		$query = $this->db->query($sql,array($id,$t));
		//	 echo $this->db->queries[0];die;	
		return $query->result();
	 }
	else{
		$query=$this->db->get_where('cc_pago',array("id"=>$p),1);
		$x=$query->result();
		$x=$x[0];
		$id=$p;
		$sql="select date_format(cc_pago.fecha,'%d/%m/%Y') as fecham,cc_pago.* from cc_pago 
		where cc_pago.id=? and cc_pago.tipo=? order by fecha desc , id asc";
		$query = $this->db->query($sql,array($id,$t));
		//	 echo $this->db->queries[0];die;	
		return $query->result();
		} 	
	}
public function pedido($p,$tipo='C'){
	if($tipo=='C'){
		
		$sql="SELECT clie.nombre as cli,clie.id 
			from  clie inner join cc_pago on cc_pago.idcp=clie.id and cc_pago.tipo='C'
				where cc_pago.id = ? limit 1";
		}			
	
	else
		{		
		$sql="SELECT date_format(fecha,'%d/%m/%Y') as fecham,clie.nombre as cli,prove.nombre as prov 
			from pedido inner join clie on clie.id = pedido.idc inner join prove on prove.id=pedido.idp 
				where pedido.id = ? limit 1";
		}			
		$query = $this->db->query($sql,array($p));
		return $query->result();
	}		
	public function guardopagoclientetotal($p)
	{
	//Inserto Registro en CC_pago
//retorno ID	
	/// Tomo la primer planilla adeuda,
	//saco el monto de la deuda
	// comparo con lo que pago
	//si el monto > pago	
				//  			
	//Saco Total
	$total=$p["monto"];
	//funciones de fecha
	$this->load->library('funciones');
	$fx=$this->funciones;
	//Creo El Objeto para insercion
	$pago = new StdClass;
	$pago->id=Null;
    $pago->idcp=$p["idcliente"];       
    $pago->fecha=$fx->fechaToDb($p["fecha"]);
	$pago->monto=$total;
	$pago->obs=$p["comentario"];  //$obs;
	$pago->tipo=$p["tipo"];
	//inserto registro	en cc_pa;go
	$query=$this->db->insert('cc_pago', $pago);
	//Devolvemos ID
	$lastid = $this->db->insert_id();
	//inserto detalles
	//Ahora hay que ver que cancela.
	//
	//Traigo las planillas con deudas
	$r=$this->acancelar($p["idcliente"],$p["tipo"]);	
	$this->load->model('cuentacorrientedetalle_model');
	foreach($r as $x){			
			if($total>0){
			$saldo=round($x->debe - $x->haber,2);
			$obj=new stdClass;
			$obj->id=null;
			$obj->idpedido=$x->id;
			$obj->idpago=$lastid;
			if($total >= $saldo)
				$obj->monto=$saldo;
			else
				$obj->monto=$total;
			$total=$total - $saldo ; 
			$obj->tipo=$p["tipo"];
			$this->cuentacorrientedetalle_model->guardar($obj);
		}
	}
	//Devuelvo mensaje	
	return(array($lastid,$total));
	}	
	}
?>
