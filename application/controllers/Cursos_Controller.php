<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cursos_Controller extends CI_Controller {
	public function __construct(){
		parent::__construct();
			if(!isset($this->session->usuario)){
				redirect('salir');
				exit;
			}		
	}
	public function listado()
	{
		$this->load->model('Cursos_Model');
		$data["cursos"]=$this->Cursos_Model->listado();
		$this->load->view('encabezado.php',$data);
		$this->load->view('menu.php',$data);
		$this->load->view('cursos/cursos_listado_view',$data);
	}
	public function nuevo_curso()
	{
		$this->load->library('form_validation');
		if(!$this->input->post()){
			$this->load->view('encabezado.php');
			$this->load->view('menu.php');
			$this->load->view('cursos/nuevo_curso_view');
		}
		else
		{
			if ($this->form_validation->run('cursos') == FALSE)
            {
				$this->load->view('encabezado.php');
				$this->load->view('menu.php');
				$this->load->view('cursos/nuevo_curso_view');
			} else 
			{
				$this->load->model('Cursos_Model');
				$this->Cursos_Model->guardar_curso($this->input->post('nombre'), 0);
				redirect('/cursos');
			}
		}
	}
	public function editar_curso($id)
	{
		$this->load->library('form_validation');
		$this->load->model('Cursos_Model');
		if(!$this->input->post()){
			$this->load->view('encabezado.php');
			$this->load->view('menu.php');
			$data['curso']=$this->Cursos_Model->curso($id);
			$this->load->view('cursos/editar_curso_view', $data);
		}
		else
		{
			if ($this->form_validation->run('cursos') == FALSE)
            {
				$this->load->view('encabezado.php');
				$this->load->view('menu.php');
				$data['curso']=$this->Cursos_Model->curso($id);
				$this->load->view('cursos/editar_curso_view', $data);
			}
			else 
			{
				$this->load->model('Cursos_Model');
				$this->Cursos_Model->guardar_curso($this->input->post('nombre'), $id);
				redirect('/cursos');
			}
		}
	}
	public function conf_eliminar_curso($id)
	{
		$this->load->view('encabezado');
		$this->load->view('menu');
			$data = array(
			"mensaje"=>"¿Seguro desea eliminar este curso?",
			"url"=>"cursos/eliminar/".$id);
		$this->load->view('confirmacion_view', $data);
		$this->load->view('pie');
	}
	public function eliminar_curso($id)
	{
		$this->load->model('Cursos_Model');
		$checkear_curso_sin_alumnos = $this->Cursos_Model->checkear_curso_sin_alumnos($id);
		if($checkear_curso_sin_alumnos){
			$this->load->view('encabezado');
			$this->load->view('menu');
				$data = array(
				"titulo"=>"No se puede eliminar este curso",
				"mensaje"=>"Este curso posee alumnos.",
				"url_back"=>base_url()."cursos");
			$this->load->view('mensaje_view', $data);
			$this->load->view('pie');
		}
		else
		{
			$this->Cursos_Model->eliminar_curso($id);
			redirect('/cursos');
		}
	}
}
?>