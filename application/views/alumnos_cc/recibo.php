<html>
<head>
<style  type="text/css">
body{
	font-family:verdana;
	font-size:small;
}
.tr1{

border-bottom:1px solid #000;
}
.texto{
font-size:small;
}
</style>
</head>
<body>
<center>
<table border=1>
<tr>
	<td width="45%" style="text-align:left;margin-left:100px">
		 Instituto Damaso Valdes <br>
		 Rivadavia N&deg 38<br>
		 San Nicolas de Los Arroyos<br>
		Buenos Aires CP 2900	<br>
		0336 - 4428102 <br>
		IVA EXENTO				
	</td>
	<td width="10%" style="text-align:center" valign="top"><span style="font-size:60px">C</span><br>
		<span style="font-size:20px">COD.0<?=$recibo->tipo_comp?>
		
		</span>
	</td>
		
	<td width="45%" align="right" valign="top">
	    FECHA :<?php echo $recibo->fecha ?> <br>
		<?php
		if($recibo->tipo_comp=='11')
		echo "FACTURA ";
		elseif($recibo->tipo_comp=='13')
		echo "NOTA DE CREDITO"
		?>
		 : <?php printf("C 0003-%1$08d",$recibo->comprobante) ?> <br>
		CUIT :33-70879612-9 <br>
		IIBB : 33-70879612-9
		
		
	
	</td>
	
</tr>
<tr>
	<td colspan="3">
		 Alumno : <?php echo $recibo->nombre ."," . $recibo->apellido ?> <br> 
		 Direcci&oacute;n: <?php echo $recibo->direccion ?> <br>
		 Curso: <?php echo $recibo->curso ?> <br>
		 DNI: <?php echo $recibo->dni ?><br>
		 CONSUMIDOR FINAL	
	</td>
</tr>
<tr>
	<td colspan="4">
	<table width="100%" class="texto">
		<td  style="border:1px solid #000" >Periodo</td>
		<td  style="border:1px solid #000">Descripcion</td>
		<td  style="border:1px solid #000">Original</td>
			<td  style="border:1px solid #000">Pendiente(A)</td>
			<td  style="border:1px solid #000">Interes(B)</td>
			<td  style="border:1px solid #000">Desuento(C)</td>
			<td  style="border:1px solid #000">Debe(A+B-C)</td>
			<td  style="border:1px solid #000">Pago(E)</td>
			<td  style="border:1px solid #000">Saldo(A+B-C-E)</td>
		<td  style="border:1px solid #000" align="right">Importe(E)</td>
		<?php 
		if($recibo->NC>0){
		$it=new stdclass;
		$it->descripcion=sprintf("ANULACION RECIBO C 0003-%1$08d",$recibo->NC);
		$it->importe=$recibo->importe;
		$items=array($it);
		}
		foreach ($items as $it){ ?>
		<tr>
			<td  ><?php echo $it->periodo ?></td>
			<td ><?php echo $it->descripcion ?></td>
			<td align="right"><?php echo $it->original ?></td>
			<td align="right"><?php echo $it->pendiente ?></td>
			<td align="right"><?php echo $it->interes ?></td>
			<td align="right"><?php echo $it->descuento ?></td>
			<td align="right"><?php echo $it->debe ?></td>
			<td align="right"><?php echo $it->importe ?></td>
			<td align="right"><?php echo $it->saldo ?></td>
			<td align="right"><?php echo $it->importe ?></td>
		</tr>
	<?php } 	
	for($j=0;$j<30 - count($items);$j++){ ?>	
			<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			
			</tr>
		<?php
		}		
		?>
		<tr>
			<td width="80%" colspan="9" align="right">TOTAL</td>
			
			<td width="20%" align="right"><b><?php echo $recibo->importe ?></b></td>
		</tr>
		<tr>
			<td width="100%" colspan="10" align="center">
				<table width="100%" align="center">
					<tr>
						<td>
							<img src="/damaso/img/<?php echo $recibo->barraimagen ?>">

						</td>
						<td>
							CAE : <?php echo $recibo->cae ?>
						</td>
						<td>
							CAE VENCE : <?php echo $recibo->vto ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>		
	</table>	
	</td>
</tr>

</table>
</center>	
</body>
</html>
