<?
$filename="Planilla" . date('Ymdhis');
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=".$filename);
$cadena='
		<table border=1>
				  <thead>
					<tr>
					 <th>#</th>
					  <th>Fecha</th>
					  <th>Cliente</th>
					  <th>Pr</th>
					  <th>Kgs</th>
					    <th>1/2s</th>
					  <th>Compra</th>									
					  <th>Venta</th>								
					  <th>Gcia.T</th>				
					</tr>
				  </thead>
				  <tbody>';
					
					$aux="";
					$total="0";
					$totgan=0;
					$totcomp=0;
					$totven=0;
					$totmedias=0;
					$saldocliente=0;
					$saldoproveedor=0;
					foreach($pedidos as $c){ 
							if($c->pagado=="")
									$c->pagado=0;
							if($c->cobrado=="")
									$c->cobrado=0;
							$saldop=$c->totalc - $c->pagado;
							$saldoc=$c->totalv - $c->cobrado;
							$gcia=$c->cobrado - $c->pagado; 
							$totgan=$totgan + $c->ganancia;
							$totcomp=$totcomp+$c->totalc;
							$totven=$totven+$c->totalv;
							$totmedias=$totmedias+$c->catres;
					$cadena=$cadena . '		
							<tr id="tr'.$c->id.'">
							<td scope="row">'.$c->id.'</td>
							<td >'.$c->fecham.'</td>
							<td>
							'.$c->cli.'
							</td>
							<td>
								'.substr($c->prov,0,2).'
							</td>
							<td align="right">'.sprintf("%d",$c->kilos) .'</td>
							<td  align="right">'.sprintf("%d",$c->catres) .'</td>		
							<td  align="right">'.$c->totalc .'</td>												
						<!--	<td  align="right">'.$saldop .'</td>		-->
							<td  align="right">'.$c->totalv .'</td>
						<!--	<td  align="right">'.$c->cobrado .'</td> -->
						<!--	<td  align="right">'.$saldoc .'</td>	-->												
							<td align="right">'.$c->ganancia .'</td>		   			
						<!--	<td  align="right">'.$gcia .'</td>		-->									
							</tr>';
							
							$total=$total+$c->kilos;
						$saldoproveedor=$saldoproveedor + $saldop;
						$saldocliente=$saldocliente + $saldoc;
						
						}
					$cadena=$cadena . '		
					<tr>				
						<td align="right" colspan="4">Total de Kgs</td>
						<td align="right">'.$total .'</td>
						<td align="right">Tot.'.$totmedias.'</td>
						<td align="right">'.sprintf("%0.2f",$totcomp).'</td>						
						<td align="right">'.sprintf("%0.2f",$totven).'</td>
						<td align="right">'.sprintf("%0.2f",$totgan).'</td>						
					</tr>										
				
				  </tbody>
				</table> ';
echo $cadena;