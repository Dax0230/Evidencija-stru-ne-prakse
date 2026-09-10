CREATE DATABASE `evidencija_prakse` CHARACTER SET utf8 COLLATE utf8_general_ci;

create table `evidencija_prakse`.`KORISNIK`
(
   IDKORISNIKA    int NOT NULL AUTO_INCREMENT PRIMARY KEY,
   PREZIME        varchar(50) not null,
   IME            varchar(50) not null,
   BROJINDEKSA    varchar(20) null,
   FAKULTET       varchar(100) null,
   STUDIJSKIPROGRAM varchar(100) null,
   TELEFON        varchar(30) null,
   EMAIL          varchar(100) not null,
   SIFRA          varchar(255) not null,
   ULOGA          varchar(20) not null
);

create table `evidencija_prakse`.`SIFARNIKSTATUSA`
(
   SIFRA   varchar(10) NOT NULL PRIMARY KEY,
   NAZIV   varchar(50) not null
);

create table `evidencija_prakse`.`PRIJAVAZAPRAKSU`
(
   IDPRIJAVE       int NOT NULL AUTO_INCREMENT PRIMARY KEY,
   BROJPRIJAVE     varchar(30) not null,
   DATUMPRIJAVE    date not null,
   IDKORISNIKA     int not null,
   SIFRASTATUSA    varchar(10) not null,
   DATUMIZMENE     datetime null
);

create table `evidencija_prakse`.`STAVKAPRIJAVE`
(
   IDSTAVKE          int NOT NULL AUTO_INCREMENT PRIMARY KEY,
   IDPRIJAVE         int not null,
   KOMPANIJA         varchar(100) not null,
   POZICIJA          varchar(100) not null,
   TRAJANJEPRAKSE    varchar(50) not null,
   NAPOMENA          varchar(255) null
);

alter table `evidencija_prakse`.`PRIJAVAZAPRAKSU`
   add constraint FK_PRIJAVA_KORISNIK foreign key (IDKORISNIKA)
   references `evidencija_prakse`.`KORISNIK`(IDKORISNIKA) on delete cascade on update cascade;

alter table `evidencija_prakse`.`PRIJAVAZAPRAKSU`
   add constraint FK_PRIJAVA_STATUS foreign key (SIFRASTATUSA)
   references `evidencija_prakse`.`SIFARNIKSTATUSA`(SIFRA) on delete restrict on update cascade;

alter table `evidencija_prakse`.`STAVKAPRIJAVE`
   add constraint FK_STAVKA_PRIJAVA foreign key (IDPRIJAVE)
   references `evidencija_prakse`.`PRIJAVAZAPRAKSU`(IDPRIJAVE) on delete cascade on update cascade;

INSERT INTO `evidencija_prakse`.`SIFARNIKSTATUSA` (SIFRA, NAZIV) VALUES ('PODN', 'Podneta');
INSERT INTO `evidencija_prakse`.`SIFARNIKSTATUSA` (SIFRA, NAZIV) VALUES ('OBRD', 'U obradi');
INSERT INTO `evidencija_prakse`.`SIFARNIKSTATUSA` (SIFRA, NAZIV) VALUES ('PRIH', 'Prihvacena');
INSERT INTO `evidencija_prakse`.`SIFARNIKSTATUSA` (SIFRA, NAZIV) VALUES ('ODBI', 'Odbijena');
INSERT INTO `evidencija_prakse`.`SIFARNIKSTATUSA` (SIFRA, NAZIV) VALUES ('REAL', 'Realizovana');
