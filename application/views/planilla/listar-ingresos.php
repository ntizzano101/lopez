 <div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			  <div class="panel-heading">Detalle de ingresos</div>
			  <div class="panel-body">
				<form class="navbar-form navbar-left" role="search" method="POST" action="<?php echo base_url(); ?>alumnos_cc/listar_ingresos/">
				Desde <input type="text" class="form-control" name="desde" value="<?php printf('%s', $desde) ?>">
				Hasta <input type="text" class="form-control" name="hasta" value="<?php printf('%s',$hasta) ?>">
				<button type="submit" class="btn btn-primary">Filtrar</button>								
				</form>	
			  </div>
				<table class="table">
				  <thead>
					<tr>
					  <th>Fecha</th>
					  <th>Medio de Pago</th>
					  <th>Comprobante</th>
						<th>Tipo</th> 
					   <th>Alumno</th>
					  <th align="right">Importe</th>
					</tr>
				  </thead>
				  <tbody>
					<?php 
					$aux="";
					$total="0";
					foreach($ingresos as $c){ 
							
							?>
							<tr>
							<td scope="row"><?=$c->fecha?></td>
							<td><?=$c->tipo_pago?></td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp; <a href="<?php echo base_url(); ?>alumnos_cc/recibo/<?=$c->id_pago?>"><?php printf("C 0003-%1$08d",$c->comprobante) ?></a>
							</td>
							<td>
							<?php 
							if($c->tipo_comp=='11'){
								echo "FACTURA";
								$mul=1;
								}
							elseif($c->tipo_comp=='13')	{
								echo "NOTA DE CREDITO";
								$mul=-1;
								}
							?>
							</td>
							<td><?=$c->apellido?> <?=$c->nombre?></td>
							<td align="right"><?php 
							if($mul==1)
							echo $c->importe;
							else
							echo "(".$c->importe.")";
							?></td>
							</tr>
							
						<?php	
							$total=$total+$c->importe*$mul;
						}
									
							
											
				
					?>
					<tr><td colspan="5" align="right"><strong>TOTAL</strong></td>
				<td align="right"><strong><?php printf("%0.2f",$total) ?></strong>
				</tr>
				  </tbody>
				</table>
			</div>
		</div>
	</div>
</div>
