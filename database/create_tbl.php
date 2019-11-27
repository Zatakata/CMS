﻿<?php
  set_time_limit(72000);
  $dbd = cdb();
   mysqli_query($dbd,'drop database if exists cms');
   mysqli_query($dbd,'create database if not exists cms');
   mysqli_query($dbd,'use cms');

// ***  Таблица РОЛЬ   
  $str_sql = 'create table role (id mediumint unsigned auto_increment primary key, name char(100) default "", prim text, unique key(name))';
  if(!mysqli_query($dbd,$str_sql)){
    echo 'Ошибка создания таблицы role '. mysqli_error($dbd).'<br>';
  }else{
    if(!mysqli_query($dbd, 'insert into role (name) values ("администратор"), ("редактор"), ("автор"),("читатель")')){
      echo 'Ошибка добавления в таблицу role '. mysqli_error($dbd).'<br>';    
    }else{
      echo 'Таблица role - создана!<br>';
    }
  }

// ***  Таблица ПОЛЬЗОВАТЕЛЬ
  $str_sql = 'create table user (id mediumint unsigned auto_increment primary key, role_id mediumint unsigned, fam char(70) default "",  name char(70) default "", otch char(70) default ""
              , login char(100) default "", parol char(150) default "", e_mail char(100) default "", prim text,  unique key(login), key(role_id), key(fam,name,otch), key(e_mail)
              , foreign key (role_id) references role(id) on delete restrict
               )';
  if(!mysqli_query($dbd,$str_sql)){
    die('Ошибка создания таблицы user '. mysqli_error($dbd));
  }else{
    $parol = password_hash('1',PASSWORD_DEFAULT);
    $str_sql = 'insert into user (login,parol,role_id,fam,name,otch, e_mail) values 
                ("admin","'.$parol.'",1,"Иванов","Иван","Иванович","ddd@yandex.ru")
                ,("redaktor","'.$parol.'",2,"Петров","Петр","Петрович","petrd@yandex.ru")
                ,("avtor","' . $parol .'",3,"Зуев","Леонид","Гердович","zuid@yandex.ru")  
				,("reader","' . $parol .'",4,"Тотиев","Иван","Гердович","tuid@yandex.ru")  ';
    if(!mysqli_query($dbd,$str_sql)){
      echo 'Ошибка добавления в таблицу user '. mysqli_error($dbd).'<br>';    
    }else{
      echo 'Таблица user - создана!<br>';
    }
  }

// ***  Таблица КАТЕГОРИЯ СТАТЬИ
$str_sql = 'create table kat_stat (id mediumint unsigned auto_increment primary key, name char(200) default "", prim text, unique key(name))';
  if(!mysqli_query($dbd,$str_sql)){
    echo 'Ошибка создания таблицы kat_stat '. mysqli_error($dbd).'<br>';
  }else{
    if(!mysqli_query($dbd, 'insert into kat_stat (name) values ("Спорт"), ("Политика"), ("Экономика")')){
      echo 'Ошибка добавления в таблицу kat_stat '. mysqli_error($dbd).'<br>';    
    }else{
      echo 'Таблица kat_stat - создана!<br>';
    }
  }

// ***  Таблица СТАТЬЯ !!!
$str_sql = 'create table stat (id )';
  if(!mysqli_query($dbd,$str_sql)){
    echo 'Ошибка создания таблицы kat_stat '. mysqli_error($dbd).'<br>';
  }else{
    if(!mysqli_query($dbd, 'insert into kat_stat (name) values ("Спорт"), ("Политика"), ("Экономика")')){
      echo 'Ошибка добавления в таблицу stat '. mysqli_error($dbd).'<br>';    
    }else{
      echo 'Таблица stat - создана!<br>';
    }
  }
  

  
  
  
  
// *** Функция соединения с БД   
function cdb(){
  // Возвращает номер соединения или 0 (в случае неудачи)
   $dbd = mysqli_connect('localhost','cms','123');
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