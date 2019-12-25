<?php
  set_time_limit(72000);
  $db_name = 'cms';
  $dbd = cdb($db_name);
   mysqli_query($dbd,'drop database if exists '.$db_name);
   mysqli_query($dbd,'create database if not exists '.$db_name);
   mysqli_query($dbd,'use '.$db_name);

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
$str_sql = 'create table kat_stat (id mediumint unsigned auto_increment primary key
                        , name char(200) default ""
                        , prim text
						, user_id mediumint unsigned		
                        , unique key(name))';
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
$str_sql = 'create table stat (id int unsigned primary key auto_increment
                    , name char(250) default "" comment "Наименование статьи"
                    , autor_id mediumint unsigned comment "Идентификатор автора user(id)"
                    , kat_id mediumint unsigned comment "Идентификатор категории статьи kat_stat(id)"
                    , dt_start datetime comment "Дата и Время начала публикации, если пусто, то нет начала"
                    , dt_end datetime comment "Дата и Время окончания публикации, если пусто, то нет окончания"
                    , status enum("0","1") default "0" comment "0 - черновик (не опубликовано); 1 - опубликовано"
                    , main enum("0","1") default "0" comment "1 - расположение на главной странице; 0 - нет "
                    , content mediumtext comment "содержание (текст статьи)"
                    , place char(100) default "" comment "место рапсоложения статьи (в каком блоке)"
                    , prim text
					, user_id mediumint unsigned		
                    , unique key(name), key(autor_id), key(kat_id), key(dt_start), key(dt_end), key(status), key(main), key(place)
                    , fulltext key(name)
                    , fulltext key(content)
                    , fulltext key(name, content)
                    , foreign key (autor_id) references user(id) on delete restrict
                    , foreign key (kat_id) references kat_stat(id) on delete restrict
                    )';
  if(!mysqli_query($dbd,$str_sql)){
    echo 'Ошибка создания таблицы stat '. mysqli_error($dbd).'<br>';
  }else{
    $ins_str = 'insert into stat (name, content, autor_id, kat_id) values 
            ("Новая статья о футболе","Сегодня опять завершился футбольный сезон.", 3,1)
            , ("Нашего президента опять встречали в Зимбабве","Состоялась встреча президента России и опять президента Зимбабве.", 3,2)
            , ("Еще статья о политике","Опять прошли выборы президента Бурунди. Пост президента опять занял бывший министр обороны Мбамбе Зумамбе", 2,2)
            , ("Это опять современная политика","В следующем году пройдут выборы президента россии. Тем самым опять будет открыт новый политический сезон.", 3,2)
                                      ';
                                      
    if(!mysqli_query($dbd, $ins_str)){
      echo 'Ошибка добавления в таблицу stat '. mysqli_error($dbd).'<br>';    
    }else{
      echo 'Таблица stat - создана!<br>';
    }
  }

 mysqli_query($dbd, 'drop database if exists cms_log ');
 mysqli_query($dbd, 'create database cms_log ');
 
$str_sql = 'create table cms_log.userlog 
		(id int unsigned primary key auto_increment
		, user_id mediumint unsigned
		, tbl_nm char(50) default ""
		, oper char(50) default ""
		, dt_oper timestamp default current_timestamp )';
		
  if(!mysqli_query($dbd,$str_sql)){
    echo 'Ошибка создания таблицы userlog '. mysqli_error($dbd).'<br>';
  }else{
    echo 'Таблица userlog - создана!<br>';
  }  
 
  
$str_sql = 'create table cms_log.kat_stat_log 
		like kat_stat';
  if(!mysqli_query($dbd,$str_sql)){
    echo 'Ошибка создания таблицы kat_stat_log '. mysqli_error($dbd).'<br>';
  }else{
	$str_sql = "alter table cms_log.kat_stat_log 
		  drop primary key
		, change id id mediumint unsigned
		, drop index name
		, add log_id int unsigned
		, add key(log_id) ";
	  
    if(!mysqli_query($dbd, $str_sql )){
      echo 'Ошибка в таблице  '. mysqli_error($dbd).'<br>';    
    }else{
      echo 'Таблица kat_stat_log - создана!<br>';
    }
  }  


  $str_sql = 'create trigger ins_kat_stat 
					after insert on kat_stat
		for each row
			begin
				insert into cms_log.userlog
					set tbl_nm="kat_stat"
					, user_id = new.user_id
					, oper="insert";
				select last_insert_id() into @last;
				insert into cms_log.kat_stat_log
					(id, log_id, name, prim, user_id) values 
					(new.id, @last, new.name, new.prim, new.user_id);
		
			end;';
  if(!mysqli_query($dbd,$str_sql)){
    echo 'Ошибка создания триггера kat_stat_log '. mysqli_error($dbd).'<br>';
  }else{
	echo 'Все ОК!';
  }

  

  $str_sql = 'create trigger upd_kat_stat 
					before update on kat_stat
		for each row
			begin
				insert into cms_log.userlog
					set tbl_nm="kat_stat"
					, user_id = new.user_id
					, oper="update";
				select last_insert_id() into @last;
				insert into cms_log.kat_stat_log
					(id, log_id, name, prim, user_id) values 
					(old.id, @last, old.name, old.prim, old.user_id)
					,(new.id, @last, new.name, new.prim, new.user_id);
		
			end;';
  if(!mysqli_query($dbd,$str_sql)){
    echo 'Ошибка создания триггера upd_kat_stat '. mysqli_error($dbd).'<br>';
  }else{
	echo 'Все ОК!';
  }
  
  
  
  $str_sql = 'create trigger del_kat_stat 
					after delete on kat_stat
		for each row
			begin
				insert into cms_log.userlog
					set tbl_nm="kat_stat"
					, user_id = new.user_id
					, oper="delete";
				select last_insert_id() into @last;
				insert into cms_log.kat_stat_log
					(id, log_id, name, prim, user_id) values 
					(old.id, @last, old.name, old.prim, old.user_id);
		
			end;';
  if(!mysqli_query($dbd,$str_sql)){
    echo 'Ошибка создания триггера del_kat_stat '. mysqli_error($dbd).'<br>';
  }else{
	echo 'Все ОК!';
  }
  
  
  
// *** Функция соединения с БД   ***

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