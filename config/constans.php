<?php

return [

    'import_url' => env('IMPORT_URL','https://www.e-obce.sk/zoznam_vsetkych_obci.html?strana='),
    'paginator'  => env('PAGINATOR', ['0', '500', '1000', '1500', '2000', '2500'])

];