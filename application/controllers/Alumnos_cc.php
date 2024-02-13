<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alumnos_cc extends CI_Controller {

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
 
    public function deuda($id,$mensaje='')
    {
				
				
		$this->load->model('conceptos_model');
		$this->load->model('alumnos_conceptos_model');
		$this->load->model('alumnos_cc_model');  
		$conceptosDisponibles=$this->conceptos_model->conceptosDisponibles($id);
		if(count($conceptosDisponibles)==0){
			 	redirect('alumnos_cc/sinconceptos/'.$id);
				exit;
			
			}
		$alu=$this->conceptos_model->alumno($id);
		$deuda=new $this->alumnos_cc_model; 				
		$deuda->id_alumno=$id;
		$deuda->fecha_vto=date('d/m/Y');
		$deuda->periodo=date('Y-m');
		if($this->input->post('id')!==null){
				$this->load->helper(array('form', 'url'));
                $this->load->library('form_validation');
                $this->load->library('funciones');
                $deuda->fecha_vto=$this->input->post('fecha_vto');        
				$deuda->id_concepto=$this->input->post('id_concepto');
				$deuda->original=$this->input->post('importe');	
				$deuda->periodo=$this->input->post('periodo');	
				$deuda->pendiente=$deuda->original;
				$deuda->capital='0.00';
                if ($this->form_validation->run() == True)
                {
					$deuda->fecha_vto=$this->funciones->fechaToDb($this->input->post('fecha_vto'));
					//tenemos que chequear que no exista alumno - concepto - periodo en la dDB
					$rta=$this->alumnos_cc_model->controlar($deuda);
					if($rta==0){
					$this->alumnos_cc_model->guardar($deuda);
					//seguir cargando
					$mensaje=0;
					}
					else{
					$mensaje=1;
					}
					redirect('alumnos_cc/deuda/'.$id.'/'.$mensaje);
					exit;
				}
				
		}
		
		
		$data=array(
			'cd'=>$conceptosDisponibles,
			'alumno'=>$alu[0],
			'deuda'=>$deuda,
			'mensaje'=>$mensaje
		);
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('alumnos_cc/deuda',$data);
		
		
		
	}
  public function importe($id){
			$this->load->model('conceptos_model');
			$rta=$this->conceptos_model->importe($id);
			die($rta[0]->monto);
	  }
	
 public function es_fecha($fecha){
	$this->load->library('funciones');
	return $this->funciones->fecha_nacimiento($fecha);
}
  public function cuentacorriente($id){
		$this->load->model('conceptos_model');
		$this->load->model('alumnos_conceptos_model');
		$this->load->model('alumnos_cc_model');  
	    $alu=$this->conceptos_model->alumno($id);
	    $deuda=$this->alumnos_cc_model->cc($id);  
			$data=array(
			'alu'=>$alu[0],
			'cc'=>$deuda
		);
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('alumnos_cc/cuenta_corriente',$data);
		
	  
	  
	  }

	public function a_cancelar($id,$interes=1,$id_pago=0){
		
		
				$this->load->model('conceptos_model');
				$this->load->model('alumnos_conceptos_model');
				$this->load->model('alumnos_cc_model');  
				$alu=$this->conceptos_model->alumno($id);
/* Para Facturas Mayoes a 1000 peso debe haber un DNI en la factura */ 

                if(!is_numeric($alu[0]->dni) or $alu[0]->dni < 99999   or strlen($alu[0]->dni)<7 or strlen($alu[0]->dni)>11){
                                 $mensaje=$alu[0]->apellido .",". $alu[0]->nombre ."El Dni debe ser un numero Sino no se puede Generar el Comprobante";
                                 $volver="/alumnos/editar/".$id;
                                 $data=array('mensaje'=>$mensaje,'volver'=>$volver);
                                 $this->load->view('encabezado.php',$data);
				                 $this->load->view('menu.php',$data);
                                 $this->load->view('error/mensaje',$data);
                                 return 0;
                }
				if($this->alumnos_cc_model->verificar_factura()===false){
								$mensaje="La Ultima Factura Registrada No Coincide Con le Siguiente Numero";
                                 $volver="/alumnos/listado/".$id;
                                 $data=array('mensaje'=>$mensaje,'volver'=>$volver);
                                 $this->load->view('encabezado.php',$data);
				                 $this->load->view('menu.php',$data);
                                 $this->load->view('error/mensaje',$data);
                                 return 0;			
				
				}
//Verificar que el ultimo numero de factura sea Igual al Ultimo numero de comprobante del FActurante				
			    	
//Vamos a Controlar Tambien antes de generar un recibo que no haya algo pendiente de cobrar /
//Seguir Aca!!
				$ultimo=$this->alumnos_cc_model->traer_ultimo_ccpago();
				//print_r($ultimo);die;
				if(count($ultimo)>0){
					$ultimo=$ultimo[0];
					if($ultimo->errores<>''){
								 $mensaje="Hubo un Error Al enviar el Comprobante de " .
								 $ultimo->apellido .",". $ultimo->nombre ." La Afip Devolvió " . $ultimo->errores;
                                 $volver="/alumnos_cc/borrar_pago/".$ultimo->id."/".$ultimo->id_alumno;
                                 $data=array('mensaje'=>$mensaje,'volver'=>$volver);
                                 $this->load->view('encabezado.php',$data);
				                 $this->load->view('menu.php',$data);
                                 $this->load->view('error/mensaje',$data);
                                 return 0;
					}
				  else{
								 $mensaje="Hubo un Error Al enviar el Comprobante de " .
								 $ultimo->apellido .",". $ultimo->nombre .
								 " Puede Que sea Por un Error de Comunicacion O que Algun Usuario Este Enviando un Comprobante en este momento.
								 Si Esta Seguro que no hay otro Usuario Esperando Respuesta de la Afip Entonces Siga. Sino Aguarde que El otro Usuario Termine.
								 "; 
                                 $volver="/alumnos_cc/borrar_pago/".$ultimo->id."/".$ultimo->id_alumno;
                                 $data=array('mensaje'=>$mensaje,'volver'=>$volver);
                                 $this->load->view('encabezado.php',$data);
				                 $this->load->view('menu.php',$data);
                                 $this->load->view('error/mensaje',$data);
                                 return 0;	
				  
				  
				  
				  }	
				}
				//
				$deuda=$this->alumnos_cc_model->a_cancelar($id,$interes);  
				//die("dd".$this->input->post('id'))	;			
				if($this->input->post('id')!==null){
				$this->load->helper(array('form', 'url'));
                $this->load->library('form_validation');
                $importes=$this->input->post('a_cancelar');             
                foreach($importes as $k => $v)
					{
						$this->form_validation->set_rules('a_cancelar['.$k.'][importe]', 'Monto a Cancelar', 'required|numeric|callback_montoCancela['.$v['debe'].']',array('required'=>'Debe Ingresar un Valor','numeric'=>'Debe ingresar un valor Numerico','montoCancela'=>'El importe a cancelar debe ser mayo a cero y menor o igual '.$v['debe']));
						//$this->aux_valor=0.00;
					}  	
                if ($this->form_validation->run() == True)
                {
					$deuda["importes"]=$importes;
					$deuda["id_alumno"]=$id;
					$id_pago=$this->alumnos_cc_model->guardar_pago($deuda);

                    //RECIBO ELECTRONICO VA ACA....
        if($id_pago>0)
        {
		
       $contador=0;
        while($contador<=3){
    	$factura=$this->alumnos_cc_model->cc_pago($id_pago);
    	if($factura->cae=="" or $factura->barranro==""){
		if($factura->cae==""){
			$d0=shell_exec("c:/appserv/php5/php.exe c:/appserv/www/facturaelectronica/index.php ".$id_pago ." ");
			sleep(2);
			if(trim($d0)!=""){
			$factura=$this->alumnos_cc_model->borrarpago($id_pago);
			die("Hubo un Error Al Generar Los Ticket de Acceso \n\r".$d0);
			
			}
		}
		if($factura->barranro==""){
			$d1=shell_exec("c:/appserv/php5/php.exe c:/appserv/www/facturaelectronica/ArchivosyCodBar.php ".$id_pago ." ");
			sleep(2);
			if(trim($d1)!=""){
				$factura=$this->alumnos_cc_model->borrarpago($id_pago);				
				die("Hubo un Error Al Generar La F.E. \n\r".$d1);
				
					}
     }
        $contador++;
       }
    else
       { $contador=55;}
     }
     if($contador==4){
		$factura=$this->alumnos_cc_model->borrarpago($id_pago);
		die($contador."<pre>se ha intentado enviar tres veces la factura , vuelva a intentarlo mas tarde, posible error
		".$factura->errores."</pre>");
      }
			//return $this->redirect($this->generateUrl('suma_ines_factura_re',array("id"=>$id)));

            if($contador<>55){
			$factura=$this->alumnos_cc_model->borrarpago($id_pago);
			die("<pre>Error al Generar el Comprobante en Afip: $d0</pre>No se Puedo Generar la Comprobante vuelva a intentarlo</pre>
			<pre>Error al generar Los Codigos de Barras y Archivos: $d1</pre>No se Puedo Generar la Factura electronica vuelva a intentarlo</pre>");
		}
    
	}


                    

                    
                    //FIN ELECTRONICO
                    if($id_pago>0){
						$id_pago=$this->alumnos_cc_model->guardar_pago($deuda,$id_pago);
						redirect('alumnos_medios_pagos/agregar/'.$id_pago);
						exit;
						}
					else
						redirect('alumnos_cc/cuentacorriente/'.$id);	
					exit;
				}
				
			}
				
				
				
			$data=array(
			'alu'=>$alu[0],
			'cc'=>$deuda
		);
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('alumnos_cc/a_cancelar',$data);
		
		
		
		
		}
		
	public function recibo($id=''){
			$this->load->model('alumnos_medios_pagos_model'); 
			$this->load->model('alumnos_cc_model'); 
			//traigo los datos del alumnos y el importe por el pago ingresado
			$recibo=$this->alumnos_medios_pagos_model->alumno_curso($id);	
			$items=$this->alumnos_medios_pagos_model->recibo_items($id);	
			$data=array(
			'recibo'=>$recibo,
			'items'=>$items
			);
			$this->load->view('alumnos_cc/recibo.php',$data);

	}
	 public function periodo($p){
		$this->load->library('funciones');
		return $this->funciones->periodo($p);
}
	public function montoCancela($p,$ori){
			return ($p<=$ori and $p>=0);
		
		}
	public function sinconceptos($id){
				$this->load->model('conceptos_model');
				$alu=$this->conceptos_model->alumno($id);
				$mensaje="El Alumno " . $alu[0]->apellido . ", " . $alu[0]->nombre . " No tiene cargado conceptos";
				$volver="conceptos/listarconalu/".$id;
				$data=array('mensaje'=>$mensaje,'volver'=>$volver);		
				$this->load->view('encabezado.php',$data);
				$this->load->view('menu.php',$data);
				$this->load->view('error/mensaje',$data);
		}
	public function listar_facturas($imprime=0){
				$this->load->model('alumnos_cc_model');
				$this->load->library('funciones');
				$desde=$this->input->post('desde');
				$hasta=$this->input->post('hasta');
				$imprime=$this->input->post('imprimir');
				if($desde=="")
						$desde=date('d/m/Y');
				if($hasta=="")
						$hasta=date('d/m/Y');
				$desdeDB=$this->funciones->fechaToDb($desde);
				$hastaDB=$this->funciones->fechaToDb($hasta);		
				$facturas=$this->alumnos_cc_model->listar_facturas($desdeDB,$hastaDB);				
				$data=array('facturas'=>$facturas,'desde'=>$desde,'hasta'=>$hasta);		
				if($imprime==0)
				{
				$this->load->view('encabezado.php',$data);
				$this->load->view('menu.php',$data);
				$this->load->view('alumnos_cc/listar-facturas.php',$data);
				}
				else{
						
						$this->load->view('alumnos_cc/listar-facturas-imprimir.php',$data);
						
				}
	
	}	
	public function listar_ingresos($fecha=''){
				$this->load->model('alumnos_cc_model');
				$this->load->library('funciones');
				$desde=$this->input->post('desde');
				$hasta=$this->input->post('hasta');
				if($desde=="")
						$desde=date('d/m/Y');
				if($hasta=="")
						$hasta=date('d/m/Y');
				$desdeDB=$this->funciones->fechaToDb($desde);
				$hastaDB=$this->funciones->fechaToDb($hasta);		
				$ingresos=$this->alumnos_cc_model->listar_ingresos($desdeDB,$hastaDB);
				
				$data=array('ingresos'=>$ingresos,'desde'=>$desde,'hasta'=>$hasta);		
				$this->load->view('encabezado.php',$data);
				$this->load->view('menu.php',$data);
				$this->load->view('alumnos_cc/listar-ingresos.php',$data);
	
	
	}	
	
	public function borradeuda($id){
				$this->load->model('alumnos_cc_model');
				$ingresos=$this->alumnos_cc_model->borradeuda($id);
				if($ingresos->capital==0){
						redirect('alumnos_cc/a_cancelar/'.$ingresos->id_alumno);	
						exit;
				}
				else
				{
				$this->load->model('conceptos_model');
				$mensaje="La Deuda que intenta Borrar Tiene Pagos Asociado..debe eliminar los pagos antes de seguir.";
				$volver="alumnos_cc/a_cancelar/".$ingresos->id_alumno;
				$data=array('mensaje'=>$mensaje,'volver'=>$volver);		
				$this->load->view('encabezado.php',$data);
				$this->load->view('menu.php',$data);
				$this->load->view('error/mensaje',$data);
				}
				
	
	
	}
	public function borrar_pago($id_pago,$id_alumno){
					$this->load->model('alumnos_cc_model');
				$this->alumnos_cc_model->borrarpago($id_pago);
				redirect('alumnos_cc/a_cancelar/'.$id_alumno);	
				exit;
	
	}	
}
?>
