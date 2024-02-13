 <div class="container">
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
		$.post("eliminar",
		{id:id}, 
		function(data){
				var rta = JSON.parse(data);					
				if(rta.ok=="1"){
					$("#errorok").html(v+'<strong>'+ rta.cliente +'</strong> Se Eliminó');	
					document.getElementById('errorok').style.display = 'block';
					$("#error").html('');
					$.post("delete",
					{id:id}, 
					function(data){});
					$("#tr"+id).remove();
				}
				else{
					$("#error").html(v+'<strong>'+ rta.cliente +'</strong>' + rta.mensaje);
					document.getElementById('error').style.display = 'block';
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
          <h4 class="modal-title">Desea Eliminar el Cliente</h4>
        </div>
        <div class="modal-body">
          <p id="idprov">1</p>
          <p id="prov">Borrando A Proveedor</p>
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
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			  <div class="panel-heading">Clientes</div>
			  <div class="panel-body">
				<form class="navbar-form navbar-left" role="search" method="POST" action="<?php echo base_url(); ?>clientes/buscar">
				<input type="text" class="form-control" name="buscar" placeholder="Buscar..">
				
				<button type="submit" class="btn btn-primary">Buscar</button>	
				
<!--				Solo con deudas<input type="checkbox"  class="form-control" value="1"  
				"<?php /*if($solocd=="1") 
						echo "checked";  
						else echo ""; 
			*/	?>" name="solocd" > 					-->		
				</form>	
			  </div>
				<table class="table">
				  <thead>
					<tr>
					  <th>#</th>
					  <th>Nombre</th>
					  <th>Saldo</th>
					  <th>Acciones</th>
					</tr>
				  </thead>
				  <tbody>
					<?php foreach($clientes as $alu){ ?>	
						<tr id="tr<?=$alu->id?>">
							<th scope="row"><?=$alu->id ?></th>
							<td><a href="<?php echo base_url(); ?>clientes/cuentacorriente/<?=$alu->id?>"><?=$alu->nombre ?></a></td>
							<td><?php echo $fx->moneda($alu->tot) ?></td>
							<td> <a href="#" onClick="muestra(<?=$alu->id?>,'<?=$alu->nombre?>')" data-toggle="modal" data-target="#myModal">Eliminar</a>
							 </td>
						</tr>
					<?php	
					}
					?>
				  </tbody>
				</table>
			</div>
		</div>
	</div>
</div>
