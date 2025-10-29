<?php

class Cliente {

    private $conn;

    private $table_name = "clientes";


    public $id;

    public $nombre;

    public $email;

    public $telefono;

    public $direccion;


    public function __construct($db) {

        $this->conn = $db;

    }


    // Crear cliente

    public function crear() {

        $query = "INSERT INTO " . $this->table_name . " 

                 SET nombre=:nombre, email=:email, telefono=:telefono, direccion=:direccion";

        

        $stmt = $this->conn->prepare($query);


        // Limpiar datos

        $this->nombre = htmlspecialchars(strip_tags($this->nombre));

        $this->email = htmlspecialchars(strip_tags($this->email));

        $this->telefono = htmlspecialchars(strip_tags($this->telefono));

        $this->direccion = htmlspecialchars(strip_tags($this->direccion));


        // Vincular parámetros

        $stmt->bindParam(":nombre", $this->nombre);

        $stmt->bindParam(":email", $this->email);

        $stmt->bindParam(":telefono", $this->telefono);

        $stmt->bindParam(":direccion", $this->direccion);


        if($stmt->execute()) {

            return true;

        }

        return false;

    }


    // Leer todos los clientes

    public function leer() {

        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;

    }


    // Leer un solo cliente

    public function leerUno() {

        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);

        $stmt->execute();


        $row = $stmt->fetch(PDO::FETCH_ASSOC);


        if($row) {

            $this->nombre = $row['nombre'];

            $this->email = $row['email'];

            $this->telefono = $row['telefono'];

            $this->direccion = $row['direccion'];

            return true;

        }

        return false;

    }


    // Actualizar cliente

    public function actualizar() {

        $query = "UPDATE " . $this->table_name . " 

                 SET nombre=:nombre, email=:email, telefono=:telefono, direccion=:direccion 

                 WHERE id=:id";

        

        $stmt = $this->conn->prepare($query);


        // Limpiar datos

        $this->nombre = htmlspecialchars(strip_tags($this->nombre));

        $this->email = htmlspecialchars(strip_tags($this->email));

        $this->telefono = htmlspecialchars(strip_tags($this->telefono));

        $this->direccion = htmlspecialchars(strip_tags($this->direccion));

        $this->id = htmlspecialchars(strip_tags($this->id));


        // Vincular parámetros

        $stmt->bindParam(":nombre", $this->nombre);

        $stmt->bindParam(":email", $this->email);

        $stmt->bindParam(":telefono", $this->telefono);

        $stmt->bindParam(":direccion", $this->direccion);

        $stmt->bindParam(":id", $this->id);


        if($stmt->execute()) {

            return true;

        }

        return false;

    }


    // Eliminar cliente

    public function eliminar() {

        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);


        if($stmt->execute()) {

            return true;

        }

        return false;

    }


    // Contar total de clientes

    public function contar() {

        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total'];

    }

}

?>