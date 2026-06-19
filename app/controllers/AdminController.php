<?php 

    // app/controllers/AdminController.php
    class AdminController {
        public function dashboard(): void {
            require __DIR__ . "/../views/admin/dashboard.php";
        }

        public function barbeiros(): void {
            require __DIR__ . "/../views/admin/barbeiros.php";
        }

        public function servicos(): void {
            require __DIR__ . "/../views/admin/servicos.php";
        }
    }

?>