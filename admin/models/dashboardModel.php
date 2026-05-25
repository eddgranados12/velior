<?php

class DashboardModel
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function totalProductos()
    {
        return $this->db->query("SELECT COUNT(*) FROM producto")->fetchColumn();
    }

    public function totalCategorias()
    {
        return $this->db->query("SELECT COUNT(*) FROM categoria")->fetchColumn();
    }

    public function totalSubcategorias()
    {
        return $this->db->query("SELECT COUNT(*) FROM subcategoria")->fetchColumn();
    }

    public function totalColecciones()
    {
        return $this->db->query("SELECT COUNT(*) FROM coleccion")->fetchColumn();
    }

}