DROP DATABASE IF EXISTS CS325;

CREATE DATABASE CS325;

USE CS325;

DROP TABLE IF EXISTS USERS;
DROP TABLE IF EXISTS CONTACTS;
DROP TABLE IF EXISTS LISTS;
DROP TABLE IF EXISTS LIST_POINTS;

CREATE TABLE USERS(
USER_ID	DECIMAL,
USER_LOGIN	VARCHAR(20),
USER_NAME	VARCHAR(20),
USER_PASSWORD	VARCHAR(200),
USER_ROLE	VARCHAR(20),
CONSTRAINT USER_PK PRIMARY KEY (USER_ID)
);

CREATE TABLE CONTACTS(
CONTACT_ID	DECIMAL,
CONTACT_NAME	VARCHAR(20),
CONTACT_LNAME	VARCHAR(20),
CONTACT_PHONE	DECIMAL,
CONTACT_ADDRESS	VARCHAR(20),
CONTACT_BIRTHDAY	DATE,
USER_CONTACTS	VARCHAR(20),
CONSTRAINT CONTACT_PK PRIMARY KEY (CONTACT_ID)
);

CREATE TABLE LISTS(
LIST_ID	DECIMAL,
LIST_NAME	VARCHAR(20),
USER_LISTS	VARCHAR(20),
CONSTRAINT LIST_PK PRIMARY KEY (LIST_ID)
);

CREATE TABLE LIST_POINTS(
POINT_ID	DECIMAL,
POINT_CONTENT	VARCHAR(20),
LIST_ID	DECIMAL,
CONSTRAINT LIST_POINTS_PK PRIMARY KEY (POINT_ID)
);

ALTER TABLE LIST_POINTS add
CONSTRAINT LIST_POINTS_FK FOREIGN KEY (LIST_ID) REFERENCES LISTS (LIST_ID);

insert into USERS values (1, 'Basil', 'Basil Pritchard', 'Basil123', 'Admin');
insert into USERS values (2, 'Agustin', 'Agustin Ogilvy', 'Agustin123', 'User');
insert into USERS values (3, 'Pepito', 'Pepito Crum', 'Pepito123', 'Admin');
insert into USERS values (4, 'Toby', 'Toby Sandoval', 'Toby123', 'Admin');
insert into USERS values (5, 'Artie', 'Artie Rolling', 'Artie123', 'User');
insert into USERS values (6, 'Calvin', 'Calvin Shirley', 'Calvin123', 'Admin');
insert into USERS values (7, 'Ollie', 'Ollie Stahl', 'Ollie123', 'Admin');
insert into USERS values (8, 'Milford', 'Milford Pollock', 'Milford123', 'Admin');
insert into USERS values (9, 'Lee', 'Lee Krause', 'Lee123', 'User');
insert into USERS values (10, 'Zachariah', 'Zachariah Waite', 'Zachariah123', 'Admin');

insert into CONTACTS values (1, 'Billy', 'Wallace', 6974185436, '398 Summers ', '22-May-1991', 'Calvin');
insert into CONTACTS values (2, 'Joel', 'Denny', 6923583634, '1 Emerice Calvin', '23-Mar-1984', 'Toby');
insert into CONTACTS values (3, 'Nathaniel', 'Chin', 6977259744, '196 Beckham ', '12-Feb-1976', 'Zachariah');
insert into CONTACTS values (4, 'Stanley', 'Mcmahon', 6951628283, '63 Mcclendon ', '23-Jul-1975', 'Ollie');
insert into CONTACTS values (5, 'Mitchel', 'Goode', 6983729782, '399 Kidd Basil', '22-May-1977', 'Pepito');
insert into CONTACTS values (6, 'Danilo', 'Esquivel', 6983351131, '207 Trent ', '10-Dec-1965', 'Zachariah');
insert into CONTACTS values (7, 'Delmer', 'Tovar', 6903592745, '389 Papas Lee', '30-Nov-1969', 'Basil');
insert into CONTACTS values (8, 'Ivory', 'Ring', 6952355783, '141 Sumner Lee', '05-Mar-1982', 'Basil');
insert into CONTACTS values (9, 'Wilford', 'Rosenkrantz', 6963553213, '3 Mansfield ', '06-Jan-1969', 'Zachariah');
insert into CONTACTS values (10, 'Emory', 'Kline', 6945213267, '250 Doyle ', '20-May-1984', 'Artie');
insert into CONTACTS values (11, 'Carmen', 'Youngblood', 6914809716, '395 Mccray Basil', '30-Mar-1988', 'Calvin');
insert into CONTACTS values (12, 'Emmitt', 'Cummings', 6957619274, '155 Jacobson ', '25-Jul-1987', 'Calvin');
insert into CONTACTS values (13, 'Philippe', 'Ackerman', 6972103502, '4 Lindsay Lee', '04-Dec-1986', 'Ollie');
insert into CONTACTS values (14, 'Dwight', 'Engle', 6942059307, '414 Ricks Basil', '29-Feb-1972', 'Lee');
insert into CONTACTS values (15, 'Elias', 'Corbett', 6903068706, '118 Bowles Ollie', '14-Mar-1992', 'Milford');
insert into CONTACTS values (16, 'Scottie', 'Field', 6942665212, '386 Guevara ', '16-Jan-1987', 'Zachariah');
insert into CONTACTS values (17, 'Phil', 'Neely', 6968321188, '133 Proust ', '04-Dec-1962', 'Zachariah');
insert into CONTACTS values (18, 'Jeremiah', 'Navarro', 6919913824, '428 Machiels ', '22-Sep-1992', 'Ollie');
insert into CONTACTS values (19, 'Randy', 'Harrell', 6966613513, '45 Wong ', '11-Aug-1970', 'Zachariah');
insert into CONTACTS values (20, 'Emery', 'Barber', 6993851916, '492 Barlow ', '18-Apr-1987', 'Calvin');
insert into CONTACTS values (21, 'Ridley', 'Roberts', 6901807135, '332 Bernier ', '07-Aug-1979', 'Pepito');
insert into CONTACTS values (22, 'Rodrigo', 'Tang', 6929215877, '211 Gutierrez Ollie', '16-Nov-1985', 'Basil');
insert into CONTACTS values (23, 'Jon', 'Dover', 6913913137, '61 Joseph ', '07-Oct-1979', 'Zachariah');
insert into CONTACTS values (24, 'Eldon', 'Galis', 6976717098, '286 Golden Lee', '19-May-1961', 'Basil');
insert into CONTACTS values (25, 'Abdullah', 'Bridges', 6923441987, '217 Albright ', '27-May-1963', 'Lee');
insert into CONTACTS values (26, 'Miles', 'Butcher', 6906071915, '164 Shea ', '04-Apr-1967', 'Ollie');
insert into CONTACTS values (27, 'Reid', 'Sellers', 6964176310, '113 Becker ', '28-Feb-1963', 'Basil');
insert into CONTACTS values (28, 'Benjamin', 'Blake', 6911295207, '218 Westbrook ', '28-Jul-1965', 'Lee');
insert into CONTACTS values (29, 'Timmy', 'Gunn', 6977169488, '35 Villegas ', '25-Feb-1977', 'Ollie');
insert into CONTACTS values (30, 'Tracey', 'Maurer', 6921178198, '471 Patterson ', '05-Dec-1992', 'Pepito');

