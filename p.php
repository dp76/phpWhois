<?php

include('src/whois.main.php');

class tests extends PHPUnit_Framework_TestCase {

    public function testsEmpty() {
        $tests = $this->getDataSet();
        $this->assertNotEmpty($tests);
        return $tests;
    }

    /**
     * @depends testsEmpty
     */
    public function testsIterator($tests) {
        $tbl = $tests->getTable('registrars');
        for ($i = 0; $i < $tbl->getRowCount(); $i++) {
            $test = $tbl->getRow($i);
            $result = $this->getWhois($test['regrinfo.domain.name']);
            foreach ($test as $name => $value) {

                $this->assertEquals($value, $this->getValueByPath($result, $name));
            }
        }
    }
    
    protected function getValueByPath($array, $path) {
        $current = $array;
        foreach(explode(".", $path) as $element) { // registrar.whois.servers.1.server
            if(!isset($current[$element])) {
                echo "???".$array;
                echo "-- $element";
                return false;
            }
            $current = $current[$element];
        }
        echo "++".$current;
        return $current;
    }

    protected function getWhois($domain) {
        $whois = new Whois();
        return $whois->Lookup($domain);
    }

    protected function getDataSet() {
        return new PHPUnit_Extensions_Database_DataSet_YamlDataSet("test.yml");
    }

}

?>