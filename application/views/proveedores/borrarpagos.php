<script>
	function Borrar(id){
			if(confirm('Realmente Borra '+id+'?')){	
			$.post("/lopez/proveedores/eliminarpago",
			{id:id}, 
			function(data){
					var rta = JSON.parse(data);					
					if(rta.ok=="1"){
						$("#C"+id).remove();
					}
					
			});
					
					
					
				}
			else
				return false;
		
		}
</script>	
<div class="container">
	<!--    -->
<div class="row">
	<div class="alert alert-success">Visualizando TODOS los  Pagos al Proveedor
	 <strong><?=$nombre->prov ?> </strong> 
	
	</div>
		<div class="col-md-12">
		
		</div>	  
				<table class="table">
				  <thead>
					<tr>
						<th>#</th>
					  <th>Fecha</th>
						<th align="right">Monto</th>
					</tr>
				  </thead>
				  <tbody>
					<?php 
					$aux="";
					$total="0";
					foreach($cc as $c){ 
						?>
						<tr id="C<?=$c->id?>">
							<td scope="row"><?=$c->id?></td>
							<td ><?=$c->fecham?></td>
							<td>
							<td  align="right"><?php  echo $fx->moneda($c->monto);  ?>
							<a  class="glyphicon glyphicon-trash" href="#" onclick="Borrar(<?=$c->id?>)"></a>
							</td> 
 							</tr>
						<?php	
						}
						?>
				  </tbody>
				</table>
		<table>
						
				
			</div>
			
		</div>
	</div>
</div>
