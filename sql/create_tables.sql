CREATE TABLE Jasen (
    jasen_id SERIAL PRIMARY KEY,
    nimi varchar(50) NOT NULL,
    sala varchar(50) NOT NULL,
    email varchar(50) NOT NULL UNIQUE,
    katuosoite varchar(50),
    posti varchar(50),
    puhelin varchar(50),
    syntyma date NOT NULL,
    huoltaja varchar(50),
    laji text[],
    rek_aika timestamp,
    status varchar(10) DEFAULT 'Kesken',
    skilstatus boolean DEFAULT false,
    seura varchar(50)
);

CREATE TABLE Hallitus (
    hal_id SERIAL PRIMARY KEY,
    vuosi integer NOT NULL UNIQUE
);

CREATE TABLE Kokous (
    kokous_id SERIAL PRIMARY KEY,
    pvm date NOT NULL,
    aika varchar(5) NOT NULL,
    paikka varchar(25),
    nimi varchar(50),
    tyyppi varchar(20) NOT NULL,
    muuta varchar(200),
    hal_id integer, 
    FOREIGN KEY (hal_id) REFERENCES Hallitus
);

CREATE TABLE Hallitus_has_Jasen (
    hal_id integer NOT NULL,
    jasen_id integer NOT NULL,
    PRIMARY KEY (hal_id, jasen_id),
    FOREIGN KEY (hal_id) REFERENCES Hallitus
);

CREATE TABLE Kokous_has_Jasen (
    kokous_id integer NOT NULL,
    jasen_id integer NOT NULL,
    PRIMARY KEY (kokous_id, jasen_id),
    FOREIGN KEY (kokous_id) REFERENCES Kokous
);

CREATE TABLE Dokumentti (
    dok_id SERIAL PRIMARY KEY,
    nimi varchar(50) NOT NULL,
    pvm date NOT NULL,
    url text NOT NULL,
    kokous_id integer NOT NULL,
    FOREIGN KEY (kokous_id) REFERENCES Kokous
);

CREATE TABLE Jasenmaksu (
    maksu_id SERIAL PRIMARY KEY,
    vuosi numeric NOT NULL UNIQUE,
    maara_lapsi numeric NOT NULL,
    maara_aikuinen numeric NOT NULL,
    maara_skil numeric NOT NULL,
    maara_liity numeric NOT NULL
);

CREATE TABLE Jasen_has_Jasenmaksu (
    jasen_id integer NOT NULL,
    jasenmaksu_id integer NOT NULL,
    maara numeric NOT NULL,
    PRIMARY KEY (jasen_id, jasenmaksu_id),
    FOREIGN KEY (jasen_id) REFERENCES Jasen,
    FOREIGN KEY (jasenmaksu_id) REFERENCES Jasenmaksu
);