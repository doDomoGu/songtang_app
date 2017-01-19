<?php
$classList  = ['User','Task','Help'];
foreach($classList as $c){
    echo $c,'<br/>';
    \api\components\CommonFunc::getHelp($c);
    echo '==========<br/>';
}
exit;