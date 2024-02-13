<div class="container">
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
			  <div class="panel-heading">Curso</div>
			  <div class="panel-body">
			<form action="<?=base_url()?>cursos/editar/<?=$curso['id_curso']?>" method="POST">
					<div class="form-group">
						<label for="nombre">Curso:</label>
						<input type="text" name="nombre" class="form-control" value="<?=$curso['curso']?>">
					</div>
						<?php echo form_error('nombre'); ?>	
					<input type="submit" class="form-control" value="Editar"/>
				</form>
			  </div>
			</div>
		</div>
	</div>
</div>
