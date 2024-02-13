 <div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			  <div class="panel-heading">Cursos</div>
			  <div class="panel-body">
				<!--<form class="navbar-form navbar-left" role="search" method="POST" action="<?php echo base_url(); ?>alumnos/buscar">
				<input type="text" class="form-control" name="buscar" placeholder="Buscar..">
				<button type="submit" class="btn btn-default">Buscar</button>								
				</form>-->	
			  </div>
			  <table class="table">
				  <thead>
					<tr>
					  <th>#</th>
					  <th>Curso</th>
					  <th>Acciones</th>
					</tr>
				  </thead>
				  <tbody>
					<?php foreach($cursos as $curso){ ?>	
						<tr>
							<th scope="row"><?php echo $curso->id_curso ?></th>
							<td><?php echo $curso->curso ?></td>
							<td>
								<a href="<?php echo base_url(); ?>cursos/editar/<?php echo $curso->id_curso ?>">Editar</a> | 
								<a href="<?php echo base_url(); ?>cursos/conf_eliminar/<?php echo $curso->id_curso ?>">Eliminar</a>
							</td>
						</tr>
					<?php	
					}
					?>
				  </tbody>
				</table>
			</div>
		</div>
	</div>
</div>
