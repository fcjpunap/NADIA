    public function editar_docente() { 
        if(!isset($_GET['id'])) header('Location: '.URL_BASE.'admin/docentes'); 
        $id=$_GET['id']; 
        if($_POST){
            $_POST['facultad'] = ($this->es_coordinador)?$this->mi_facultad:$_POST['facultad_input'];
            if(isset($_POST['facultad_input'])) $_POST['id_facultad'] = $_POST['facultad_input'];
            $this->model('Usuario')->actualizar_full($id,$_POST); 
            header('Location: '.URL_BASE.'admin/docentes?msg=updated'); exit;
        } 
        $u=$this->model('Usuario')->obtener($id); 
        $e=$this->model('Usuario')->obtenerExpertise($id); 
        $f=$this->db->query("SELECT * FROM facultades")->fetchAll(PDO::FETCH_ASSOC);
        
        $arbol=[]; 
        // FIX: REQUIRE PARA EVITAR FATAL ERROR
        if(file_exists('../app/models/Jerarquia.php')){
            require_once '../app/models/Jerarquia.php'; 
            $arbol=(new Jerarquia())->obtenerArbol();
        }
        $this->view('admin/docentes/editar',['usuario'=>$u,'expertise'=>$e,'arbol'=>$arbol, 'facultades'=>$f, 'ui'=>$this->getUiData()]); 
    }
