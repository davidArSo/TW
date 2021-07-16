<?php

function build_pagLinks($ruta, $numd, $numitems, $primero, $busq=null) {
  $links = [];

  $ultima = $numd - ($numd%$numitems);
  $anterior = $numitems>$primero ? 0 : ($primero-$numitems);
  $siguiente = ($primero+$numitems)>$numd ? $ultima : ($primero+$numitems);
  
  $links[] = ['texto'=>'Primera', 'url'=>$ruta.'?primero=0&items='.urlencode($numitems)];
  $links[] = ['texto'=>'Anterior', 'url'=>$ruta.'?primero='.urlencode($anterior).'&items='.urlencode($numitems)];
  $links[] = ['texto'=>'Siguiente', 'url'=>$ruta.'?primero='.urlencode($siguiente).'&items='.urlencode($numitems)];
  $links[] = ['texto'=>'Última', 'url'=>$ruta.'?primero='.urlencode($ultima).'&items='.urlencode($numitems)];

  if ($busq!=null)
    $links = add_query2links($links,$busq);

  return $links;
}

function add_query2links($links,$query) {
  foreach ($links as &$l)
    $l['url'] = $l['url'].'&'.http_build_query($query);
  return $links;
}

?>