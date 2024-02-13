<script>
function muestra(){
		//$("#prov").html(b);
		//$("#idprov").html(a);
alert('eer');
		}
	function confirma(){
		var id=$("#idprov").html();
		var nombre=$("#prov").html();
		var v='<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
		$("#error").html('');
		document.getElementById('error').style.display = 'none';
		$("#error").html('');
		var original=Array();
		var cancelar=Array();
		var id=Array();
		$('input[name="original[]"').each(function()
			{original.push($(this).val()); });			
		$('input[name="cancelar[]"').each(function()
			{cancelar.push($(this).val()); });	
		$('input[name="id[]"').each(function()
			{id.push($(this).val()); });		
		$.post("/lopez/clientes/guardapago",
		{o:original,c:cancelar,i:id,obs:$('input[name="obs"').val()}, 
		function(data){
			var rta = JSON.parse(data);					
				if(rta.ok=="1"){
					$("#error").html(v+' ' +  rta.mensaje);
					document.getElementById('error').style.display = 'block';
				}
				else{
					document.getElementById('row2').style.display = 'block';
					document.getElementById('row1').style.display = 'none';
				}
				
		});	
	 }	
</script>
<div class="container">
 <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Borra Pago Registrado</h4>
        </div>
        <div class="modal-body">
          <p id="idprov">1</p>
          <p id="prov">Borrando Planilla</p>
        </div>
        <div class="modal-footer">
		 <button type="button" class="btn btn-success" data-dismiss="modal" onClick="borra()">Seguir</button>	
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
      
    </div>
  </div>  
<div class="alert alert-danger  alert-dismissible fade in" id="error" style="display:none">
</div>
	<div class="row" id="row2" style="display:none">
		<div class="col-md-12">
				<div class="alert alert-success">Se guardo el pago<strong></strong></div>
				<a href="/lopez/clientes/listado" class="btn btn-primary">Seguir</a>
			
		</div>		

	</div>
	<!--    -->
	<div class="row" id="row1">
			<div class="alert alert-success">Visualizando Cuenta Corriente de <strong><?=$nombre?></strong></div>
		<div class="col-md-12">
		
		  
				<table class="table">
				  <thead>
					<tr>
						<th>#</th>
					  <th>Fecha</th>
					  <th>Descripcion</th>
					  <th align="right">Total</th>
					  <th align="right">Pagado</th>
					  <th align="right">A Pagar</th>
					
					</tr>
				  </thead>
				  <tbody>
				
					<?php 
					$aux="";
					$total="0";
					foreach($cc as $c){ 
							
							?>
							<tr id="tr<?=$c->id?>">
							<td scope="row"><?=$c->id?></td>
							<td ><?=$c->Fe?></td>
							<td>
							<?=$c->descripcion?>
							</td>
							<td  align="right"><?php if($c->debe >0) { echo $fx->moneda($c->debe);  } ?></td>
							<td align="right"><?php if($c->haber >0) { echo  $fx->moneda($c->haber); } ?></td>
							<td align="right">
			<input type="hidden" name="id[]" value="<?=$c->id?>">				
			<input type="hidden" name="original[]" value="<?php echo round($c->debe-$c->haber,2)?>">
				<input type="text" name="cancelar[]" value="<?php  echo round($c->debe-$c->haber,2) ?>" 
					class="form-control"></td>
 							</tr>
							
						<?php	$total=$total - $c->debe + $c->haber;
						
						}
									
							
											
				
					?>
					<tr>
						<td colspan=4 align="right">Saldo</td>
						<td align="right"><?php 
							$rta=$fx->moneda($total);
						echo $rta; ?></td>
						<td>
					
					<label>Observaciones</label>			
					<input type="text" name="obs" value="" class="form-control">
					<br>
					<input type="button" class="form-control btn-primary" 
					onClick="confirma()" value="Guardar">
					</td>
					</tr>
				  </tbody>
				</table>
			</div>
		</div>
	</div>
</div>
