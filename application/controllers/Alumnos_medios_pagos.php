<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alumnos_medios_pagos extends CI_Controller {

	public function __construct(){
		
		      parent::__construct();
			if(!isset($this->session->usuario)){
				redirect('salir');
				exit;
		}		
		
		
	}
	public function listar_pagos($id){
			//$id id_pago
			
			
		
		}
	public function agregar($id){
			//$id id_pago
		$this->load->model('alumnos_medios_pagos_model'); 
		$this->load->model('alumnos_cc_model'); 
		$medio=new $this->alumnos_medios_pagos_model;
		//seteo para el nuevo pago el id_pago
		$medio->id_pago=$id;
		//traigo los datos del alumnos y el importe por el pago ingresado
		$alumno=$this->alumnos_medios_pagos_model->alumno($id);				
		$medio->fecha_acreditacion=date('d/m/Y');
		//Consulto cuando se ingreso ya del pago
		$pagado=$this->alumnos_medios_pagos_model->pagos_ingresados($id);	
		//lo a cancelar que sea por defecto el total de lo que falte ingresar	
		$resto=round($alumno->importe-$pagado->importe,2);
		$medio->importe=$resto;
		if($this->input->post('importe')!==null){
				$this->load->helper(array('form', 'url'));
                $this->load->library('form_validation');
                $this->load->library('funciones');
                $medio->fecha_acreditacion=$this->input->post('fecha_acreditacion');
                $medio->cuenta=$this->input->post('cuenta');
                $medio->banco=$this->input->post('banco');
                $medio->nro_cheque=$this->input->post('nro_cheque');
                $medio->nro_deposito=$this->input->post('nro_deposito');
				$medio->fecha_vencimiento=$this->input->post('fecha_vencimiento');
				$medio->cupon_tarjeta=$this->input->post('cupon_tarjeta');
				$medio->tipo_pago=$this->input->post('tipo_pago');
				$medio->importe=$this->input->post('importe');
				$this->form_validation->set_rules('importe', 'Monto a Cancelar', 'required|numeric|callback_montoCancela['.$resto.']',array('required'=>'Debe Ingresar un Valor','numeric'=>'Debe ingresar un valor Numerico','montoCancela'=>'El importe a cancelar debe ser  menor o igual '.$resto));
                if($this->form_validation->run() == True)
                {
					$medio->fecha_acreditacion=$this->funciones->fechaToDb($this->input->post('fecha_acreditacion'));
					$medio->fecha_vencimiento=$this->funciones->fechaToDb($this->input->post('fecha_vencimiento'));
					$rta=$this->alumnos_medios_pagos_model->guardar($medio);
					//respuesta es el total ingresado por medio de pago al momento
					// sigue hasta llegar al total.

					if($rta->importe < $alumno->importe)
						redirect('alumnos_medios_pagos/agregar/'.$id);
					else
						redirect('alumnos_medios_pagos/listar/'.$id);	
					exit;
				}
				
		}
		
		
		$data=array(
			
			'pago'=>$medio,
			'alumno'=>$alumno,
			'id'=>0,
			'pagado'=>$pagado->importe,
			'total'=>$alumno->importe,
			'resto'=>$resto
		);
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('alumnos_medios_pagos/pagos.php',$data);
		
	
			
		
		}
		public function montoCancela($p,$ori){
			return ($p<=$ori and $p>=0);
		
		}
		
		public function listar($id){
				//muestra lo ingresado para un comprobante de pago
				//medios de pagos	
				$this->load->model('alumnos_medios_pagos_model'); 
				$this->load->model('alumnos_cc_model'); 
				$medio=new $this->alumnos_medios_pagos_model;
				$data=$medio->alumnos_medios_pagos_model->ver_medios($id);

			$this->load->view('encabezado.php',$data);
			$this->load->view('menu.php',$data);
			$this->load->view('alumnos_medios_pagos/ingresados.php',$data);
			
		}
}		
