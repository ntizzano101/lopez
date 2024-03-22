<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes extends CI_Controller {

	
	public function __construct(){
		
		      parent::__construct();
			//var_dump($this->session);
			if(!isset($this->session->usuario)){
				//die("kokoa");
				redirect('salir');
				exit;
		}		
		
		
		
	}	
	
   
	public function listar()
	{
		$data["mensaje"]="";
				$this->load->library('funciones');
		$fx=$this->funciones;
		$data["fx"]=$fx;
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('clientes/listado',$data);
		

	}
	public function buscar()
	{
		$p=$this->input->post('buscar');
		$p1=$this->input->post('solocd');
				$this->load->library('funciones');
		$fx=$this->funciones;
		$data["fx"]=$fx;
		$this->load->model('clientes_model');
		$data["clientes"]=$this->clientes_model->buscar($p,$p1);
		$data["solocd"]=$p1;
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('clientes/listado',$data);


	}
	public function listado()
	{
		$this->load->model('clientes_model');
		$data["clientes"]=$this->clientes_model->listado();
				$this->load->library('funciones');
		$fx=$this->funciones;
		$data["fx"]=$fx;
		$data["solocd"]="";
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('clientes/listado',$data);
	}
		public function editar($p='')
	{
		$this->load->model('clientes_model');
		
		if($p>0){
			
			$rta=$this->clientes_model->editar($p);
			$data["cliente"]=$rta[0];
		}	
		if($p=='' or $this->input->post('id')!==null or count($rta)==0){
			$aux= new stdClass;
			$aux->id=$this->input->post('id');
			$aux->nombre=$this->input->post('nombre');
			$data["cliente"]=$aux;
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
					$this->clientes_model->guardar($data);
					redirect('clientes/listado');
				}
				
		}
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('clientes/editar',$data);
		

	}

public function eliminar($p='')
	{
		$this->load->model('clientes_model');
		$rta=array("ok"=>'0',"mensaje"=>"","cliente"=>"");
		if($p==0)
				$p=$this->input->post('id');
		if($p>0 and is_numeric($p) and $p<90000){
			$rta1=$this->clientes_model->eliminar($p);
			$prove=$rta1["clie"];
			$pedido=$rta1["pedido"];
			$pago=$rta1["cc_pago"];			
            
            if(count($prove) > 0){
				//Existe el cliente OK
				$rta["ok"]="1";	
				$rta["cliente"]=$prove[0]->nombre;	
			}
            /*
			if(count($pedido) > 0){
				//no puede borrar tiene pedidos
				$rta["ok"]="0";	
				$rta["mensaje"]=" Hay Pedidos Para Este Cliente";	
			}
			if(count($pago) > 0){
				//No puede Borrar Tiene Pagos
				$rta["ok"]="0";	
				$rta["mensaje"]=" Hay Pagos Para Este Cliente";	
			}	
			*/
		}
		else{
				$rta["mensaje"]=" Parametros Para Borrado Incorrectos";
			}
	
		echo json_encode($rta);
		return;
}

public function delete($p=''){
		$p=$this->input->post('id');
		$this->load->model('clientes_model');
		$rta1=$this->clientes_model->delete($p);
	}
 public function es_fecha($fecha){
	$this->load->library('funciones');
	return $this->funciones->fecha_nacimiento($fecha);
}
public function borrarpago($p=''){				
		$this->load->model('cuentacorriente_model');
		if($p=='')
			$p=$this->input->post('id');
		//es el id PEDIDO
		//no no ahora es el id del CC_PAGO	
		if($p>0 and is_numeric($p) and $p<90000){
					$rta=$this->cuentacorriente_model->traepagos($p,'C');
		}
		else{
			 $rta=array();
			}		
		
		$data["cc"]=$rta;
		//var_dump($rta);die;
		$this->load->library('funciones');
		$fx=$this->funciones;
		$data["fx"]=$fx;
		$data["idpedido"]=$p;
		$aa=$this->cuentacorriente_model->pedido($p,'C');	
		$data["nombre"]=$aa[0];
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('clientes/borrarpagos',$data);
	}
public function cuentacorriente($p=''){				
		$this->load->model('cuentacorriente_model');
		$t='C';
		$rta=$this->cuentacorriente_model->trae($p,$t);
		$data["cc"]=array();
		//var_dump($rta);die;
		if(count($rta)>0)
			$data["cc"]=$rta;
		$this->load->library('funciones');
		$fx=$this->funciones;
		$data["fx"]=$fx;
		$data["idcliente"]=$p;
		$data["nombre"]=$this->cuentacorriente_model->nombre($p);	
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('clientes/cuentacorriente',$data);
	
	}
	
	public function acancelar($p=''){				
		$this->load->model('cuentacorriente_model');
		$t='C';
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
		$this->load->view('clientes/acancelar',$data);
	
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
			 //Controlamos los post.
			if(strlen($obs)>30){
				$rta=array("ok"=>"1",	
				    		"mensaje"=>"Las observaciones son muy largas");
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
			$aux[]='C';
			$this->cuentacorriente_model->guardopagocliente($aux);
		echo json_encode($rta);
	  	return;					
		}
	public function pagar(){
		//monto,cliente,
		//primero verifico que el monto sea Valido.
		$this->load->model('cuentacorriente_model');
		$aux["monto"]=$_POST["monto"];
		$aux["deuda"]=$_POST["deuda"];
		$fecha=$_POST["fecha"];
		list($anio,$mes,$dia)=explode("-",$fecha);
		$aux["fecha"]=$dia."/".$mes."/".$anio;
		$aux["idcliente"]=$_POST["idcliente"];
		$aux["tipo"]='C';
		$aux["comentario"]=$_POST["comentario"];
		$this->cuentacorriente_model->guardopagoclientetotal($aux);
		$this->output->set_header();
		redirect(base_url("/clientes/cuentacorriente/".$aux["idcliente"]));	
	}
	public function validarpago($monto=0,$deuda=0,$fecha=0){
		//monto,cliente,
		//primero verifico que el monto sea Valido.		
		//print_r($_POST);die;
		$monto=$_POST["monto"];
		$fecha=$_POST["fecha"];
		list($anio,$mes,$dia)=explode("-",$fecha);
		$fecha=$dia."/" .$mes. "/". $anio;
		$deuda=$_POST["deuda"];
		if(is_numeric($monto)){						
			if(is_numeric($deuda)){ // and $deuda < 0 			 2020 6  6  antes estaba asi 			
				$deuda=abs($deuda);
				//$deuda >= $monto and $deuda > 0 and   2020 6  6  antes estaba asi 
				//osea se puede dejar plata a favor				
				if( $monto>0){									
						if($this->es_fecha($fecha))
						{
							$rta=array("ok"=>0,"mensaje"=>"");
						}
					else{
							$rta=array("ok"=>1,"mensaje"=>"la Fecha no es válida");
						}
				}
				else{
					$rta=array("ok"=>1,"mensaje"=>"Los importes son incorrectos");
				}			
			}
			else
			{
				$rta=array("ok"=>1,"mensaje"=>"La deuda no es un importe valido");
			}	
		}
		else
			{
				$rta=array("ok"=>1,"mensaje"=>"El importe a cancelar no es valido");
			}
			
		echo json_encode($rta);
	  	return;				
	}	
}
?>
