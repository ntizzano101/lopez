<script>
	function Borrar(id){
			if(confirm('Realmente Borra '+id+'?')){	
			$.post("/lopez/clientes/eliminarpago",
			{id:id}, 
			function(data){
					var rta = JSON.parse(data);					
					if(rta.ok=="1"){
						$("#C"+id).remove();
						if($('#tablita >tbody >tr').length==0){
							document.getElementById("volver").click()
							
						};
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
	<div class="alert alert-success">Visualizando Pagos De
	Cliente <strong><?=$nombre->cli?> </strong> 
	
	</div>
		<div class="col-md-12">
		
		</div>	  
				<table id="tablita" class="table">
				  <thead>
					<tr>
						<th>#</th>
					  <th>Fecha</th>
					  <th>Comentario</th>
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
							<td ><?=$c->obs?></td>
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
			<div class="col-md-2">
			<a href="/lopez/clientes/cuentacorriente/<?=$c->idcp ?>" class="form-control btn btn-primary" id="volver"> Volver </a>
			</div>
		</div>
	</div>
</div>
