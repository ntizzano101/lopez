 <div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			  <div class="panel-heading">Detalle de la Cuenta Corriente <span class="badge"><?=$alu->apellido .", ". $alu->nombre ?></span></div>
			  <div class="panel-body">
				<form class="navbar-form navbar-left" role="search" method="POST" action="<?php echo base_url(); ?>alumnos_cc/cuentacorriente/<?=$alu->id ?>">
				Desde<input type="text" class="form-control" name="desde" placeholder="<?php sprintf('%s', date('d/m/Y') - 30) ?>">
				Hasta<input type="text" class="form-control" name="hasta" placeholder="<?php sprintf('%s',date('d/m/Y')) ?>">
				<button type="submit" class="btn btn-default">Filtrar</button>								
				</form>	
			  </div>
				<table class="table">
				  <thead>
					<tr>
					  <th>Fecha Vto.</th>
					  <th>Periodo</th>
					   <th>Concepto</th>
					  <th align="right">Debe</th>
					  <th align="right">Haber</th>
					  <th align="right">Saldo</th>
					</tr>
				  </thead>
				  <tbody>
					<?php 
					$aux="";
					$total="0";
					foreach($cc as $c){ 
						if($aux!=$c->id){
							$total=$total+$c->debe;
							?>
							<tr>
							<td scope="row"><?=$c->vto ?></td>
							<td><?=$c->periodo ?></td>
							<td><?=$c->nombre ?></td>
							<td align="right"><?=$c->debe ?></td>
							<td>&nbsp;</td>
							<td align="right"><?php printf("%0.2f",$total)?></td> 
							</tr>
							<?php
						}
						if($c->comprobante!=""){
							$total=$total-$c->haber;
							?>
							<tr>
							<td scope="row">&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp; <a href="<?php echo base_url(); ?>alumnos_cc/recibo/<?=$c->id_pago?>"><?=$c->fe_pago ?> <?php printf("C 0003-%1$08d",$c->comprobante) ?></a>
							</td>
							<td>&nbsp;</td>
							<td align="right"><?=$c->haber?></td>
							<td align="right"><?php printf("%0.2f",$total)?></td> 
							</tr>
							
						<?php	
							
						}
							
							if($aux!=$c->id){
							$aux=$c->id;
						  }	
							
							
						}
						
				
					?>
				  </tbody>
				</table>
			</div>
		</div>
	</div>
</div>
