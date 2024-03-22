
<div class="container">
	<!--    -->
	
	<script>
	function muestra(a,b){
		$("#prov").html(b);
		$("#idprov").html(a);
		}
	function cancelar(){
		
		
	}
	function validar(){
		document.getElementById('errormonto').style.display = 'none';
		$.post("/lopez/clientes/validarpago",
		{monto:$("#monto").val(),
		 deuda:$("#deuda").val(),
		 fecha:$("#fecha").val()}, 
		function(data){			
			//alert(data);
				var rta = JSON.parse(data);					
				if(rta.ok=="0"){
					document.getElementById('myform').submit();
					return true;
				}
				else{
					$("#errormonto").html(rta.mensaje);
					document.getElementById('errormonto').style.display = 'block';
					return false;
				}
		});	
		
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
		$.post("/lopez/clientes/eliminarpago",
		{id:id}, 
		function(data){
				var rta = JSON.parse(data);					
				if(rta.ok=="1"){
					$("#errorok").html(v+'<strong>'+ rta.pedido +'</strong> Se Eliminó');	
					document.getElementById('errorok').style.display = 'block';
					$("#error").html('');
					$("#tr"+id).remove();
				}
				else{
					$("#error").html(v+'<strong>'+ rta.pedido +'</strong>' + rta.mensaje);
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
<div class="alert alert-success alert-dismissible fade in" id="errorok" style="display:none">
</div> 
	
	
	
	
	<!--    -->
	<div class="row">
			<div class="alert alert-success">Visualizando Cuenta Corriente de <strong><?=$nombre?></strong></div>
		<div class="col-md-12">
			<div class="alert alert-primary"><button type="button" class="btn btn-success">1 Mes</button>
<button type="button" class="btn btn-info">6 Meses</button>
<button type="button" class="btn btn-warning">1 A&ntilde;o</button>
<button type="button" class="btn btn-danger">Todo</button></div>
		<div class="col-md-12">
		</div>	  
				<table class="table">
				  <thead>
					<tr>
						<th>#</th>
					  <th>Fecha</th>
					  <th>Descripcion</th>
					  <th align="right">Debe</th>
					  <th align="right">Haber</th>
					  <th align="right">Saldo</th>
					  <th align="right">Acciones</th>
					</tr>
				  </thead>
				  <tbody>
					<?php 
					$aux="";
					$total="0";
                    //muestro solo los ultiumos 15 movimientos 
                    //del resto el saldo
                    $cuantos=count($cc)-15;                 
                    $cuento=0;
                    $saldo_antes=0;
					foreach($cc as $c){ 
                            $cuento++;
                            if($cuento < $cuantos){
                              //acumulo , no muestro.
                                $saldo_antes=$saldo_antes + $c->haber    - $c->debe;
                                //die("dale m mas  ".$cuantos . "cuento ". $cuento);     
                            }
                            else{
                            //Si hubo saldo anterior muestro...
                            if($saldo_antes<>0){
                                ?>
                                <tr>
                                    <td colspan="5"><strong> SALDO ANTERIOR <strong></td>
                                    <td  align="right"><? echo  $fx->moneda($saldo_antes); $total=$total + $saldo_antes;$saldo_antes=0; ?> </td>
                                    <td></td>
                                </tr>
                                <?
                            }        
							if ($c->haber>0) {
									?>
									<tr id="tr<?=$c->id?>">
									<?php
							}
							else
							{
								?>
									<tr>
									<?php
							}	
							?>
							
							
							
							<td scope="row"><?=$c->id?></td>
							<td ><?=$c->Fe?></td>
							<td>
							<?=$c->descripcion?>
							</td>
							<td  align="right"><?php  echo $fx->moneda($c->debe);  ?></td>
							<td align="right"><?php  echo  $fx->moneda($c->haber) ?></td>
							<?php if($c->debe > $c->haber){
								?>
								<input type="hidden" name="valor[]" value="<?=$c->debe ?>">	
								<input type="hidden" name="ids[]" value="<?=$c->id ?>">	
								<?	
								} 
							
							?>
							<td align="right"><?php $total=$total - $c->debe + $c->haber;   echo  $fx->moneda($total) ?></td>
							<td>
							<?php if ($c->haber>0) { ?>
								<a  class="glyphicon glyphicon-trash" href="/lopez/clientes/borrarpago/<?=$c->id?>"></a> 
								<?php } ?>
 							</tr>
							
						<?php	
						
						}
					}				
							
											
				
					?>
				  </tbody>
				</table>
			</div>
		</div>
	<div class="col-md-3">
			<div class="panel panel-primary">
			  <div class="panel-heading">Agregar Un Pago</div>
			  <div class="panel-body">
			  <div class="alert alert-danger" id="errormonto" style="display:none"></div>
			  <div class="form-group">
			 <?php 
			$this->load->helper('form');
			$data1 = array(
			'type'  => 'hidden',
			'name'  => 'deuda',
			'id'    => 'deuda', 
			'value' => $total
			);
				$data2 = array(
			'type'  => 'hidden',
			'name'  => 'idcliente',
			'id'    => 'idcliente',
			'value' => $idcliente,
			 );
			 	$data3 = array(
			'type'  => 'date',
			'name'  => 'fecha',
			'id'    => 'fecha',
			'value' => date("Y-m-d"),
			'class' => "form-control" 
			 );
			 echo form_open('clientes/pagar"','id="myform" name="myform"');			 
			 echo form_input($data1); 
			 echo form_input($data2); 
			 ?>			 		
				<label for="cuit">Fecha</label>
					<?php echo form_input($data3); ?>
				<label for="cuit">Monto a Cancelar</label>
					<?php echo form_input('monto',round(abs($total),2),'class="form-control" id="monto"'); ?>
				<label for="cuit">Comentario</label>
					
					<?php echo form_input('comentario','','class="form-control" id="comentario"'); ?>
									

			</div>	
					<input type="submit" class="form-control btn-primary" onClick="if(!validar()){return false;}" value="Agregar"/>
				</form>
			  </div>
			</div>
		</div>
	</div>
	</div>
</div>
