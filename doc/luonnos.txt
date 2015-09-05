# -tsoha2015

Leffalippujaerjestelmae, jossa on kaksi käyttäjätyyppiä (admin+user)ja jotka pystyy joko tekemään kaikkea tai sitten vain varaamaan lippuja lisättyihin näytöksiin. Admin-tason käyttäjä voi lisätä teattereita ja elokuvia, sekä näytöksiä niihin. Näytöksiin voi kaikki käyttäjät varata lippuja. Näytöksiä ei voi poistaa, jos niihin on tehty varauksia. Elokvuia tai teattereita ei voi poistaa, jos on näytöksiä, jotka niitä käyttää. Käyttäjät eivät saa unohtaa salasanojaan.


Luonnos:

theater (id: primary id_seq_theater tjsp, created_at:datetime, modified_at:datetime, name:text, description:text, image:binary, seats:integer)
movie (id, created_at:datetime, modified_at:datetime, name:text, descritpion:text, image:binary, duration:integer)
timetable (id, created_at:datetime, modified_at:datetime, start_at:datetime, theater_id, movie_id)
user (id, created_at:datetime, modified_at:datetime, privilege:integer default(0), username:text unique, password:md5sum tms, firstname:text, lastname:text, address:text, jne) passun suolaus otetaan tosta creatd_at elikkä md5(passu + created_at) 
reservation (id, created_at:datetime, modified_at:datetime, user_id, timetable_id, qty)

taulut theater, movie ja timetable ja niiden toiminnallisuus on itsenäistä eli ne eivät suoraan vaadi, että taulut user ja reservation olisi olemassa. 

 
