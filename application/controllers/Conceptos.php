<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Conceptos extends CI_Controller {

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
 
 
	public function listar()
	{
		$data["mensaje"]="";
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('alumnos/lista',$data);
		

	}
	public function buscar()
	{
		$p=$this->input->post('buscar');
		$this->load->model('conceptos_model');
		$data["conceptos"]=$this->conceptos_model->buscar($p);
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('conceptos/lista',$data);


	}
	public function listado()
	{
		$this->load->model('conceptos_model');
		$data["conceptos"]=$this->conceptos_model->listado();
		
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('conceptos/lista',$data);


	}
		public function editar($p='')
	{
		$this->load->model('conceptos_model');
		
		if($p>0){
			
			$rta=$this->conceptos_model->editar($p);
			$data["concepto"]=$rta[0];
		}	
		if($p=='' or $this->input->post('id')!==null or count($rta)==0){
			$aux= new stdClass;
			$aux->id=$this->input->post('id');
			$aux->nombre=$this->input->post('nombre');
			$aux->monto=$this->input->post('monto');

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
					$this->conceptos_model->guardar($data);
					redirect('conceptos/listado');
					exit;
				}
				
		}
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('conceptos/editar',$data);
		

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
public function conceptosalumno($nombre=''){
		$this->load->model('conceptos_model');
		$nombre=$this->input->post('buscar');
		$rta=$this->conceptos_model->listarconceptosalumno($nombre);	
		$data["alumnos"]=$rta;
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('conceptos/alumnosconceptos',$data);
	
	}
public function listarconalu($id=''){ 
		$this->load->model('conceptos_model'); 
		$rta=$this->conceptos_model->conceptos($id);	
		$data["conceptos"]=$rta;
		$rta=$this->conceptos_model->alumno($id);
		$data["alumno"]=$rta[0];
		$rta=$this->conceptos_model->conceptosParaAgregar($id);
		$data["conceptosDisponibles"]=$rta;
		$data["haymas"]=count($rta);
		//var_dump($data);die;
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('conceptos/listarconalu',$data);
	
	}
 public function agregarnuevoconcepto($id){
		$this->load->model('conceptos_model');
		$this->load->model('alumnos_conceptos_model');  
		$conceptosDisponibles=$this->conceptos_model->conceptosParaAgregar($id);
		$alu=$this->conceptos_model->alumno($id);
		$concepto=new $this->alumnos_conceptos_model; 				
		$concepto->desde=date('d/m/Y');
		$concepto->hasta=date('d/m/Y');
		$concepto->id_alumno=$id;
		if($this->input->post('id')!==null){
				$this->load->helper(array('form', 'url'));
                $this->load->library('form_validation');
                $this->load->library('funciones');
                $concepto->desde=$this->input->post('desde');
				$concepto->hasta=$this->input->post('hasta');
				$concepto->id_concepto=$this->input->post('id_concepto');				
                if ($this->form_validation->run() == True)
                {
					$concepto->desde=$this->funciones->fechaToDb($this->input->post('desde'));
					$concepto->hasta=$this->funciones->fechaToDb($this->input->post('hasta'));
					$this->alumnos_conceptos_model->guardar($concepto);
					redirect('conceptos/listarconalu/'.$id);
					exit;
				}
				
		}
		
		
		$data=array(
			'cd'=>$conceptosDisponibles,
			'alumno'=>$alu[0],
			'concepto'=>$concepto
		);
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('conceptos/editarconalu',$data);
	 
	 }
	public function eliminarlistaalu($id_alumno,$id){
			$this->load->model('alumnos_conceptos_model');  	
			if($id>0){
				$this->alumnos_conceptos_model->eliminar($id);
			     redirect('conceptos/listarconalu/'.$id_alumno);
			     exit;
			}
					
		} 		
}
?>
