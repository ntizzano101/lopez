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
		<?php if($mensaje<>''){ ?>
		<div class="
		<?php
		if($mensaje=="1"){
			$mensaje="NO SE PUEDE AGREGAR LA DEUDA PORQUE YA EXISTE";
			echo "alert alert-danger";
		}
		elseif($mensaje=="0"){
			$mensaje="Se Agrego la Deuda Correctamente";
			echo "alert alert-success";
		}
		?>
		">
			<?=$mensaje?>
		</div>
		<?php } ?>
		<div class="col-md-6">
			<div class="panel panel-primary">
			  <div class="panel-heading">Generar Deuda Para <span class="badge"><?php echo $alumno->apellido .','.$alumno->nombre  ?></span></div>
			  <div class="panel-body">
			 <?php 
			 $this->load->helper('form');
			
			 echo form_open('alumnos_cc/deuda/'. $alumno->id,'id="myform"');
			 echo form_hidden('id', $deuda->id,'id="id"');
			 echo form_hidden('id_alumno', $alumno->id,'id_alumno="id_alumno"');
			 ?>
			 	
					<div class="form-group">
						<label for="cuit">Concepto</label>
					<?php echo form_error('id_concepto'); ?>	
					<?php	echo form_dropdown('id_concepto',$cd,'','class="form-control" id="id_concepto" onChange="Cambia(this.value)"'); ?>
				
					</div>
					<div class="form-group">
						<label for="iibb">Fecha Vencimiento</label>
						<?php echo form_error('fecha_vto'); ?>	
						<?php echo form_input('fecha_vto', $deuda->fecha_vto,'class="form-control" id="fecha_vto"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Periodo</label>
						<?php echo form_error('periodo'); ?>	
						<?php echo form_input('periodo', $deuda->periodo,'class="form-control" id="periodo"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Importe</label>
						<?php echo form_error('importe'); ?>	
						<?php echo form_input('importe', $deuda->original,'id="importe" class="form-control"'); ?>
					</div>
		    <div class="form-group">
					<input type="button" class="form-control btn btn-primary" value="Agregar" onClick="Cobrar()"/>
			</div>
			</form>
			<?php  echo form_open('alumnos_cc/a_cancelar/'. $alumno->id,'id="myform2"');?>
			 <div class="form-group">
					<input type="submit" class="form-control btn btn-primary" value="Ir A Cobrar"  />
			</div>
			</form>	
			  </div>
			</div>
		</div>
	</div>
</div>
<script>
	Cambia($("#id_concepto").val());
</script>
