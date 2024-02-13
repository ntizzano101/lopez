  <nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?=base_url()?>">SISTEMA Lopez</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
	      <li class="nav-item">
				<a class="nav-link" href="<?=base_url()?>clientes/listado">Clientes</a>
		</li>
		<li class="nav-item">
				<a class="nav-link" href="<?=base_url()?>clientes/editar/">Nuevo Cliente</a>
		</li>
		  <li class="nav-item">
				<a class="nav-link" href="<?=base_url()?>proveedores/listado/">Proveedores</a>
		</li>
		<li class="nav-item">
				<a class="nav-link" href="<?=base_url()?>proveedores/editar/">Nuevo Provee.</a>
		</li>
		  <li class="nav-item">
				<a class="nav-link" href="<?=base_url()?>planilla/listado">Pedidos</a>
		</li>
		<li class="nav-item">
				<a class="nav-link" href="<?=base_url()?>planilla/nuevo/">Nuevo Pedido</a>
		</li>
	   <?php /*
		 <li class="dropdown">
          <a href="<?=base_url()?>clientes/listado" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Clientes<span class="caret"></span></a>
         
		  <ul class="dropdown-menu">
			<li><a href="<?=base_url()?>clientes/editar/">Nuevo Clie.</a></li>
            <li><a href="<?=base_url()?>clientes/listado">Listado</a></li>
            <li><hr></li>
          </ul>		  
       
	    <li class="dropdown">
          <a href="<?=base_url()?>proveedores/listado" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Proveedores<span class="caret"></span></a>
		
		<ul class="dropdown-menu">
            <li><a href="<?=base_url()?>proveedores/editar/">Nuevo Prov.</a></li>
            <li><a href="<?=base_url()?>proveedores/listado">Listado</a></li>
			 <li><hr></li>
          </ul>
        </li> 
		
		   <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pedidos<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?=base_url()?>planilla/nuevo/">Nueva Planilla</a></li>
            <li><a href="<?=base_url()?>planilla/listado">Listado</a></li>
          </ul>
        </li>
	<!--
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Facturas<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?=base_url()?>alumnos_cc/listar_facturas">Listar Facturas</a></li>
            <li><a href="<?=base_url()?>alumnos_cc/listar_ingresos">Listar Ingresos</a></li>
          </ul>
        </li>
        <li><a href="<?=base_url()?>index.php/empresas">Empresas</a></li>
      </ul>
	  -->
	  */ ?>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $this->session->userdata("titulo") ?><span class="caret"></span></a>
          <ul class="dropdown-menu">
			<li><a href="<?=base_url()?>cambiar_contrasena">Cambiar contraseña</a></li>
            <li><a href="<?=base_url()?>salir">Salir</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
