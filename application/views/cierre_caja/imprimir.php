<head>
<style>
table {
font-family:verdana;
font-size:8pt;

}
td {
		border:1px solid #ccc;
}
th {
		border:1px solid #ccc;
}
</style>
</head>
				<table class="table">
				  <thead>
					<tr>
					  
					  <th>Apertura</th>
					  <th>Cierre</th>
					  <th align="right">Monto Inicial</th>
					  <th align="right">Monto Final</th>
					 
					</tr>
				  </thead>
				  <tbody>
					<?php foreach($cierre as $alu){ ?>	
						<tr>
						
							<td><?=$alu->fecha_aperturaM ?></td>
							<td><?=$alu->fecha_cierreM ?></td>
							<td align="right"><?=$alu->monto_inicial ?></td>
							<td align="right"><?=$alu->monto_final ?></td>
						
						</tr>
					<?php	
					}
					?>
				  </tbody>
				</table>

				
				
				<table class="table">
				  <thead>
					<tr>
					  <th>Fecha</th>
					  <th>Descripcion</th>
					  <th>Concepto</th>
					 
					  <th align="right">Ingreso</th>
					   <th align="right">Egreso</th>
					  <th align="right">Saldo</th>
					</tr>
				  </thead>
				  <tbody>
				<?php 
					$aux="";
					$total=0;
					
					foreach($movimientos as $c){ 
							$borrar=false;
							?>
							<tr>
							<td scope="row"><?=$c->fechaM?></td>
							<td>
							<?php 
							echo $c->nombreA." ";
							if($c->concepto=='Cobro Cuota'){
								if($c->tipo=="H"){
									echo " NC ";
								}
								printf("C 0003-%1$08d",$c->descripcion);
								}
							else{
								echo $c->descripcion;
								$borrar=true;
								}
							?>
							</td>
							<td><?=$c->concepto?></td>
						
							
							<td align="right"><?php
							if($c->tipo=="D")
							{
							  printf("%0.2f",$c->monto);
							  $total=$total+$c->monto; 	
							}
							else
							echo "0.00";
							?>
							</td>
							<td align="right"><?php
							if($c->tipo=="D")
							{
							echo "0.00";
							 
							}
							else
							{
							printf("%0.2f", $c->monto);
							  $total=$total-$c->monto; 	
							}
							?>
							</td>
							<td align="right">
								<?php
								
								printf("%0.2f",$total);
							
								?>
							</td>
							
					<?php		
					//print_r($c);die;
						}
									
							
											
				
					?>
				<tr><td colspan="5" align="right"><strong>
				<?php
						echo "Saldo Movimientos";
					

				?>
				</strong></td>
				<td align="right"><strong><?php printf("%0.2f",$total) ;?></strong>
				</tr>				
				  </tbody>
				</table>
				
				<table>
				  <thead>
					<tr>
					  <th>Saldo al Inicio</th>
					  <th>Saldo Movimientos</th>
					  <th>Saldo Final </th>
					</tr>
				  </thead>
				  <tbody>
					<tr>
						<td align="right">
									<?php 
								 printf("%0.2f",$alu->monto_inicial) ;
								?>
						</td>
						<td align="right">
									<?php 
							 printf("%0.2f",$total) ;?>
							
						</td>
						
						<td align="right">
								
									<?php 
							 printf("%0.2f",$total+$alu->monto_inicial) ;?>
								
						</td>
					</tr>
					<tr>
						<td colspan="3"><strong>Cheques</strong></td>
					</tr>
					<tr>
						<td colspan="3"><?=$alu->cheques?></td>
					</tr>
					<tr>
						<td colspan="3"><strong>Observaciones</strong></td>
					</tr>
					<tr>
						<td colspan="3"><?=$alu->observaciones?></td>
					</tr>
					
				  </tbody>
				</table>
				
				