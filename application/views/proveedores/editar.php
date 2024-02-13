<div class="container">
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
			  <div class="panel-heading">Proveedor</div>
			  <div class="panel-body">
			 <?php 
			 $this->load->helper('form');
			
			 echo form_open('proveedores/editar','id="myform"');
			 echo form_hidden('id', $proveedor->id);
			 ?>
			 	
					<div class="form-group">
						<label for="cuit">Nombre</label>
					<?php echo form_error('nombre'); ?>	
					<?php	echo form_input('nombre',$proveedor->nombre,'class="form-control"'); ?>
				
					</div>
					
				
					<input type="submit" class="form-control btn-primary" value="Agregar"/>
				</form>
			  </div>
			</div>
		</div>
	</div>
</div>
