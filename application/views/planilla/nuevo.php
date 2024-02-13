<script>

			function verificar(){
		//$("#myform").submit();
		//alert("veamos que onda");	
		$("#ganancia").html("");
		$.post("/lopez/planilla/verifica",
				{id:$("#id").val(),
				 idc:$("#idc").val(),	
				 idp:$("#idp").val(),	
				 fecha:$("#fecha").val(),
				 catres:$("#catres").val(),	
				 sumares:$("#sumares").val(),	
				 kilos:$("#kilos").val(),
				 preciov:$("#preciov").val(),
				 precioc:$("#precioc").val(),
				 totalc:$("#totalc").val(),
				 totalv:$("#totalv").val(),
				 descuento:$("#descuento").val(),
				 obs:$("#obs").val()		
				} ,
			function(data){					
					//alert(data);
					var rta=JSON.parse(data);					
					
					var x="";
					$("#respuesta").html('');
					for (i in  rta.errores) {
						x=x+rta.errores[i]+'<br>';
					}
					if(x!='') 
						$("#respuesta").html('<div class="alert alert-danger"><strong>Errores:</strong><br>'+x+'</div>');
					else{
						if($("#id").val()>0)
							$("#respuesta").html('<div class="alert alert-success">Se ha Modificado el Pedido '+ $("#id").val() +'  </div>');
						else
						$("#respuesta").html('<div class="alert alert-success">se ha agregado un nuevo Pedido  </div>');
						$("#catres").val(rta.catres);
						$("#kilos").val(rta.kilos);	
						$("#totalc").val(rta.totalc);	
						$("#totalv").val(rta.totalv);
						$("#ganancia").html('<div class="alert alert-success">Ganancia : $'+ rta.ganancia +'</div>');
						$("#id").val(rta.id);
						$("#otro").show();
					}		
						
});
		
		
		}
</script>
<div class="container">
	<div class="row">
		<div class="col-md-6">
			<div id="respuesta"></div>
			<div class="panel panel-primary">
			  <div class="panel-heading">Pedido<span class="badge"></span></div>
			  <div class="panel-body">
			 <?php 
			 $this->load->helper('form');
			 echo form_open('planilla/nuevo/'. $planilla->id,'id="myform"');
			 ?>
			 	<input type="hidden" name="id" id="id" value="<?php echo $planilla->id ?>" >
					<div class="form-group">
						<label for="cuit">Cliente</label>
					<?php echo form_error('idc'); ?>	
					<?php	echo form_dropdown('idc',$clientes,$planilla->idc,'class="form-control" id="idc"'); ?>
					</div>
					<div class="form-group">
						<label for="cuit">Proveedor</label>
					<?php echo form_error('idp'); ?>	
					<?php	echo form_dropdown('idp',$proveedores,$planilla->idp,'class="form-control" id="idp"'); ?>
					</div>
				
					<div class="form-group">
							<label for="iibb">Fecha</label>
						<?php echo form_error('fecha'); 
						$data2 = array(
				'type'  => 'date',
				'name'  => 'fecha',
				'id'    => 'fecha',
				'value' => $planilla->fecha,
				'class' => 'form-control'
							);					
						 echo form_input($data2);
						 ?>
						
					</div>	
					<!--<div class='input-group date' id='datetimepicker1'>
						<input type='date' class="form-control" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>			
				 <script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datetimepicker();
            });
					</script>
					
				-->
				
					<div class="form-group">
						<label for="iibb">Cantidad de Res</label>
						<?php echo form_error('catres'); ?>	
						<?php echo form_input('catres', $planilla->catres,'class="form-control" readonly="readonly" id="catres"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Sumar Kgs Res</label>
						<?php echo form_error('sumares'); ?>	
						<?php echo form_input('sumares', $planilla->sumares,'id="sumares" class="form-control"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Total Kgs</label>
						<?php echo form_error(''); ?>	
						<?php echo form_input('kilos', $planilla->kilos,'id="kilos" readonly="readonly" class="form-control"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Descuento x 1/2 res(Kg)</label>
						<?php echo form_error(''); ?>	
						<?php echo form_input('descuento', $planilla->descuento,'id="descuento"  class="form-control"'); ?>
					</div>

					<div class="form-group">
						<label for="iibb">Precio U. Venta</label>
						<?php echo form_error(''); ?>	
						<?php echo form_input('preciov', $planilla->preciov,'id="preciov" class="form-control"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Precio U. Compra</label>
						<?php echo form_error(''); ?>	
						<?php echo form_input('precioc', $planilla->precioc,'id="precioc" class="form-control"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Total Venta</label>
						<?php echo form_error(''); ?>	
						<?php echo form_input('totalv', $planilla->totalv,'id="totalv" readonly="readonly" class="form-control"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Total Compra</label>
						<?php echo form_error(''); ?>	
						<?php echo form_input('totalc', $planilla->totalc,'id="totalc" readonly="readonly" class="form-control"'); ?>
					</div>
					<div class="form-group">
						<label for="iibb">Observacion</label>
						<?php echo form_error(''); ?>	
						<?php echo form_input('obs', $planilla->obs,'id="obs" class="form-control"'); ?>
					</div>
					<div id="ganancia"></div>
		    <div class="form-group">
					<input type="button" class="form-control btn btn-primary" value="Agregar" onClick="verificar()"/>
			</div>
			<div id="otro" class="form-group" style="display:none">
					<a href="/lopez/planilla/nuevo/" class="form-control btn btn-primary">Nueva</a>
			</div>
			</form>	
			  </div>
			</div>
		</div>
	</div>
</div>
<?php $this->session->set_userdata("fecha",$planilla->fecha); ?>
