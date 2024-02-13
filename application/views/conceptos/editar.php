<div class="container">
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
			  <div class="panel-heading">Concepto</div>
			  <div class="panel-body">
			 <?php 
			 $this->load->helper('form');
			
			 echo form_open('conceptos/editar','id="myform"');
			 echo form_hidden('id', $concepto->id);
			 ?>
			 	
					<div class="form-group">
						<label for="cuit">Nombre</label>
					<?php echo form_error('nombre'); ?>	
					<?php	echo form_input('nombre',$concepto->nombre,'class="form-control"'); ?>
				
					</div>
					<div class="form-group">
						<label for="iibb">Monto</label>
						<?php echo form_error('monto'); ?>	
						<?php echo form_input('monto', $concepto->monto,'class="form-control"'); ?>
				

					<input type="submit" class="form-control" value="Agregar"/>
				</form>
			  </div>
			</div>
		</div>
	</div>
</div>
