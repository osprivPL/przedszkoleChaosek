<?php
    function printArr(&$arr): void {
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                echo $key . " => <br>";
                printArr($value);
                continue;
            }
            else if (gettype($value) == "boolean") {
                echo $key . " => " . ($value ? "true" : "false") . "<br>";
            }
            else {
                echo $key . " => " . $value . "<br>";
            }
        }
}
?>