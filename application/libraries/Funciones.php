<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Funciones {

        public function fecha_nacimiento($fecha)
        {
			$rta=True;
			//si es vacio o una fecha devuelve true
			if($fecha!=""){
				$rta=False;
				$a=explode("/",$fecha);	
			if(count($a)==3){
					$rta=checkdate( $a[1],  $a[0],  $a[2]);
					if($a[2]<2000)
						$rta=false;
			}		
			}
	return $rta;
	}
        
         public function fecha_ingreso($fecha)
        {
			$rta=True;
	//si es vacio o una fecha devuelve true
		if($fecha!=""){
			$rta=False;
			$a=explode("/",$fecha);
			if(count($a)==3){
					$rta=checkdate( $a[1],  $a[0],  $a[2]);
			}		
	}
	return $rta;
        }
    
     public function fechaToDb($fecha)
        {
	    $fecha=substr($fecha,0,10); 
		$rta="";
		if($fecha!=""){
			$a=explode("/",$fecha);
			if(count($a)==3){
					$rta=$a[2]."-".$a[1]."-".$a[0];
			}		
		}
			return $rta;
        }
      
      public function DbTofecha($fecha)
        {
	    $fecha=substr($fecha,0,10); 
		if($fecha!=""){
			$a=explode("-",$fecha);
			if(count($a)==3){
					$rta=$a[2]."/".$a[1]."/".$a[0];
			}		
		}
			return $rta;
        } 
      
      public function periodo($p){
				$c=explode("-",$p);				
				if(count($c)==2){
					if(strlen($c[1])==2){
						if($c[0]>2006 and $c[0]<=date('Y')+1 and in_array($c[1],array("01","02","03","04","05","06","07","08","09","10","11","12"),true)){
								
								return true;	
						}	
					}
				}	
			return false;
		  }
	public	function codigo_barras($url,$nom,$valores){
			//par1 path completo archivo.png	
			//par2 valores del codigo de barras
			$par=true;
			$pares="0";
			$impares="0";
			for($i=0;$i<strlen($valores);$i++){
				if($par)
					$pares=$pares + substr($valores,$i,1);
				else
					$impares=$impares + substr($valores,$i,1);
				$par=!$par;
			}
			$impares=$impares*3;
			$total=$impares+$pares;
			if(substr($total,strlen($total)-1,1)=='0')
				$digito="0";
			else
				$digito= (substr($total,0,strlen($total)-1).'0')+10 -  $total ;
			//$f=fopen("http://www.sannicolas.gov.ar/barcode2/image.php?code=$valores$digito&style=68&type=I25&width=400&height=50&xres=1","r");
			$f=fopen("http://".$url."/barcode/barcode.php?code=$valores$digito&encoding=I25&scale=1&mode=png","r");
			$png="";
			while($c=fgets($f,1204)){
				$png=$png.$c;	
			}
			fclose($f);
			$a=fopen($nom,"w");
			 fputs($a,$png,strlen($png));
			fclose($a); 
			return $digito;	
			}  
	 
		public function moneda($p){
			 if($p>=0)
					return "$" .  number_format($p,2,".",",");
			 else
				return "$(" .  number_format(abs($p),2,".",",") . ")";
			}       
}
