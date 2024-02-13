<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			  <div class="panel-heading">Conceptos Caja</div>
			  <div class="panel-body">
				<form class="navbar-form navbar-left" role="search" method="POST" action="<?php echo base_url(); ?>conceptos_caja/buscar">
				<input type="text" class="form-control" name="buscar" placeholder="Buscar..">
				<button type="submit" class="btn btn-default">Buscar</button>								
				</form>	
			  </div>
				<table class="table">
				  <thead>
					<tr>
					  <th>#</th>
					  <th>Nombre</th>
					  <th>Acciones</th>
					</tr>
				  </thead>
				  <tbody>
					<?php foreach($conceptos as $alu){ ?>	
						<tr>
							<th scope="row"><?=$alu->id ?></th>
							<td><?=$alu->nombre ?></td>
							<td><a href="<?php echo base_url(); ?>conceptos_caja/editar/<?=$alu->id?>"> Editar </a> | 
							 <a href="<?php echo base_url(); ?>conceptos_caja/eliminar/<?=$alu->id?>">Eliminar</a>
							 
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
