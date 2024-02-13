 <div class="container">
	<!--    -->
	
	<script>
	function muestra(a,b){
		$("#prov").html(b);
		$("#idprov").html(a);
		}
	function borra(){
		var id=$("#idprov").html();
		var nombre=$("#prov").html();
		var v='<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
		$("#errorok").html('');
		$("#error").html('');
		document.getElementById('error').style.display = 'none';
		document.getElementById('errorok').style.display = 'none';
		$("#error").html('');
		$.post("/lopez/planilla/eliminar",
		{id:id}, 
		function(data){
				var rta = JSON.parse(data);					
				if(rta.ok=="1"){
					$("#errorok").html(v+'<strong>'+ rta.pedido +'</strong> Se Eliminó');	
					document.getElementById('errorok').style.display = 'block';
					$("#error").html('');
					$.post("delete",
					{id:id}, 
					function(data){});
					$("#tr"+id).remove();
				}
				else{
					//$("#error").html(v+'<strong>'+ rta.pedido +'</strong>' + rta.mensaje);
					//document.getElementById('error').style.display = 'block';
					alert(rta.pedido + ' ' + rta.mensaje);
				}
		});	
	 }	
</script>
 <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Desea Eliminar la Planilla</h4>
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
<div class="alert alert-success alert-dismissible fade in" id="errorok" style="display:none">
</div> 
	
	
	
	
	<!--    -->
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
			  <div class="panel-heading"Listado </div>
			  <div class="panel-body">
				<form class="navbar-form navbar-left" id="ff" role="search" method="POST" action="listado">
				<div class="form-group">
						<label for="cuit">Desde</label>
					<input type="text"  class="form-control" name="desde" value="<?php printf('%s', $desde) ?>">
				</div>
				<div class="form-group">
					<label for="cuit">Hasta</label>
					<input type="text"  class="form-control" name="hasta" value="<?php printf('%s',$hasta) ?>">
				</div>
				<div class="form-group">
						<label for="cuit">Cliente</label>	
					<input type="text" class="form-control" name="clie" value="<?php printf('%s',$clie) ?>">
				</div>
				<div class="form-group">
						<label for="cuit">Proveedor</label>
						<input type="text" class="form-control" name="prov" value="<?php printf('%s',$prov) ?>">
				</div>
				<div class="form-group">		
				<input type="hidden" name="imprimir" value="0" id="imprimir">
				 <button type="submit" class="btn btn-primary" onClick="$('#imprimir').val(0)">Filtrar</button>
				 <button type="submit" class="btn btn-primary" onClick="$('#imprimir').val(1);$('#ff').attr('target','_blank')";>Imprimir</button>								
				 <button type="submit" class="btn btn-success" onClick="$('#imprimir').val(2);$('#ff').attr('target','_blank')";>Excel</button>	
				</div>
				</form>	
			  </div>
		</div>	  
				<table class="table" id="tablaplanilla">
				  <thead>
					<tr>
					 <th>#</th>
					  <th>Fecha</th>
					  <th>Cliente</th>
					  <th>Pr</th>
					  <th>Kgs</th>
					  <th>1/2s</th>
					  <th>Compra</th>					
				<!--	  <th>Saldo P</th> -->
					  <th>Venta</th>
					 
					  <th>Com.U</th>
					   <th>Ven.U</th>
				<!--	  <th>Cobrado</th> -->
				<!--	  <th>Saldo C</th> -->
					  <th>Gcia.T</th>
				<!--	  <th>Gcia.R</th> -->
					  <th>Acciones</th>
					</tr>
				  </thead>
				  <tbody>
					<?php 
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
							?>
							<tr id="tr<?=$c->id?>">
							<td scope="row"><?=$c->id?></td>
							<td ><?=$c->fecham?></td>
							<td>
							<?=$c->cli?>
							</td>
							<td>
								<?=substr($c->prov,0,2)?>
							</td>
							<td align="right"><?=sprintf("%d",$c->kilos) ?></td>
							<td  align="right"><?=sprintf("%d",$c->catres) ?></td>		
							<td  align="right"><?=$c->totalc ?></td>												
						<!--	<td  align="right"><?=$saldop ?></td>		-->
							<td  align="right"><?=$c->totalv ?></td>
							<td  align="right"><?=$c->precioc ?></td>
							<td  align="right"><?=$c->preciov ?></td>
						<!--	<td  align="right"><?=$c->cobrado ?></td> -->
						<!--	<td  align="right"><?=$saldoc ?></td>	-->												
							<td align="right"><?=$c->ganancia ?></td>		   			
						<!--	<td  align="right"><?=$gcia ?></td>		-->		
							<td align="right"><a href="nuevo/<?=$c->id?>" class="glyphicon glyphicon-edit" aria-hidden="true"></a>
							&nbsp;<a href="#" onclick="muestra(<?=$c->id?>,'Fecha : <?php echo $c->fecham  . "  "  . $c->kilos ?>Kg')" class="glyphicon glyphicon-trash" aria-hidden="true" data-toggle="modal" data-target="#myModal"></a></td>
							</tr>
							
						<?php	$total=$total+$c->kilos;
						$saldoproveedor=$saldoproveedor + $saldop;
						$saldocliente=$saldocliente + $saldoc;
						
						}
					?>
					<tr>				
						<td align="right" colspan="4">Total de Kgs</td>
						<td align="right"><?=$total ?></td>
						<td align="right">Tot.<?=$totmedias?></td>
						<td align="right"><?=sprintf("%0.2f",$totcomp)?></td>						
						<td align="right"><?=sprintf("%0.2f",$totven)?></td>
						<td align="right"><?=sprintf("%0.2f",$totgan)?></td>
						<td>&nbsp;</td>
					</tr>						
				
					
				
				  </tbody>
				</table>
			</div>
		</div>
	</div>
</div>
