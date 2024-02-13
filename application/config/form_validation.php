<?php
$config = array(
	'clientes/editar'=>
	 array(
			array(
					'field' => 'nombre',
					'label' => 'Nombre',
					'rules' => 'required',
					'errors'=>array('required'=>'Debe Ingresar Nombre')
			),
	 ),		
		'proveedores/editar'=>
	 array(
			array(
					'field' => 'nombre',
					'label' => 'Nombre',
					'rules' => 'required',
					'errors'=>array('required'=>'Debe Ingresar Nombre')
			)
	),			
			/*	array(
					'field' => 'apellido',
					'label' => 'Apellido',
					'rules' => 'required',
					'errors'=>array('required'=>'Debe Ingresar Apellido')
			),
			array(
					'field' => 'direccion',
					'label' => 'Direccion',
					'rules' => 'required',
					 'errors'=>array('required'=>'Debe Ingresar Direccion')
					
			),
			array(
					'field' => 'telefono',
					'label' => 'Telefono',
					'rules' => 'required',
					'errors'=>array('required'=>'Debe Ingresar Telefono')
			),
			array(
					'field' => 'dni',
					'label' => 'Dni',
					'rules' => 'required|exact_length[8]|numeric',
					 'errors'=>array('required'=>'Debe Ingresar Dni','exact_length'=>'El Dni Debe Tener 8 Digitos','numeric'=>'Solo Numeros en el DNI')
			),
			array(
					'field' => 'fechanacimiento',
					'label' => 'Fecha Nacimiento',
					'rules' => 'callback_es_fecha',
					'errors'=>array('es_fecha'=>'Fecha Nacimiento Incorrecta')
			),
			array(
					'field' => 'fechaingreso',
					'label' => 'Fecha Ingreso',
					'rules' => 'callback_es_fecha',
					'errors'=>array('es_fecha'=>'Fecha Ingreso Incorrecta')
			)*/
	
   'conceptos/editar'=>
        array(
				array(
						'field' => 'nombre',
						'label' => 'Nombre',
						'rules' => 'required',
						'errors'=>array('required'=>'Debe Ingresar Nombre')
				),
				array(
						'field' => 'monto',
						'label' => 'Monto',
						'rules' => 'required|decimal',
						'errors'=>array('required'=>'Debe Ingresar Monto','decimal'=>'Debe Usar . para los decimales')
				),
	),
	'conceptos/agregarnuevoconcepto'=>
        array(
				array(
						'field' => 'desde',
						'label' => 'Fecha Desde',
						'rules' => 'required|callback_es_fecha',
						'errors'=>array('required'=>'Debe ingresar Fecha Desde','es_fecha'=>'Fecha Desde Incorrecta')
				),
				array(
						'field' => 'hasta',
						'label' => 'Fecha Hasta',
						'rules' => 'required|callback_es_fecha',
						'errors'=>array('required'=>'Debe Ingresar Fecha Hasta','es_fecha'=>'Fecha Desde Incorrecta')
				),
	),
	'alumnos_cc/deuda'=>
        array(
				array(
						'field' => 'fecha_vto',
						'label' => 'Fecha De Vencimiento',
						'rules' => 'required|callback_es_fecha',
						'errors'=>array('required'=>'Debe ingresar Fecha De Vencimiento','es_fecha'=>'Fecha De Vencimiento Incorrecta')
				),
				array(
						'field' => 'importe',
						'label' => 'Importe del Concepto',
						'rules' => 'required|decimal',
						'errors'=>array('required'=>'Debe Ingresar un Importe','decimal'=>'Debe usar . para los decimales ')
				),
				array(
						'field' => 'periodo',
						'label' => 'Periodo',
						'rules' => 'required|callback_periodo',
						'errors'=>array('required'=>'Debe Ingresar Periodo','periodo'=>'Formato del Periodo Incorrecto')
				),
	),
	'cambiar_contrasena' => array(
                array(
                        'field' => 'contrasena_actual',
                        'label' => 'Contraseña actual',
                        'rules' => 'required',
						'errors'=>array('required'=>'Debe ingresar la contraseña actual.')
                ),
                array(
                        'field' => 'contrasena_nueva',
                        'label' => 'Contraseña nueva',
                        'rules' => 'required',
						'errors'=>array('required'=>'Debe ingresar la contraseña nueva.')
                ),
                array(
                        'field' => 'contrasena_nueva_conf',
                        'label' => 'Confirmación contraseña nueva',
                        'rules' => 'required|matches[contrasena_nueva]',
						'errors'=>array('required'=>'Debe ingresar la confirmación de contraseña nueva.')
                )
        ),
		'cursos' => array(
                array(
                        'field' => 'nombre',
                        'label' => 'Curso',
                        'rules' => 'required',
						'errors'=>array('required'=>'Debe ingresar el nombre del curso.')
                )
        ),
		'conceptos_caja/editar'=>
        array(
				array(
						'field' => 'nombre',
						'label' => 'Nombre',
						'rules' => 'required',
						'errors'=>array('required'=>'Debe Ingresar Nombre')
				),
	),
	'movimientos_caja/editar'=>
        array(
				array(
						'field' => 'descripcion',
						'label' => 'Descripcion',
						'rules' => 'required',
						'errors'=>array('required'=>'Debe Ingresar Descripcion')
				),
				array(
						'field' => 'monto',
						'label' => 'Monto',
						'rules' => 'required|decimal',
						'errors'=>array('required'=>'Debe Ingresar Monto','decimal'=>'Debe Usar . para los decimales')
				),
	),
	'cierre_caja/cierre'=>
        array(
				array(
						'field' => 'monto_final',
						'label' => 'Monto Final',
						'rules' => 'required|decimal',
						'errors'=>array('required'=>'Debe Ingresar Monto Inicial','decimal'=>'Debe Usar . para los decimales')
				),
				array(
						'field' => 'monto_inicial',
						'label' => 'Monto Inicial',
						'rules' => 'required|decimal',
						'errors'=>array('required'=>'Debe Ingresar Monto Final','decimal'=>'Debe Usar . para los decimales')
				),
	),
);
$config['error_prefix'] = '<div class="alert alert-danger">';
$config['error_suffix'] = '</div>';


?>
