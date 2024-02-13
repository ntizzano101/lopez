<div class="container">
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
			  <div class="panel-heading">Agragar un Nuevo Concepto <span class="badge"><?php echo $alumno->apellido .','.$alumno->nombre  ?></span></div>
			  <div class="panel-body">
			 <?php 
			 $this->load->helper('form');
			
			 echo form_open('conceptos/agregarnuevoconcepto/'. $concepto->id_alumno,'id="myform"');
			 echo form_hidden('id', $concepto->id);
			 echo form_hidden('id_alumno', $concepto->id_alumno);
			 ?>
			 	
					<div class="form-group">
						<label for="cuit">Concepto</label>
					<?php echo form_error('id_concepto'); ?>	
					<?php	echo form_dropdown('id_concepto',$cd,'','class="form-control"'); ?>
				
					</div>
					<div class="form-group">
						<label for="iibb">Fecha Desde</label>
						<?php echo form_error('desde'); ?>	
						<?php echo form_input('desde', $concepto->desde,'class="form-control"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Fecha Hasta</label>
						<?php echo form_error('hasta'); ?>	
						<?php echo form_input('hasta', $concepto->hasta,'class="form-control"'); ?>
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
