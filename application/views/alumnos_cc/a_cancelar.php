 <script>
 function confirmar(){
		var total=0.00;
		var cant=parseInt($("#reng").val());
		for(i=0;i<=cant;i++){
			total=total+parseFloat($("input[name='a_cancelar["+i+"][debe]']" ).val());
		}
		if(total==0.00){
				alert('No Hay Deuda Que Cancelar');
			   return false;
		}
		if(confirm('Esta Seguro de Cancelar la deuda?'))
			return true;
		else
			return false;	
	 }
function calcular(v){
	var total=0.00;
	$.each($('input[type=text]'),function(){
	if(!(isNaN($(this).val()))){
		total=total +  parseFloat($(this).val());
	}
});
	total=redondeo(total,2);
	$("#total").html(total);
}
function formato(v){
	$.each($('input[type=text]'),function(){
	if(!(isNaN($(this).val()))){
		$(this).val(redondeo($(this).val(),2));
		}
	});
}
function CancelarAUTO(){
var total=0.00;
	var cant=parseInt($("#reng").val());
	for(i=0;i<=cant;i++){
			$("input[name='a_cancelar["+i+"][importe]']" ).val($("input[name='a_cancelar["+i+"][debe]']" ).val());
			total=total+parseFloat($("input[name='a_cancelar["+i+"][debe]']" ).val());
	}
	total=redondeo(total,2);
	$("#total").html(total);
}
function CancelarMONT(v){
	if(isNaN(v)){
		alert('No es Numerico el valor ingresado ' + v);
		return false;
	
	}
	var monto=parseFloat(v);
	if(monto <=0 ){
		alert('No es Numerico el valor ingresado ' + v);
		return false;
	
	}
	var cant=parseInt($("#reng").val());
	for(i=0;i<=cant;i++)
			$("input[name='a_cancelar["+i+"][importe]']" ).val(0.00);
	
	var totaldeuda=0.00;
	for(i=0;i<=cant;i++)
				totaldeuda=totaldeuda+parseFloat($("input[name='a_cancelar["+i+"][debe]']" ).val());
	if(totaldeuda < monto){
		alert('El Monto '+ monto + '  Supera el Total de la Deuda ' + totaldeuda);
		return false;
	}			
	var total=0.00;
	var resto=monto;
	for(i=0;i<=cant;i++){
			if(resto>0){
				deuda=parseFloat($("input[name='a_cancelar["+i+"][debe]']" ).val())
				if(deuda<=resto)
						cancela=deuda;
				else
						cancela=resto;
				cancela=redondeo(cancela);		
				$("input[name='a_cancelar["+i+"][importe]']" ).val(cancela);
				total=total + cancela;
				resto=resto - cancela;
			}
	}
	total=redondeo(total,2);
	$("#total").html(total);
}	 
 </script>
 <div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
			  <div class="panel-heading">Detalle Adeudado <span class="badge"><?=$alu->apellido .", ". $alu->nombre ?></span></div>
			  <div class="panel-body">
					
			
				
			  </div>
				<table class="table">
				  <thead>
					<tr>
					  <th>Fecha</th>
					  <th>Periodo</th>
					  <th>Concepto</th>
					  <th>Deuda</th>
					  <th>&nbsp;</th>
					  <th>Eliminar</th>
					</tr>
				  </thead>
				  <tbody>
					<?php 
					 $this->load->helper('form');
					 echo form_open('alumnos_cc/a_cancelar/'. $alu->id,'id="myform"');
					 echo form_hidden('id', $alu->id);
					$i=0;
					$total=0;
					foreach($cc as $c){ 
						    if($c->debe > 0){ 
							?>
							<tr>
							<td scope="row"><?=$c->vto ?></td>
							<td><?=$c->periodo ?></td>
							<td><?=$c->nombre ?></td>
							<td align="right"><?=$c->debe ?></td>
							<td align="right">
							<?php echo form_error("importe_a_cancelar[][id]"); 
							 echo form_hidden('a_cancelar['.$i.'][id]', $c->id);
							 echo form_hidden('a_cancelar['.$i.'][debe]', $c->debe);
							?>	
						<?php echo form_error('a_cancelar['.$i.'][importe]'); ?>		
						<?php echo form_input('a_cancelar['.$i.'][importe]', '0.00','size="5%" style="text-align:right"  onKeyUp="calcular(this.value)" onBlur="formato(this.value)" id="importes" class="form-control"'); 
							$total=$total+$c->debe;
							?>
							 
							
							
							</td> 
							<td><a href="<?php echo base_url(); ?>alumnos_cc/borradeuda/<?=$c->id ?>" onclick="if(!(confirm('Borra Item?'))) return false;">Eliminar</a></td>
							</tr>
							<?php
							$i++;
						  }
						
						}
						
						
						
				
					?>
					<tr>
						
						<td colspan="3">
						<a href="<?php echo base_url();?>alumnos_cc/a_cancelar/<?=$alu->id ?>/0">	
							NO Calcular Intereses y Descuentos </a> 
						| 
						<a href="<?php echo base_url();?>alumnos_cc/a_cancelar/<?=$alu->id ?>/1">	
							SI Calcular Intereses y Descuentos </a> 
						<?php if($total>0) { ?>		
						| 
						<a href="#" onClick="CancelarAUTO()">	
							Cancelar Automatico </a> |
							<a href="#" onClick="CancelarMONT(prompt('Ingrese Monto a Cancelar'))">	
							Ingresar Monto </a> 
						<?php } ?>	
						<td  align="right">
							<strong><?=$total;?></strong>
						</td>	
						<td align="right"><strong><span id="total" style="text-align:rigth">00.00</span></strong></td>
						<td  align="right">
						<button type="submit" class="btn btn-default" onclick="if(!(confirmar())) return false; ">Confirmar</button></td>
						<input type="hidden" value="<?=--$i?>" id="reng">
					</tr>
				  </tbody>
				</table>
				</form>	
			</div>
		</div>
	</div>
</div>
