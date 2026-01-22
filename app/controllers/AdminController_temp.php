    public function editar_usuario() { 
        if(!isset($_GET['id']))header('Location: '.URL_BASE.'admin/usuarios'); $id=$_GET['id']; 
        if($_POST){ 
            $_POST['facultad']=($this->es_coordinador)?$this->mi_facultad:$_POST['facultad_input']; 
            if($this->model('Usuario')->actualizar_full($id,$_POST)) header('Location: '.URL_BASE.'admin/usuarios?msg=editado'); 
            exit; 
        } 
        $u=$this->model('Usuario')->obtener($id); 
        $rs=$this->db->query("SELECT * FROM roles")->fetchAll(); 
        // FIX: Cargar facultades para el selector
        $fs=$this->db->query("SELECT * FROM facultades")->fetchAll(PDO::FETCH_ASSOC);
        $this->view('admin/usuarios/editar',['usuario'=>$u,'roles'=>$rs, 'facultades'=>$fs]); 
    }
