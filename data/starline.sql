DROP TABLE IF EXISTS user;

CREATE TABLE IF NOT EXISTS user (
  id int(11) NOT NULL AUTO_INCREMENT,
  login varchar(50) NOT NULL,
  password varchar(50) NOT NULL,
  email varchar(50) NOT NULL,
  phone varchar(20) NOT NULL,
  name varchar(50) NOT NULL,
  sex int(11) NOT NULL,
  date_birth datetime NOT NULL,
  attempts int(11) NOT NULL,
  date_attempt datetime NOT NULL,
  date_created datetime NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY login (login,email,phone),
  UNIQUE KEY email (email,phone)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

