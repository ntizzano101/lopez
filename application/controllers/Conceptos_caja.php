<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Conceptos_caja extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	/*public function __construct()
	{
		if($this->session->usuario=""){
				redirect('login');
				exit;
		}		
	  
				
	}
		*/
	public function __construct(){
		
		      parent::__construct();
			if(!isset($this->session->usuario)){
				redirect('salir');
				exit;
		}		
		
		
	}	
 
 
	public function eliminar($id)
	{
		
		$this->load->model('conceptos_caja_model');
		$rta=$this->conceptos_caja_model->eliminar($id);
		if($rta=='OK'){
		$data["mensaje"]="";
		$data["conceptos"]=$this->conceptos_caja_model->listado();
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('conceptos_caja/lista',$data);
		}
		else{
				$mensaje="No Se puede Eliminar El Concepto Porque Hay Registros Relacionados";
                $volver="/conceptos_caja/listado";
                                 $data=array('mensaje'=>$mensaje,'volver'=>$volver);
                                 $this->load->view('encabezado.php',$data);
				                 $this->load->view('menu.php',$data);
                                 $this->load->view('error/mensaje',$data);
		
		
		}

	}
	public function buscar()
	{
		$p=$this->input->post('buscar');
		$this->load->model('conceptos_caja_model');
		$data["conceptos"]=$this->conceptos_caja_model->buscar($p);
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('conceptos_caja/lista',$data);


	}
	public function listado()
	{
		$this->load->model('conceptos_caja_model');
		$data["conceptos"]=$this->conceptos_caja_model->listado();
		
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('conceptos_caja/lista',$data);


	}
		public function editar($p='')
	{
		$this->load->model('conceptos_caja_model');
		
		if($p>0){
			
			$rta=$this->conceptos_caja_model->editar($p);
			$data["concepto"]=$rta[0];
		}	
		if($p=='' or $this->input->post('id')!==null or count($rta)==0){
			$aux= new stdClass;
			$aux->id=$this->input->post('id');
			$aux->nombre=$this->input->post('nombre');

			$data["concepto"]=$aux;
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
					$this->conceptos_caja_model->guardar($data);
					redirect('conceptos_caja/listado');
					exit;
				}
				
		}
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('conceptos_caja/editar',$data);
		

	}
	
/*	public function ingreso()
	{
		$usuario=$this->input->post('usuario');
		$pass=$this->input->post('password');
		$this->load->model('login_model');
		$usu=$this->login_model->ingreso($usuario,$pass);
		if($usu){
				redirect('pincipal');
		}
		else{
			$data["mensaje"]="Usuario o Password Incorrecto";
			$this->load->view('login/login.php',$data);
			}
	}*/
 public function es_fecha($fecha){
	$this->load->library('funciones');
	return $this->funciones->fecha_nacimiento($fecha);
}

}
?>
