# Leffalippujaerjestelmae -tietokantasovellus

Yleisiä linkkejä:

* [Linkki sovellukseeni](http://ruupert.kapsi.fi/tsoha2015/)
* [Linkki dokumentaatiooni](http://ruupert.kapsi.fi/tsoha2015/doc/tsoha2015-fi.pdf)

Ktunnus: admin
Salasana: admin1


## Työn aihe

Harjoitustyön aiheeksi on otettu elokuvalippujärjestelmä, jossa käyttäjät voivat oikeuksista riippuen joko varata lippuja ja perua omia varauksiaan tai lisätä, muokata ja poistaa elokuvia, elokuvateattereita ja niiden näytöksiä. Toteutetaan PHP-webohjelmointikiellellä käyttäen PostgreSQL-tietokantaa tietovarastona.


2015-09-06
Päädyttyäni lukemaan vähän ohjeita lisää, niin löytyi githubista Tsoha/Tsoha-Bootstrap, jota voi käyttää. Ehkä otan sen käyttöön ja toteutan sen toimintalogiikan mukaisesti tämän projektin. Jos, niin kokeillaan sitten siinä vaiheessa sijoittaa se tämän projektin omaan kehityshaaraan ja jatkaa siitä eteenpäin.

2015-09-12
Testaan, että miten tän sai populoitua tsoha-bootstrapin tiedostoilla. Vaihdettu tsoha-boostrap ensisijaiseks branchiks. En keksinyt miten voisi forkata suoraan https://github.com/Tsoha/Tsoha-Bootstrap omaks branchiks tähän repoon.

2015-09-13
Oikaistu hieman. Kaikista sivuista ei tullut staattista näytekappaletta. Jokatapauksessa ne mitä on ovat:

http://ruupert.kapsi.fi/tsoha2015/doc/pages/movies-edit.html
http://ruupert.kapsi.fi/tsoha2015/doc/pages/movies-new.html

Ja loput toiminnassa:

http://ruupert.kapsi.fi/tsoha2015/movie
http://ruupert.kapsi.fi/tsoha2015/movie/1/show

2015-09-27
Jotakuinkin toimii nyt elokuvien lisäys, muokkaus ja poistaminen. Käyttäjällä 'admin' pääsee klikkailemaan.