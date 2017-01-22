<?php
$classList  = ['User','Task'];
foreach($classList as $c){
    echo $c,'<br/>';
    \api\components\CommonFunc::getHelp($c);
    echo '==========<br/><br/><Br/>';
}
exit;