insert into LISTS values (1, 'Givens', 'Toby');
insert into LISTS values (2, 'Draper', 'Toby');
insert into LISTS values (3, 'Gross', 'Lee');
insert into LISTS values (4, 'Francis', 'Agustin');
insert into LISTS values (5, 'Pate', 'Calvin');
insert into LISTS values (6, 'Carey', 'Calvin');
insert into LISTS values (7, 'Murillo', 'Basil');
insert into LISTS values (8, 'Zavala', 'Calvin');
insert into LISTS values (9, 'Denny', 'Calvin');
insert into LISTS values (10, 'Cullen', 'Milford');
insert into LISTS values (11, 'Guevara', 'Toby');
insert into LISTS values (12, 'Cooper', 'Agustin');
insert into LISTS values (13, 'Moran', 'Artie');
insert into LISTS values (14, 'Winslow', 'Toby');
insert into LISTS values (15, 'Beatty', 'Agustin');

insert into LIST_POINTS values (1, 'k', 1);
insert into LIST_POINTS values (2, '!', 3);
insert into LIST_POINTS values (3, '%', 5);
insert into LIST_POINTS values (4, 'P', 15);
insert into LIST_POINTS values (5, 'P', 14);
insert into LIST_POINTS values (6, 'V', 5);
insert into LIST_POINTS values (7, 'T', 12);
insert into LIST_POINTS values (8, 'i', 14);
insert into LIST_POINTS values (9, '$', 2);
insert into LIST_POINTS values (10, '%', 4);
insert into LIST_POINTS values (11, '5', 8);
insert into LIST_POINTS values (12, '4', 15);
insert into LIST_POINTS values (13, 'c', 5);
insert into LIST_POINTS values (14, 'C', 5);
insert into LIST_POINTS values (15, '~', 3);
insert into LIST_POINTS values (16, 'T', 9);
insert into LIST_POINTS values (17, 'Y', 12);
insert into LIST_POINTS values (18, 'G', 14);
insert into LIST_POINTS values (19, 'e', 14);
insert into LIST_POINTS values (20, 'l', 7);
insert into LIST_POINTS values (21, '`', 12);
insert into LIST_POINTS values (22, 'U', 12);
insert into LIST_POINTS values (23, 'Ä', 15);
insert into LIST_POINTS values (24, 'w', 14);
insert into LIST_POINTS values (25, 't', 8);
insert into LIST_POINTS values (26, '=', 14);
insert into LIST_POINTS values (27, 't', 11);
insert into LIST_POINTS values (28, 'N', 10);
insert into LIST_POINTS values (29, 'a', 7);
insert into LIST_POINTS values (30, '3', 14);
insert into LIST_POINTS values (31, '', 15);
insert into LIST_POINTS values (32, 's', 14);
insert into LIST_POINTS values (33, 'm', 8);
insert into LIST_POINTS values (34, '#', 11);
insert into LIST_POINTS values (35, 'z', 10);
insert into LIST_POINTS values (36, 'K', 8);
insert into LIST_POINTS values (37, '4', 14);
insert into LIST_POINTS values (38, '5', 4);
insert into LIST_POINTS values (39, 'w', 5);
insert into LIST_POINTS values (40, '/', 9);