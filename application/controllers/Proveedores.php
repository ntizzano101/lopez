<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedores extends CI_Controller {

	
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
		$this->load->library('funciones');
			$fx=$this->funciones;
		$data["fx"]=$fx;
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('proveedores/listado',$data);


	}
	public function listado()
	{
		$this->load->model('proveedores_model');
		$this->load->library('funciones');
			$fx=$this->funciones;
		$data["fx"]=$fx;
		$data["clientes"]=$this->proveedores_model->listado();
		
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('proveedores/listado',$data);
	}
		public function editar($p='')
	{
		$this->load->model('proveedores_model');
		
		if($p>0){
			
			$rta=$this->proveedores_model->editar($p);
			$data["proveedor"]=$rta[0];
		}	
		if($p=='' or $this->input->post('id')!==null or count($rta)==0){
			$aux= new stdClass;
			$aux->id=$this->input->post('id');
			$aux->nombre=$this->input->post('nombre');
			$data["proveedor"]=$aux;
		}
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
					redirect('proveedores/listado');
				}
				
		}
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('proveedores/editar',$data);
		

	}


	public function eliminar($p='')
	{
		$this->load->model('proveedores_model');
		$rta=array("ok"=>'0',"mensaje"=>"","proveedor"=>"");
		$p=$this->input->post('id');
		if($p>0 and is_numeric($p) and $p<90000){
			$rta1=$this->proveedores_model->eliminar($p);
			$prove=$rta1["prove"];
			$pedido=$rta1["pedido"];
			$pago=$rta1["cc_pago"];
			if(count($prove) > 0){
				$rta["ok"]="1";	
				$rta["proveedor"]=$prove[0]->nombre;	
			}
			if(count($pedido) > 0){
				$rta["ok"]="0";	
				$rta["mensaje"]=" Hay Pedidos Para Este Proveeedor";	
			}
			if(count($pago) > 0){
				$rta["ok"]="0";	
				$rta["mensaje"]=" Hay Pagos Para Este Proveedor";	
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
		$this->load->model('proveedores_model');
		$rta1=$this->proveedores_model->delete($p);
	}

public function cuentacorriente($p=''){				
		$this->load->model('cuentacorriente_model');
		$t='P';
		$rta=$this->cuentacorriente_model->trae($p,$t);
		$data["cc"]=array();
		//var_dump($rta);die;
		if(count($rta)>0)
			$data["cc"]=$rta;
		$this->load->library('funciones');
		$fx=$this->funciones;
		$data["fx"]=$fx;
		$data["nombre"]=$this->cuentacorriente_model->nombre($p,'P');	
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('proveedores/cuentacorriente',$data);
	
	}
	
	public function acancelar($p=''){				
		$this->load->model('cuentacorriente_model');
		$t='P';
		$rta=$this->cuentacorriente_model->acancelar($p,$t);
		$data["cc"]=array();
		//var_dump($rta);die;
		if(count($rta)>0)
			$data["cc"]=$rta;
		$this->load->library('funciones');
		$fx=$this->funciones;
		$data["fx"]=$fx;
		$data["nombre"]=$this->cuentacorriente_model->nombre($p,$t);	
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('proveedores/acancelar',$data);
	
	}
	public function eliminarpago($p=''){
		$rta["ok"]="0";
		$rta["pedido"]="";
		$rta["mensaje"]="";
		if($p=='')
			$p=$this->input->post('id');
		if($p>0 and is_numeric($p) and $p<90000){
				$this->load->model('cuentacorriente_model');
				$a=$this->cuentacorriente_model->eliminar($p);
				$rta["ok"]="1";
				$rta["pedido"]=$p;
	
		}
		else{
				$rta["mensaje"]="El Parametro es incorrecto";
			}	
	  echo json_encode($rta);
	  return;		
 }
	function guardapago(){
			 $rta=array("ok"=>"0",	
				    "mensaje"=>""
					);
			 $o=$i=$c=array();		
			 $o=$_POST["o"];
			 $i=$_POST["i"];
			 $c=$_POST["c"];
			 $obs=$_POST["obs"];
			 $fecha=$_POST["fecha"];
			 list($anio,$mes,$dia)=explode("-",$fecha);
			 $fecha=$dia."/".$mes."/".$anio;
			 //Controlamos los post.
			if(strlen($obs)>30){
				$rta=array("ok"=>"1",	
				    		"mensaje"=>"Las observaciones son muy largas");
						 echo json_encode($rta);
		  				return;		
				
			} 
				if(!($this->es_fecha($fecha) and trim($fecha)<>"")){
				$rta=array("ok"=>"1",	
				    		"mensaje"=>"La Fecha no es correcta");
						 echo json_encode($rta);
		  				return;		
				
			} 
			$aux=array($o,$i,$c);			 
			foreach($aux as $a){
					 for($j=0;$j<count($a);$j++){
						if(!(is_numeric($a[$j])))
						{
						$rta=array("ok"=>"1",	
				    		"mensaje"=>"Valor NO PERMITIDO " . $a[$j]);
						 echo json_encode($rta);
		  				return;					
						}	
					}			
			}			
			 for($j=0;$j<count($o);$j++){
				if($o[$j]<$c[$j]){
						$rta=array("ok"=>"1",	
				    		"mensaje"=>"No Puede Cancelar " . $c[$j] . " de " . $o[$j] );
						 echo json_encode($rta);
		  				 return;					
						}						
			}
			 
			//Si llegamos aca Entonces Esta Todo Bien para Cancelar.
			$this->load->model('cuentacorriente_model');
			$aux[]=$obs;
			$aux[]='P';
			$this->cuentacorriente_model->guardopagocliente($aux);
		echo json_encode($rta);
	  	return;				
	

		}
	public function borrarpago($p=''){				
		$this->load->model('cuentacorriente_model');
		if($p=='')
			$p=$this->input->post('id')
			;
		//es el id PEDIDO	
		if($p>0 and is_numeric($p) and $p<90000){
					$rta=$this->cuentacorriente_model->traepagos($p,'P');
		}
		else{
			 $rta=array();
			}
		$data["cc"]=$rta;
		
		$this->load->library('funciones');
		$fx=$this->funciones;
		$data["fx"]=$fx;
		$data["idpedido"]=$p;
		$aa=$this->cuentacorriente_model->pedido($p,'P');	
		//var_dump($aa);die;
		$data["nombre"]=$aa[0];
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('proveedores/borrarpagos',$data);
	}

}
?>
