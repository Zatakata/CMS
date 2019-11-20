<?php
set_time_limit(72000);

  $dbd = cdb();
   mysqli_query($dbd,'drop database cms');
   mysqli_query($dbd,'create database cms');
   mysqli_query($dbd,'use cms');

  $sql_str = 'create table ';
  if(!mysqli_query($dbd,$sql_str)){
      die('Ошибка создания таблицы autoinc '. mysqli_error($dbd));
   } else{
   
   }
   
   
   
   
// *** Функция соединения с БД   
function cdb(){
  // Возвращает номер соединения или 0 (в случае неудачи)
   $dbd = mysqli_connect('localhost','cms','va5tin9rog');
   // проверка успешности соединения
   if(!$dbd){
      return 0;
   }else{
      mysqli_query($dbd,'use cms');
      mysqli_query($dbd,'set names utf8');
      return $dbd;
   }
}
   
   
?>