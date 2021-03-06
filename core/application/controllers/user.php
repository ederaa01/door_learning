<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
    }
	

	function index(){
        $this->load->model("user_mdl");

        try {
            $usuarios = $this->user_mdl->getAll();
        } catch (exception $e){
             ToJSONMsg("ERR", $e->getMessage());
             return;
        }
      
	    $data->page_title = 'Agregar Nuevo Usuario';
        $data->numero = $usuarios->NDatos;
        $data->usuarios = $usuarios->Datos;
 		$this->layout->view('users/index', $data);
	}

	function create(){
		$data['page_title'] = 'Agregar Nuevo Usuario';
 		$this->layout->view('users/form_create', $data);
	}

    function get_user_id()
    {
        $this->load->model("user_mdl");


        $user_id = $_POST['user_id'];

        try {
            $objDatosUsr = $this->user_mdl->getRow_ById($user_id);
            $objResp->Res = "success";
            $objResp->Msg = "El Usuario se ha cargado";
            $objResp->Data = $objDatosUsr;
            echo json_encode($objResp);
        } catch ( Exception $e){
            $this->tools->ToJSONMsg("error",$e->getMessage());
            return;
        }

    }

	function crear_usuario(){		
        
        $this->load->model("user_mdl");
        
        $config['allowed_types'] = 'gif|jpg|png';
        $config['image_library'] = 'gd2';            
        $config['source_image'] =$_FILES['imagen']['tmp_name'];
        $config['new_image'] = "./upload/".$_FILES['imagen']['name'];
        
        $this->load->library('image_lib',$config); 
        
        if ( !$this->image_lib->resize()){
           echo $this->image_lib->display_errors();   
        }
        
        $arrUsuario['first_name'] = $_POST['firstname'];
        $arrUsuario['last_name'] = $_POST['lastname'];
        $arrUsuario['image'] = $this->image_lib->dest_image;        
        $arrUsuario['identification'] = $_POST['idenficacion'];
        $arrUsuario['password'] = md5($_POST['password']);
        $arrUsuario['email'] = $_POST['email'];
        $arrUsuario['phone'] = $_POST['phone'];
        $arrUsuario['mobile_phone'] = $_POST['celphone'];
        $arrUsuario['date_birthday'] = $_POST['birthday'];
        
        try {
            $this->user_mdl->addRow($arrUsuario);
        } catch (exception $e){
             $this->tools->ToJSONMsg("ERR", $e->getMessage());
             return;
        }
        $data->page_title = 'Administración de Usuarios';
		$data->Notificaciones = array("modules/articulos/viewArticulos","lib/jquery/jquery.populate");
     	$this->layout->view('users/index', $data);
	}

}
?>

