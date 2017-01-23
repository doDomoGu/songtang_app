<?php
$classList  = ['user','oa'];
foreach($classList as $c){
    echo $c,'<br/>';
    \api\components\CommonFunc::getHelp($c);
    echo '==========<br/><br/><Br/>';
}
exit;