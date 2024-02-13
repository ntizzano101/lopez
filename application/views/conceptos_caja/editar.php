<div class="container">
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
			  <div class="panel-heading">Concepto Caja</div>
			  <div class="panel-body">
			 <?php 
			 $this->load->helper('form');
			
			 echo form_open('conceptos_caja/editar','id="myform"');
			 echo form_hidden('id', $concepto->id);
			 ?>
			 	
					<div class="form-group">
						<label for="nombre">Nombre</label>
					<?php echo form_error('nombre'); ?>	
					<?php	echo form_input('nombre',$concepto->nombre,'class="form-control"'); ?>
				
					</div>
					<div class="form-group">
					

					<input type="submit" class="form-control btn-primary" value="Agregar"/>
					</div>
				</form>
			  </div>
			</div>
		</div>
	</div>
</div>
