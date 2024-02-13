 <div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			  <div class="panel-heading">Cierres de Caja</div>
			  <div class="panel-body">
			  </div>
				<table class="table">
				  <thead>
					<tr>
					  <th>#</th>
					  <th>Apertura</th>
					  <th>Cierre</th>
					  <th align="right">Monto Inicial</th>
					  <th align="right">Monto Final</th>
					  <th>Acciones</th>
					</tr>
				  </thead>
				  <tbody>
					<?php foreach($cierres as $alu){ ?>	
						<tr>
							<th scope="row"><?=$alu->id ?></th>
							<td><?=$alu->fecha_aperturaM ?></td>
							<td><?=$alu->fecha_cierreM ?></td>
							<td align="right"><?=$alu->monto_inicial ?></td>
							<td align="right"><?=$alu->monto_final ?></td>
							<td><a href="<?php echo base_url(); ?>cierre_caja/listar/<?=$alu->id?>"> Imprimir </a>
				
							 
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
