<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cierre_caja extends CI_Controller {

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

    public function verificar(){
				$this->load->model('cierre_de_caja_model');
				$estado=$this->cierre_de_caja_model->verificar();
				if($estado["estado"]==1){
						redirect('cierre_caja/cierre/'.$estado["id"]);
				}
				elseif($estado["estado"]==2){
					//hay que cerrar la caja
					redirect('cierre_caja/cierre/'.$estado["id"]);
				}
				elseif($estado["estado"]==3){
					//hay que crear una apertura
					redirect('cierre_caja/cierre/0');
				}
				else{
					die("NO HAY ESTADOS ;( ");
				}
	
	}
	
	public function cierre($id=0){
	//$id=0 abrir nueva
	$mensaje="C I E R R E";
	$readonly='readonly="readonly"';
	$readonly01='readonly="readonly"';
	$this->load->model('cierre_de_caja_model');
	$this->load->model('movimientos_caja_model');
	 $this->load->library('funciones');
	if($this->input->post('fecha_apertura')!==null){
				$this->load->helper(array('form', 'url'));
                $this->load->library('form_validation');
               
				$item=new $this->cierre_de_caja_model;
				$item->fecha_apertura=$this->funciones->fechaToDb($this->input->post('fecha_apertura'));
				if($this->input->post('fecha_cierre')!='')
						$item->fecha_cierre=$this->funciones->fechaToDb($this->input->post('fecha_cierre'));
				$item->monto_inicial=$this->input->post('monto_inicial');
				$item->monto_final=$this->input->post('monto_final');	
				$item->cheques=$this->input->post('cheques');	
				$item->id=$this->input->post('id');	
				$item->observaciones=$this->input->post('observaciones');	
				$item->id=$this->input->post('id');	
                if ($this->form_validation->run() == True)
                {
					
					if($item->id > 0)
						$this->cierre_de_caja_model->actualizar($item,$this->input->post('continuar'));
					else
							$this->cierre_de_caja_model->agregar($item);
						
					//seguir cargando
					redirect("cierre_caja/listar");
				}
	
	
	
	}
	if($id==0){
		$mensaje="A P E R T U R A";
		$cierre=new stdClass;
		$cierre->id=$id; 
		$cierre->fecha_apertura=date('d/m/Y');
		$cierre->fecha_cierre='';
		$cierre->monto_inicial='0.00';
		$cierre->monto_final='0.00';
		$cierre->obervaciones='';
		$cierre->cheques='';
		$cierre->observaciones='';
		}
	else{
		$readonly01="";
		
		$cierre=$this->cierre_de_caja_model->buscar($id);
		$cierre->fecha_cierre=date('d/m/Y');
		$cierre->fecha_apertura=$this->funciones->DbTofecha($cierre->fecha_apertura);
	}
	
	
	
	
	
	$data=array(			
			'cierre'=>$cierre,
			'mensaje'=>$mensaje,
			'readonly'=>$readonly,
			'readonly01'=>$readonly01,
		);
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('cierre_caja/cierre_caja.php',$data);
	}	
 public function listar($id=0){
		$this->load->model('movimientos_caja_model');
		$this->load->model('cierre_de_caja_model');
		if($id==0){
		$cierres=$this->cierre_de_caja_model->listar();	
			$data=array(			
			'cierres'=>$cierres,
		);
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('cierre_caja/listar.php',$data);
		}
	else{
			
			$cierre=$this->movimientos_caja_model->listar_una_caja($id);
			
			//print_r($cierre);die;
			$data=array("cierre"=>$cierre["cierre"],'movimientos'=>$cierre["movimientos"]);
			$this->load->view('cierre_caja/imprimir.php',$data);				
		}
	}
public function control($id,$id_caja=0){
			if($id==2){
				$data["mensaje"]="La Caja Esta Sin Cerrar.! No Podra Seguir Hasta Que la Cierre";
			}
			elseif($id==3){
					$data["mensaje"]="Debe Crear Una Caja Nueva Para Seguir";
			}
				
				$data["volver"]="cierre_caja/cierre/".$id_caja;
		            $this->load->view('encabezado.php',$data);
				    $this->load->view('menu.php',$data);
                    $this->load->view('error/mensaje',$data);
 	
	}	
}
?>