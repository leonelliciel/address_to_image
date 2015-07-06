<?php
$var = $lenght = 18;
$display = 1;
$i= 1;
while ($var > 0) {
    if ($display == 1 && $lenght >= 3) {
        echo '==> '.$var.'<br/>';
    }
    if ($i == 3 ) {
        if ( $var <= 3 ) {
            $display = 0;
        }
        echo '<br/> <br/>';
        $i=0;
    }
    $i++;
    $var --;
}