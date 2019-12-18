
<?php
$dbd = cdb();

if(isset($_GET['slovo'])){
	$slovo = mysqli_real_escape_string($dbd,$_GET['slovo']);
echo $slovo;
	$where_flt =' where match(name, content) against("+'.$slovo.' выборы россии in boolean mode") ';
	$sql_str='select name, content, match(name, content) against("+'.$slovo.' выборы россии in boolean mode") rel from cms.stat '. $where_flt;

}else{
	$where_flt ='';		
	$sql_str='select name,content from cms.stat ';
}

if(!$res = mysqli_query($dbd,$sql_str)){
	die(mysqli_error($dbd));
}

$kol = mysqli_num_rows($res);
echo $kol.'<br>';	

echo 'Результат<br>';	
echo '<table border=1>';	
while($arr =mysqli_fetch_row($res)){
	echo '<tr><td>'.$arr[0].'</td><td>'.$arr[1].'</td><td>'.$arr[2].'</td></tr>';			
}
echo '</table>';	




function cdb($data_name = 'cms'){
  // Возвращает номер соединения или 0 (в случае неудачи)
   $dbd = mysqli_connect('localhost','cms','123');
   // проверка успешности соединения
   if(!$dbd){
      return 0;
   }else{
      mysqli_query($dbd,'use '.  $data_name);
      mysqli_query($dbd,'set names utf8');
      return $dbd;
   }
}
?>