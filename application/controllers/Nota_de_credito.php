<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nota_de_credito extends CI_Controller {

	public function __construct(){

		      parent::__construct();
			if(!isset($this->session->usuario)){
				redirect('salir');
				exit;
		}



	}
	public function nc($id){
            //LA NOTA DE CREDITO ES SOLAMENTE POR ANULACION DE COMPROBANTE
            //NO POR DEVOLUCION
            //$id del recibo a anular  cc_pago.id
			//1º Tenemos que saber cual es el recibo a anular Se pedira el Nro de Recibo
            //Verificamos que el recibo no este ya anulado.
            //Preguntamos si . devuelve el dinero y tambien si en caso de quedar la deuda en cero borramos...
            //Paso1 Verificar que ya no se haya anulado.   tipo_comp=13
           	$this->load->model('nota_de_credito_model');
            $model=New $this->nota_de_credito_model;
            $rta=$model->verificar_anulado($id);
            if(count($rta)>0){
                    $mensaje=$rta[1];
                    $volver="alumnos_cc/listar_facturas";
                    $data=array('mensaje'=>$mensaje,'volver'=>$volver);
                    $this->load->view('encabezado.php',$data);
				    $this->load->view('menu.php',$data);
                    $this->load->view('error/mensaje',$data);
                    return 0;
            }

            //2º Tomar el total del recibo y Mandarlo a AFIP como nota de credido
            //AFIPPPP  INICIO
                        //RECIBO ELECTRONICO VA ACA....

        $contador=0;
        while($contador<=3){
        $model->refrescar_nc($model->nc->id);
        $factura=$model->nc;
    	$id_pago=$factura->id;
        //print_r($factura);die;
        if($factura->cae=="" or $factura->barranro==""){
		if($factura->cae==""){
			$d0=shell_exec("c:/appserv/php5/php.exe c:/appserv/www/facturaelectronica/index.php ".$id_pago ." ");
			sleep(2);
			if(trim($d0)!=""){
            $model->borrar();
            die("Hubo un Error Al Generar Los Ticket de Acceso \n\r".$d0);
			}
		}
		if($factura->barranro==""){
			$d1=shell_exec("c:/appserv/php5/php.exe c:/appserv/www/facturaelectronica/ArchivosyCodBar.php ".$id_pago ." ");
			sleep(2);
			if(trim($d1)!=""){
                    $model->borrar();
                    die("Hubo un Error Al Generar La F.E. \n\r".$d1);
					}
     }
        $contador++;
       }
    else
       { $contador=55;}
     }
     if($contador==4){
        $model->borrar();
		die($contador."<pre>se ha intentado enviar tres veces la factura , vuelva a intentarlo mas tarde, posible error
		".$factura->errores."</pre>");
      }
			//return $this->redirect($this->generateUrl('suma_ines_factura_re',array("id"=>$id)));

            if($contador<>55){
            $model->borrar();
			die("<pre>Error al Generar el Comprobante en Afip: $d0</pre>No se Puedo Generar la Comprobante vuelva a intentarlo</pre>
			<pre>Error al generar Los Codigos de Barras y Archivos: $d1</pre>No se Puedo Generar la Factura electronica vuelva a intentarlo</pre>");
		}
                   //FIN ELECTRONICO
            //AFIP FIN
            //3º Guardar el Recibo en la tabla
         //listo en el paso anterior

            //4º Tomar los items del recibo a anular Sumarlo al Pendiente y Restarlo del Capital.
            $model->anular($factura->id_anula);



            //5º Si no se devuelve el dinero no inngresar nada en medios pagos. Si se devuelve el dinero ingresar
            //el valor en medios pagos.



            //Mostrar La Nota de Credito.
             redirect("alumnos_cc/listar_facturas");
		}

}
