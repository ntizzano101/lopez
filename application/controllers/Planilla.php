<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Planilla extends CI_Controller {

	
	public function __construct(){
		
		      parent::__construct();
			if(!isset($this->session->usuario)){
				redirect('salir');
				exit;
		}		
		
		
		
	}	
	
   
	public function listar()
	{
		$data["mensaje"]="";
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('proveedores/listado',$data);
		

	}
	public function buscar()
	{
		$p=$this->input->post('buscar');
		$this->load->model('proveedores_model');
		$data["clientes"]=$this->proveedores_model->buscar($p);
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('proveedores/listado',$data);


	}
	public function listado()
	{
		$clie=$this->input->post('clie');
		$prov=$this->input->post('prov');
		$this->load->library('funciones');
		$desde=$this->input->post('desde');
		$hasta=$this->input->post('hasta');
		if($desde=="")
				$desde=date('d/m/Y', strtotime('-30 days'));
		if($hasta=="")
				$hasta=date('d/m/Y');
		$desdeDB=$this->funciones->fechaToDb($desde);
		$hastaDB=$this->funciones->fechaToDb($hasta);
		$data["desde"]=$desde;
		$data["hasta"]=$hasta;
		$data["clie"]=$clie;
		$data["prov"]=$prov;
		$this->load->model('planilla_model');
		$data["pedidos"]=$this->planilla_model->listado($desdeDB,$hastaDB,$clie,$prov);
		if($this->input->post('imprimir')==0){
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('planilla/listado',$data);
		}
		elseif($this->input->post('imprimir')==1){
			$this->load->view('planilla/listado2',$data);
			
		}
		elseif($this->input->post('imprimir')==2){
			$this->load->view('planilla/listado3',$data);
			
		}
	}
		public function nuevo($p='')
	{
		$this->load->library('session');
		$this->load->model('planilla_model');
		$this->load->model('proveedores_model');
		$this->load->model('clientes_model');
		$aux=new $this->planilla_model();
//	echo $this->session->fecha . " , " ; 
//	die();
		$rta=array();
		if($p>0){
			$rta=$this->planilla_model->editar($p);
			$data["planilla"]=$rta[0];
			
		}	
		if($p=='' or $this->input->post('id')!==null or count($rta)==0){
			$aux->id=$this->input->post('id');
			$aux->fecham=$this->session->fecha;
			$aux->fecha=$this->session->fecha;
			
			$data["planilla"]=$aux;
		}
	//	echo $this->session->fecha;
	//	$this->session->set_userdata('fecha','2018-06-10');
	//	echo $this->session->fecha;
	//	die;
		 
		if($p=='')	
			$data["id"]=$p;
		else	
			$data["id"]=$this->input->post('id');
		if($this->input->post('id')!==null){
				$this->load->helper(array('form', 'url'));
                $this->load->library('form_validation');				
                if ($this->form_validation->run() == True)
                {
					$this->proveedores_model->guardar($data);
					redirect('planilla/listado');
				}
				
		}
		
		$data["mensaje"]="";
		$data["clientes"]=$this->clientes_model->cmbClientes('%');
		$data["proveedores"]=$this->proveedores_model->cmbProveedores('%');
		//print_r($data);die;
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('planilla/nuevo',$data);
		

	}


	public function eliminar($p='')
	{
		$this->load->model('planilla_model');
		$rta=array("ok"=>'0',"mensaje"=>"","pedido"=>"");
		if($p==0)
				$p=$this->input->post('id');
		if($p>0 and is_numeric($p) and $p<90000){
			$rta1=$this->planilla_model->eliminar($p);
			$pedido=$rta1["pedido"];
			$pago=$rta1["cc_pago"];
			//var_dump($pedido);die;
			if(count($pedido) > 0){
				$rta["ok"]="1";	
				$rta["pedido"]=" Pedido # " . $pedido[0]->id ;	
			}
			if(count($pago) > 0){
				$rta["ok"]="0";	
				$rta["mensaje"]=" Hay Pagos Para Este Pedido";	
			}
		}
		else{
				$rta["mensaje"]=" Parametros Para Borrado Incorrectos";
			}
				
		echo json_encode($rta);
		return;
	}		
 public function es_fecha($fecha){
	$this->load->library('funciones');
	return $this->funciones->fecha_nacimiento($fecha);
}
 public function delete($p=''){
		$p=$this->input->post('id');
		$this->load->model('planilla_model');
		$rta1=$this->planilla_model->delete($p);
	}
 public function verifica($p=''){
		//Verificamos campo por campo
		//id numerico					
				 $aa=array();
				 $this->load->library('funciones');
				 $aa["mensaje"]="";				
				 //die(json_encode($aa));
				 $id=$this->input->post('id');
				 $rta=array();
				 if(!($id==0 or $id=="" or  $id==null or is_numeric($id)))
					$rta["id"]="El Id No es Válido";
				 $idc=$this->input->post('idc');	
				 if(!is_numeric($idc))
					$rta["idc"]="El Id de Cliente No es Válido";
				 $idp=$this->input->post('idp');
				 if(!is_numeric($idp))
				 	$rta["idp"]="El Id de Proveedor No es Válido";
				 $fecha=$this->input->post('fecha');
				 list($aa1,$mm,$dd)=explode("-",$fecha);
				 $fecha=$dd."/".$mm."/".$aa1;
				 if(!$this->es_fecha($fecha))
				 	$rta["fecha"]="El Formato de Fecha no es Válido";				 
				 /*$catres=$this->input->post('catres');
				 if(!($catres > 0  and $catres<10))
					$rta["catres"]="La Cantidad de Reses es incorrecta";
				 $kilos=$this->input->post('kilos');
				 if(!($kilos > 0  and $kilos<9000))
					$rta["kilos"]="El Total de Kilos es Incorrecto";
				 */
				 $preciov=$this->input->post('preciov');
				 if(!($preciov > 0  and $preciov<999999))
				 	$rta["preciov"]="El precio de Venta es Incorrecto";
				 //Descuento por en kilos cada media res
				 $descuento=$this->input->post('descuento');
				 if(!($descuento >= 0  and $descuento<6))
				 	$rta["descuento"]="El descuento deberia de 0 a no mas de 5kg por 1/2 res";	
				 $precioc=$this->input->post('precioc');
				 if(!($precioc > 0  and $precioc<999999))
				 	$rta["precioc"]="El Precio de Compra es Incorrecto";
				 /*$totalc=$this->input->post('totalc');
				 if(!($totalc > 0  and $totalc < 999999))
				 	$rta["totalc"]="El Precio Total de Compra es Incorrecto";
				 $totalv=$this->input->post('totalv');
				 if(!($totalv > 0  and $totalc < 999999))
				 	$rta["totalv"]="El Precio Total de Venta es Incorrecto";
				 */
				 $obs=$this->input->post('obs');
				 if(!(strlen($obs) >= 0  and strlen($obs) < 30))
				 	$rta["obs"]="Las Observaciones son muy largas";
				//Este es El Ingreso de Datos , Ahora Falta los controles de cada tipo.
				//Verificamos que suma kilo de res sea Correcto
				 $sumares=$this->input->post('sumares');
				 //Primero verificamos que tenga algo
				 $totk=explode("+",trim($sumares));
				 $tt=0;
				 if(count($totk)>0){
				 foreach($totk as $r){
						if(is_numeric($r))
							$tt=$tt+$r;
						else{
							$rta["sumares"]="El Valor ". $r  . " No es valido";				
						}
					 }
					 $catres= count($totk);
					 $kilos=$tt;
				 }
				 else{
					   if(!is_numeric($sumares))		
							$rta["sumares"]="El Valor". $sumares . " No es valido";	
					  else{
							$catres=1	;
							$kilos=$sumares;	
						}		
					 }
				if (!is_numeric($descuento))	
						$descuento=0;
				if (!is_numeric( $catres))	
						 $catres=0;
				if (!is_numeric($kilos))	
						 $kilos=0;
				if (!is_numeric($preciov))	
						 $preciov=0;
					if (!is_numeric($precioc))	
						 $precioc=0;		 
				 $aa["errores"]=$rta;
				 $aa["id"]=$id;
				 $aa["catres"]=$catres;
				 $aa["kilos"]=$kilos;
				 $aa["totalv"]=($kilos - $descuento * $catres) * $preciov;				 
				 $aa["totalc"]=$kilos * $precioc;
				 $aa["ganancia"]=$aa["totalv"] - $aa["totalc"];
				 
				 if(count($rta)==0){
					 //guardamos planilla
					 $this->load->model('planilla_model');
					 $planilla=new stdClass;
					 $planilla->id=$id;
					 $planilla->idc=$idc;
					 $planilla->idp=$idp;
					 $planilla->fecha=$this->funciones->fechaToDb($fecha);
					 $planilla->catres=$catres;
					 $planilla->kilos=$kilos;
					 $planilla->preciov=$preciov;
					 $planilla->precioc=$precioc;
					 $planilla->sumares=$sumares;
					 $planilla->totalc= $aa["totalc"];
					 $planilla->totalv= $aa["totalv"];
					 $planilla->obs= $obs;
					 $planilla->descuento=$descuento;
					 $id=$this->planilla_model->guardar($planilla);
					 $aa["id"]=$id;
					 }
				die(json_encode($aa));		
					
	 }	
}
?>
