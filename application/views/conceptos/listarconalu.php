<div class="container">
	<div class="row">
		<div class="col-md-12"> 
			<div class="panel panel-primary"> 
			  <div class="panel-heading">Conceptos Del Alumno <span class="badge"><?php echo $alumno->apellido .','.$alumno->nombre  ?></span></div>
			  <div class="panel-body">
			<?php if($haymas > 0){ 
			?>	  
				 <form class="navbar-form navbar-left" role="search" method="POST" action="<?php echo base_url(); ?>conceptos/agregarnuevoconcepto/<?php echo $alumno->id ?>">
				<button type="submit" class="btn btn-default">Agregar Nuevo Concepto</button>								
				</form>	
			<?php
			} 
			else
			{  
				?>	  
			<div><span class="alert alert-warning">No Quedan Conceptos Para Agregar</span></div>
			<?php
			}
			?>			
			  </div>
				<table class="table">
				  <thead>
					<tr>
					  <th>#</th>
					  <th>Nombre</th>
					  <th>Fecha Desde</th>
					  <th>Fecha Hasta</th>
					   <th>Monto</th>
					  <th>Acciones</th>
					</tr>
				  </thead>
				  <tbody>
					<?php foreach($conceptos as $alu){ ?>	 
						<tr>
							<th scope="row"><?=$alu->id ?></th>
							<td><?=$alu->concepto ?></td>
							<td><?=$alu->desde ?></td>
							<td><?=$alu->hasta ?></td>
							<td><?=$alu->monto ?></td>
							<td>
							
							<a href="<?php echo base_url(); ?>conceptos/eliminarlistaalu/<?=$alumno->id?>/<?=$alu->id?>" onclick="if(!(confirm('Borra El Registro ?')))return false;"> Eliminar </a> 
							
							 
							 </td>
						</tr>
					<?php	
					}
					?>
				  </tbody>
				</table>				
			</div>
		<div><a href="<?php echo base_url(); ?>/alumnos_cc/deuda/<?=$alumno->id?>">Ir A Generar Deuda</a></div>
		</div>
		
	</div>
</div>
