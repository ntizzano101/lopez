<script>
function confirmar(){
	if(confirm('Cierra '))

}
</script>
<div class="container">
	<div class="row">
		<?php if($mensaje<>''){ ?>
		<div class="alert alert-success">
			<?php echo $mensaje ;?>
		</div>
		<?php } ?>
		<div class="col-md-6">
			<div class="panel panel-primary">
			  <div class="panel-heading">CAJA<span class="badge"></span></div>
			  <div class="panel-body">
			 <?php 
			 $this->load->helper('form');
			
			 echo form_open('/cierre_caja/cierre/'. $cierre->id,'id="myform"');
			 echo form_hidden('id',$cierre->id,'id="id" class="form-control"');
			 ?>
			 	
					<div class="form-group">
						<label for="cuit">Fecha Apertura</label>
					<?php echo form_error('fecha_apertura'); ?>	
					<?php	echo form_input('fecha_apertura',$cierre->fecha_apertura,'class="form-control"  '.$readonly.' id="fecha_apertura"'); ?>
				
					</div>
					<div class="form-group">
						<label for="iibb">Fecha Cierre</label>
						<?php echo form_error('fecha_cierre'); ?>	
						<?php echo form_input('fecha_cierre', $cierre->fecha_cierre,'class="form-control" '.$readonly.' id="fecha_cierre"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Monto Inicial</label>
						<?php echo form_error('monto_inicial'); ?>	
						<?php echo form_input('monto_inicial', $cierre->monto_inicial,'class="form-control" id="monto_inicial"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Monto Final</label>
						<?php echo form_error('monto_final'); ?>	
						<?php echo form_input('monto_final', $cierre->monto_final,'id="monto_final"  '.$readonly01.' class="form-control"'); ?>
					</div>
		   <div class="form-group">
						<label for="iibb">Cheques</label>
						<?php echo form_error('cheques'); ?>	
						<?php echo form_input('cheques', $cierre->cheques,'id="cheques" class="form-control"'); ?>
					</div>
			<div class="form-group">
						<label for="iibb">Observaciones</label>
						<?php echo form_error('observaciones'); ?>	
						<?php echo form_textarea('observaciones', $cierre->observaciones,'id="observaciones" class="form-control"'); ?>
					</div>		
			<div class="form-group">
						<?php if($readonly01==""){ ?>
						<?php echo form_submit('continuar', 'Modificar Y Seguir','class="form-control btn-success"'); ?>	
						<?php } ?>
			</div>		
			<div class="form-group">
							
						<?php echo form_submit('guardar', 'Guardar','class="form-control btn-primary" onClick="if(confrimar()) return false"'); ?>
			</div>		
			</form>
			
			  </div>
			</div>
		</div>
	</div>
</div>
