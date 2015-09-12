-- Lisää INSERT INTO lauseet tähän tiedostoon
insert into movie (name,description,duration) values ('blade runner', 'aika jepa leffa', 129);
insert into movie (name,description,duration) values ('zardoz', 'aika jepa leffa kans', 149);
insert into movie (name,description,duration) values ('running man', 'aika ok leffa', 121);
insert into movie (name,description,duration) values ('total recall', 'tosi jees leffa', 176);
insert into theater (name,description,seats) values ('kinopalatsi','aika iso teatteri','145');
insert into theater (name,description,seats) values ('tennispalatsi','iso teatteri','249');
insert into theater (name,description,seats) values ('biorex','vanha leffateatteri','85');
insert into users (admin,username,pw_hash,name,lastname) values (true, 'admin', 'admin1', 'Pentti', 'Pääkäyttäjä');
insert into users (admin,username,pw_hash,name,lastname) values (false, 'rkeskiva', 'keskivaanto', 'Raimo', 'Keskivääntö');
