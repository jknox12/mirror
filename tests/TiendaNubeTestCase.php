<?php

abstract class TiendaNubeTestCase extends PHPUnit_Framework_TestCase {

    protected function setUp() {
        $this->setUpDatabase();
    }

    protected function tearDown() {
        $this->tearDownDatabase();
    }

    private function setUpDatabase() {
        $sql = file_get_contents(MWP_ROOT . 'scripts/vagrant/sqlite-schema.sql');
        $tables = explode(';', $sql);
        foreach($tables as $table){
            if (!empty($table)){
                DB::connection('memory')->query($table);
            }
        }
    }

    private function tearDownDatabase() {
        DB::$connections = [];
    }

}
