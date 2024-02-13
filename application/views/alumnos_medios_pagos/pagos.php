<div class="container">
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
			  <div class="panel-heading">Ingresar Pagos  <span class="badge"><?php echo $alumno->apellido .','.$alumno->nombre  ?>
			   </span>
			  Comprobante : 
			  <?php printf("0003-%08d",$alumno->comprobante) ?>
			  			  
			  </div>
			  <div class="panel-body">
			 <?php 
			 $this->load->helper('form');
			
			 echo form_open('alumnos_medios_pagos/agregar/'. $pago->id_pago,'id="myform"');
			 
			 ?>
			 	
					
					<div class="form-group">
					<label for="iibb">Importe <?php printf(" ingreado %0.2f de %0.2f",$pagado,$total)  ?></label>
					<?php echo form_error('importe'); ?>	
					<?php echo form_input('importe', $resto,'class="form-control"'); ?>
					</div>	
					<div class="form-group">
					<label for="cuit">Fecha Acreditación</label>
					<?php echo form_error('fecha_acreditacion'); ?>	
					<?php	echo form_input('fecha_acreditacion',$pago->fecha_acreditacion,'class="form-control"'); ?>
				
					</div>
					<div class="form-group">
						<label for="iibb">Tipo de Pago</label>
						<?php echo form_error('tipo_pago');
						$tipos=array("Efectivo"=>"Efectivo",
									 "Cheque"=>"Cheques",
									 "Deposito"=>"Deposito",
									 "Transferecia"=>"Transferecia",
									 "Debito"=>"Debito",
									 "Credito"=>"Credito");
						 ?>	
						<?php echo form_dropdown('tipo_pago', $tipos,'Efectivo','class="form-control"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Banco</label>
						<?php echo form_error('banco'); ?>	
						<?php echo form_input('banco', $pago->banco,'class="form-control"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Cuenta</label>
						<?php echo form_error('cuenta'); ?>	
						<?php echo form_input('cuenta', $pago->cuenta,'class="form-control"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Cheque</label>
						<?php echo form_error('nro_cheque'); ?>	
						<?php echo form_input('nro_cheque', $pago->nro_cheque,'class="form-control"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Deposito</label>
						<?php echo form_error('nro_deposito'); ?>	
						<?php echo form_input('nro_deposito', $pago->nro_deposito,'class="form-control"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Fecha Vencimiento</label>
						<?php echo form_error('fecha_vencimiento'); ?>	
						<?php echo form_input('fecha_vencimiento', $pago->fecha_vencimiento,'class="form-control"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Cupon Tarjeta</label>
						<?php echo form_error('cupon_tarjeta'); ?>	
						<?php echo form_input('cupon_tarjeta', $pago->cupon_tarjeta,'class="form-control"'); ?>
					</div>
						
		    <div class="form-group">
					<input type="submit" class="form-control" value="Agregar"/>
			</div>
				</form>
			  </div>
			</div>
		</div>
	</div>
</div>
