<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Movimientos_caja extends CI_Controller {

	public $aux_valor;
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
	public function borrar($id){
			$this->load->model('movimientos_caja_model');
			$this->load->library('funciones');
			if($this->movimientos_caja_model->borrar($id)){
					redirect("movimientos_caja/listar");
					exit;
		}
		else{
				$data=array("mensaje"=>" El Item que quiere borrar esta en una caja inexistente o cerrada ",
				"volver"=>"movimientos_caja/listar");
		                    $this->load->view('encabezado.php',$data);
				    $this->load->view('menu.php',$data);
                    $this->load->view('error/mensaje',$data);
                    return 0;
		}
	
	}
    public function listar()
	{
		$this->load->model('movimientos_caja_model');
		$this->load->library('funciones');
				$desde=$this->input->post('desde');
				$hasta=$this->input->post('hasta');
				if($desde=="")
						$desde=date('d/m/Y');
				if($hasta=="")
						$hasta=date('d/m/Y');
				$desdeDB=$this->funciones->fechaToDb($desde);
				$hastaDB=$this->funciones->fechaToDb($hasta);		
		$data["items"]=$this->movimientos_caja_model->listar($desdeDB,$hastaDB);
		$data["desde"]=$desde;
		$data["hasta"]=$hasta;
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('caja_movimientos/listar',$data);


	}
	public function editar()
	{
		$this->load->model('movimientos_caja_model');
		$this->load->model('cierre_de_caja_model');
		$cd=$this->movimientos_caja_model->conceptos();
		$item= new $this->movimientos_caja_model;
		$cierre=new $this->cierre_de_caja_model;
		$ultima=$cierre->verificar();
		$mensaje="";
		$advertencia="";
		if($ultima["estado"]==2 or $ultima["estado"]==1){
			$item->cierre_caja_id=$ultima["id"];
			if($ultima["estado"]==2)
				$advertencia="ESTA TRABAJANDO CON UNA FECHA QUE DEBERIA ESTAR CERRADA!! RECUERDE CERRARLA!!";
		}
		else{
			 $data=array("mensaje"=>" LA CAJA ESTA CERRADA DEBE ABRIRLA ",
				"volver"=>"cierre_caja/cierre/0");
		                    $this->load->view('encabezado.php',$data);
				    $this->load->view('menu.php',$data);
                    $this->load->view('error/mensaje',$data);
                    return 0;
		
		}
		if($this->input->post('concepto_id')!==null){
				$this->load->helper(array('form', 'url'));
                $this->load->library('form_validation');
                $this->load->library('funciones');
				$item->fecha=date('Y-m-d');
				
				$item->concepto_id=$this->input->post('concepto_id');
				$item->monto=$this->input->post('monto');	
				$item->descripcion=$this->input->post('descripcion');	
				$item->tipo=$this->input->post('tipo');	
                if ($this->form_validation->run() == True)
                {
					$mensaje="Se Cargo El Item.";
					$this->movimientos_caja_model->agregar($item);
					//seguir cargando
				}
				
		}
		$data=array('cd'=>$cd,
		'item'=>$item,
		'mensaje'=>$mensaje,
		'tipo'=>array('D'=>'Ingreso','H'=>'Egreso'),
		'advertencia'=>$advertencia,
		);
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('caja_movimientos/movimiento',$data);
	}
}
?>
