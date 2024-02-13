<?php
class Cuentacorrientedetalle_model extends CI_Model {

    public $id;
    public $idpago;       
    public $idpedido;
	public $monto;
	public $tipo;
	   
	   
    public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }
	public function guardar($p)
        {
								
				
					$query=$this->db->insert('cc_pago_det', $p);
						
				
			return true;
        }
}
?>
