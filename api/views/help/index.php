<?php
$classList  = ['User','Oa'];
foreach($classList as $c){
    echo strtolower($c),'<br/>';
    \api\components\CommonFunc::getHelp($c);
    echo '==========<br/><br/><Br/>';
}
exit;