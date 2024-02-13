<script>
	function Cambia(id){
		$.get("<?php echo base_url(); ?>alumnos_cc/importe/" + id , function( data ) {
				$("#importe").val(data);
			
});
		
		
		}
			function Cobrar(){
			$("#myform").submit();
		/*$.post("<?php echo base_url(); ?>alumnos_cc/deuda/" + $("#id_alumno").val(),
				{id:$("#id").val(),
				 id_alumno:$("#id_alumno").val(),	
				 id_concepto:$("#id_concepto").val(),	
				 fecha_vto:$("#fecha_vto").val(),
				 periodo:$("#periodo").val(),	
				 importe:$("#importe").val(),	
				} ,
			function( data ) {
					alert(data);
					$("#respuesta").html('<div class="alert alert-success">se ha agregado la deuda para el periodo ' + $("#periodo").val() +' Por :' +  $("#importe").val() + '</div>');
			
});
		
		*/
		}
</script>	
<div class="container">
	<div class="row">
		<?php if($advertencia<>''){ ?>
		<div class="alert alert-warning">
			<?=$advertencia?>
		</div>
		<?php } ?>
		<?php if($mensaje<>''){ ?>
		<div class="alert alert-success">
			Se Ha Guardado Em Movimiento de Caja
		</div>
		<?php } ?>
		<div class="col-md-6">
			<div class="panel panel-primary">
			  <div class="panel-heading">Movimiento de Caja<span class="badge"></span></div>
			  <div class="panel-body">
			 <?php 
			 $this->load->helper('form');
			
			 echo form_open('/movimientos_caja/editar','id="myform"');
			 ?>
			 	
					<div class="form-group">
						<label for="cuit">Concepto</label>
					<?php echo form_error('concepto_id'); ?>	
					<?php	echo form_dropdown('concepto_id',$cd,'','class="form-control" id="concepto_id"'); ?>
				
					</div>
					<div class="form-group">
						<label for="iibb">Descripcion</label>
						<?php echo form_error('descripcion'); ?>	
						<?php echo form_input('descripcion', $item->descripcion,'class="form-control" id="descripcion"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Tipo</label>
						<?php echo form_error('tipo'); ?>	
						<?php echo form_dropdown('tipo',$tipo,'','class="form-control" id="tipo"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Importe</label>
						<?php echo form_error('monto'); ?>	
						<?php echo form_input('monto', $item->monto,'id="monto" class="form-control"'); ?>
					</div>
		    <div class="form-group">
					<input type="button" class="form-control btn btn-primary" value="Agregar" onClick="Cobrar()"/>
			</div>
			</form>
			
			  </div>
			</div>
		</div>
	</div>
</div>

