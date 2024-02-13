<div class="container">
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary">
			  <div class="panel-heading"> 
			  			  Detalle Del Recibo Oficial / Factura 
			  </div>
				 <div>		
					<table class="table">
							<tr>
								<th>Alumno</th>
								<th>Recibo</th>
								<th>Importe</th>
							</tr>
							<tr>
							<td><?php echo $alumnoyrecibo->apellido . " , " . $alumnoyrecibo->nombre ?></td>
							<td><?php printf("0003-%08d",$alumnoyrecibo->comprobante) ?></td>
							<td><?php printf("%0.2f",$alumnoyrecibo->importe) ?></td>
							</tr>
						
		
						
		
						
					</table>					
				</div>
			</div>
		 </div>
	</div>	
	<div class="row">
		<div class="col-md-12">		
			
			
			<div class="panel panel-primary">
			
			
			
			<div class="panel-heading">
							Medios de Pagos Utilizados
						
			</div>	
					<div>
							<table class="table">
							<tr>
								<th>Fecha Acreditacion</th>
								<th>Forma de Pago</th>
								<th>Cuenta</th>
								<th>Banco</th>
								<th>Cheque</th>
								<th>Deposito</th>
								<th>Cupon Tarjeta</th>
								<th>Importe</th>
							</tr>
						<?php foreach($pagos as $p){
							echo "
							<tr>
								<td>".$p->fecha_acreditacion."&nbsp;</td>
								<td>".$p->tipo_pago."&nbsp;</td>
								<td>".$p->cuenta."&nbsp;</td>
								<td>".$p->banco."&nbsp;</td>
								<td>".$p->nro_cheque."&nbsp;</td>
								<td>".$p->nro_deposito."&nbsp;</td>
								<td>".$p->cupon_tarjeta."&nbsp;</td>
								<td>".$p->importe."&nbsp;</td>
							</tr>
							
							
							";	
								
								
						 } ?>
							</table>	
					</div>
		</div>
	</div>
	</div>
		   <div class="col-md-2"> 
		     <?php 
			 $this->load->helper('form');
			
			 echo form_open('alumnos_cc/recibo/'.$id_pago,'id="myform" target=blank_');
			 
			 ?>
				<div class="form-group">
                    <input type="submit" class="form-control" value="Seguir"/>
				</div>
			</form>
		</div>		
		    
</div>
