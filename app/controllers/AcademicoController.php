<?php
require_once '../app/core/Controller.php';
require_once '../app/core/Database.php';

class AcademicoController extends Controller {
    private $db;
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if ($_SESSION['rol'] != 3) { header('Location: '.URL_BASE.'admin/dashboard'); exit; }
        $this->db = (new Database())->connect();
    }

    public function index() {
        $sql = "SELECT p.*, f.nombre as facultad FROM programas p JOIN facultades f ON p.id_facultad = f.id ORDER BY f.nombre, p.nombre";
        $progs = $this->db->query($sql)->fetchAll();
        $facs = $this->db->query("SELECT * FROM facultades")->fetchAll();
        $this->view('admin/academico/index', ['programas' => $progs, 'facultades' => $facs]);
    }

    public function guardar_facultad() {
        if($_POST){
            if(isset($_POST['id']) && !empty($_POST['id'])) {
                $this->db->prepare("UPDATE facultades SET nombre=?, siglas=? WHERE id=?")->execute([$_POST['nombre'], $_POST['siglas'], $_POST['id']]);
            } else {
                $this->db->prepare("INSERT INTO facultades (nombre, siglas) VALUES (?, ?)")->execute([$_POST['nombre'], $_POST['siglas']]);
            }
            header('Location: '.URL_BASE.'academico/index');
        }
    }

    public function guardar_programa() {
        if($_POST){
            if(isset($_POST['id']) && !empty($_POST['id'])) {
                $this->db->prepare("UPDATE programas SET id_facultad=?, nombre=?, nivel=? WHERE id=?")->execute([$_POST['id_facultad'], $_POST['nombre'], $_POST['nivel'], $_POST['id']]);
            } else {
                $this->db->prepare("INSERT INTO programas (id_facultad, nombre, nivel) VALUES (?, ?, ?)")->execute([$_POST['id_facultad'], $_POST['nombre'], $_POST['nivel']]);
            }
            header('Location: '.URL_BASE.'academico/index');
        }
    }
}
