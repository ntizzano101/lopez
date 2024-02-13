<?php
$this->load->helper(array('number','date'));
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			  <div class="panel-heading">Conceptos Por Alumnos</div>
			  <div class="panel-body">
				<form class="navbar-form navbar-left" role="search" method="POST" action="<?php echo base_url(); ?>conceptos/conceptosalumno">
				<input type="text" class="form-control" name="buscar" placeholder="Buscar..">
				<button type="submit" class="btn btn-default">Buscar</button>								
				</form>	
			  </div>
				<table class="table">
				  <thead>
					<tr>
					  <th>#</th>
					  <th>Nombre</th>
					  <th>Cruso</th>
					  <th>Conceptos</th>
					  <th>Acciones</th>
					</tr>
				  </thead>
				  <tbody>
					<?php foreach($alumnos as $alu){ ?>	
						<tr>
							<th scope="row"><?=$alu->id ?></th>
							<td><?=$alu->apellido .", ". $alu->nombre ?></td>
							<td><?=$alu->curso ?></td>
							<td> <span class="badge"><?=$alu->total ?></span></td>
							<td><a href="<?php echo base_url(); ?>conceptos/listarconalu/<?=$alu->id?>"> Editar </a> | 
							 <a href="<?php echo base_url(); ?>alumnos/eliminarconalu/<?=$alu->id?>">Eliminar</a>
							 
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
