<script>

    
    function Test(){
        
    }

</script>
<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function fetdate($len) {
        $chars = array("0", "1", "2",
                "3", "4", "5", "6", "7", "8", "9"
                );
        $charsLen = count($chars) - 1;
        shuffle($chars);
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }

$a = fetdate(5);
echo $a;
?>
