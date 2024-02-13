 <div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			  <div class="panel-heading">Detalle de Comprobante </div>
			  <div class="panel-body">
				<form class="navbar-form navbar-left" id="ff" role="search" method="POST" action="<?php echo base_url(); ?>alumnos_cc/listar_facturas/">
				Desde <input type="text" class="form-control" name="desde" value="<?php printf('%s', $desde) ?>">
				Hasta <input type="text" class="form-control" name="hasta" value="<?php printf('%s',$hasta) ?>">
				<input type="hidden" name="imprimir" value="0" id="imprimir">
				<button type="submit" class="btn btn-primary">Filtrar</button>
				<button type="submit" class="btn btn-primary" onClick="$('#imprimir').val(1);$('#ff').attr('target','_blank')";>Imprimir</button>								
				</form>	
			  </div>
				<table class="table">
				  <thead>
					<tr>
					  <th>Fecha</th>
					  <th>Tipo</th>
					  <th>Comprobante</th>
					   <th>Alumno</th>
					  <th align="right">Importe</th>
					</tr>
				  </thead>
				  <tbody>
					<?php 
					$aux="";
					$total="0";
					foreach($facturas as $c){ 
							
							?>
							<tr>
							<td scope="row"><?=$c->fecha?></td>
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
							<td>&nbsp;&nbsp;&nbsp;&nbsp; <a href="<?php echo base_url(); ?>alumnos_cc/recibo/<?=$c->id?>" target="_blank"><?php printf("C 0003-%1$08d",$c->comprobante) ?></a>
							<?php if($mul>0 and $c->id_anula=='' and $c->anulada==''){ ?>
							<a class="text-danger"  href="<?php echo base_url(); ?>nota_de_credito/nc/<?=$c->id?>" 
							onClick="if(!(confirm('Confirma la Realizacion de la NOTA de credito'))) return false;">Anular<a/> 
							<?}
							else {
								if($c->anulada>=1){
										echo "<i>Anulada.</i>";
								
								}
							
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
				<tr><td colspan="4" align="right"><strong>TOTAL</strong></td>
				<td align="right"><strong><?php printf("%0.2f",$total) ?></strong>
				</tr>				
				  </tbody>
				</table>
			</div>
		</div>
	</div>
</div>
