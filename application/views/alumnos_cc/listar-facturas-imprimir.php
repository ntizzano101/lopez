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
				<div><h3>FACTURACION DESDE <?=$desde?>  HASTA <?=$hasta?> </h3></div>
				<table class="table">
				  <thead>
					<tr>
					  <th>Fecha</th>
					  <th>Tipo</th>
					  <th>Comprobante</th>
					  					   <th>DNI</th>
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
							<td><?php printf("C 0003-%1$08d",$c->comprobante) ?>
							</td>
							<td><?=$c->dni?> </td>
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
