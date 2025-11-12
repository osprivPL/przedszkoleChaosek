<?php
    function printArr(&$arr): void {
        foreach ($arr as $key => $value) {
            echo $key . " => " . $value . "<br>";
        }
}
?>