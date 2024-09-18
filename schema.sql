/**
@author evilnapsis
@brief Modelo de base de datos
**/
create database katanapro;
use katanapro;

create table user(
	id int not null auto_increment primary key,
	name varchar(50) not null,
	lastname varchar(50) not null,
	username varchar(50),
	email varchar(255) not null,
	password varchar(60) not null,
	is_active boolean not null default 1,
	is_admin boolean not null default 0,
	created_at datetime not null
);


insert into user(name,lastname,username,email,password,is_active,is_admin,created_at) value("Admin","","admin","",sha1(md5("admin")),1,1,NOW());

create table country (
	id int not null auto_increment primary key,
	name varchar(200) not null
);

insert into country (name) value ("Argentina");
insert into country (name) value ("Chile");
insert into country (name) value ("Colombia");
insert into country (name) value ("España");
insert into country (name) value ("Mexico");


create table unit (
	id int not null auto_increment primary key,
	name varchar(200) not null
);

insert into unit (name) value ("Pieza");
insert into unit (name) value ("Kit");
insert into unit (name) value ("Juego");
insert into unit (name) value ("Caja");



create table category (
	id int not null auto_increment primary key,
	image varchar(255),
	name varchar(200) not null,
	short_name varchar(200) not null,
	category_id int,
	in_home boolean not null default 0,
	in_menu boolean not null default 0,
	is_active boolean not null default 0
);

insert into category (name,short_name,is_active) value ("Basico","basico",1);


create table ship (
	id int not null auto_increment primary key,
	name varchar(200) not null,
	description varchar(200) not null,
	price double not null,
	is_active boolean not null default 0
);

insert into ship (name,description,price,is_active) value ("Default","Default",0,1);



create table product (
	id int not null auto_increment primary key,
	short_name varchar(20) not null,
	name varchar(200) not null,
	code varchar(200) not null,
	description varchar(1000) not null,
	offer_txt varchar(1000) not null,
	image varchar(255),	
	link varchar(255),	
	is_featured boolean not null default 0,
	is_public boolean not null default 0,
	in_existence boolean not null default 0,
	created_at datetime not null,
	order_at datetime not null,
	price float not null,
	price_offer float,
	category_id int not null,
	subcategory_id int,
	unit_id int not null,
	/** for SEO **/
	meta_title varchar(100),
	meta_description varchar(255),
	meta_keywords varchar(100),
	foreign key(unit_id) references unit(id),
	foreign key(category_id) references category(id),
	foreign key(subcategory_id) references category(id)
);

create table product_image(
	id int not null auto_increment primary key,
	title varchar(200) not null,
	description varchar(1000) not null,
	src varchar(255),	
	product_id int not null,
	foreign key(product_id) references product(id)
);


create table coupon (
	id int not null auto_increment primary key,
	name varchar(200) not null,
	description varchar(1000) not null,
	product_id int,
	val double,
	kind int not null default 1, /** 1.- precio, 2.- porcentaje **/
	is_multiple boolean not null default 0,
	is_active boolean not null default 1,
	start_at date not null,
	finish_at date not null,
	created_at datetime not null,
	foreign key(product_id) references product(id)
);


create table product_view(
	id int not null auto_increment primary key,
	viewer_id int,
	product_id int null,
	created_at datetime not null,
	realip varchar(16) not null,
	foreign key (viewer_id) references user(id),
	foreign key (product_id) references product(id)
);

create table client (
	id int not null auto_increment primary key,
	name varchar(50) not null,
	lastname varchar(50) not null,
	email varchar(255) not null,
	phone varchar(255) not null,
	address varchar(255) not null,
	password varchar(60) not null,
	is_active boolean not null default 1,
	created_at datetime not null
);

create table paymethod(
	id int not null auto_increment primary key,
	short_name varchar(100),
	name varchar(200) not null,
	is_active boolean not null default 0	
);

insert into paymethod(short_name,name) value ("paypal", "Paypal"),("bank", "Deposito Bancario"),("deliver", "Contra entrega"),("mp", "MercadoPago"),("oxxo", "Oxxo Pay"),("conekta", "Pago con Tarjeta");


create table status (
	id int not null auto_increment primary key,
	name varchar(200) not null
);

insert into status (name) value ("Pendiente");
insert into status (name) value ("Pagado");
insert into status (name) value ("Cancelado");
/* 3 estados extra*/
insert into status (name) value ("Enviado");
insert into status (name) value ("Finalizado");


create table buy (
	id int not null auto_increment primary key,
	k varchar(20) not null,
	code varchar(20) not null,
	oxxo_code varchar(255),
	person_name varchar(255),
	person_phone varchar(255),
	person_address varchar(255),
	person_city varchar(255),
	person_zip varchar(255),
	ship_id int not null,
	client_id int not null,
	coupon_id int,
	status_id int not null,
	created_at datetime not null,
	paymethod_id int not null,
	foreign key(paymethod_id) references paymethod(id),
	foreign key(coupon_id) references coupon(id),
	foreign key(client_id) references client(id),
	foreign key(status_id) references status(id),
	foreign key (ship_id) references ship(id)
);

create table buy_product(
	id int not null auto_increment primary key,
	buy_id int not null,
	product_id int not null,
	price double not null,
	q int not null,
	foreign key(buy_id) references buy(id),
	foreign key(product_id) references product(id)
);

create table history(
	id int not null auto_increment primary key,
	buy_id int not null,
	status_id int not null,
	created_at datetime not null,
	foreign key(buy_id) references buy(id),
	foreign key(status_id) references status(id)
);

create table slide (
	id int not null auto_increment primary key,
	title varchar(200) not null,
	image varchar(255),	
	is_public boolean not null default 0,
	position int not null,
	created_at datetime not null
);
/**
kind:
1- texto
2- entero
3- checkbox
4- reference
**/


create table configuration(
	id int not null auto_increment primary key,
	name varchar(100) not null unique,
	label varchar(200) not null,
	kind int,
	val text,
	cfg_id int default 1
);

insert into configuration(name,label,kind,val) value ("general_main_title","Titulo Principal",1,"KATANA");
insert into configuration(name,label,kind,val) value ("general_main_email","Email Principal",1,"tuemail@tudominio.com");
insert into configuration(name,label,kind,val) value ("general_country","Pais",1,"MX");
insert into configuration(name,label,kind,val) value ("general_coin","Moneda",1,"$");
insert into configuration(name,label,kind,val) value ("general_iva_txt","Impuesto Texto",1,"Impuesto");
insert into configuration(name,label,kind,val) value ("general_iva","Impuesto (%)",2,0);
insert into configuration(name,label,kind,val) value ("general_img_default","Imagen Default",1,"res/img/default.png");
insert into configuration(name,label,kind,val) value ("general_base","URL del sistema",1,"http://localhost/katana-pro/");
insert into configuration(name,label,kind,val) value ("general_whatsapp","Numero de Whatsapp",1,"5219371331142");
/* for paypal */
insert into configuration(name,label,kind,val) value ("paypal_business","Busines Email",1,"");
insert into configuration(name,label,kind,val) value ("paypal_currency","Currency",1,"USD");
insert into configuration(name,label,kind,val) value ("paypal_cursymbol","Symbol",1,"&usd;");
insert into configuration(name,label,kind,val) value ("paypal_location","Location",1,"US");
insert into configuration(name,label,kind,val) value ("paypal_returnurl","Return URL",1,"http://localhost/katana-pro/?action=ppdone");
insert into configuration(name,label,kind,val) value ("paypal_returntxt","Return Text",1,"Pago Realizado Exitosamente!");
insert into configuration(name,label,kind,val) value ("paypal_cancelurl","Cancel URL",1,"http://localhost/katana-pro/?action=ppcancel");
/* for mercadopago */
insert into configuration(name,label,kind,val) value ("mp_id","MercadoPago Cliente ID",1,"");
insert into configuration(name,label,kind,val) value ("mp_secret","MercadoPago Cliente Secret",1,"");

/*
insert into configuration(name,label,kind,val) value ("paypal_shipping","Shipping",1,"");
insert into configuration(name,label,kind,val) value ("paypal_custom","Custom",1,"");
insert into configuration(name,label,kind,val) value ("paypal_note","Nota",1,"");
*/
/* for bank */
insert into configuration(name,label,kind,val) value ("bank_titular","Titular de la cuenta",1,"");
insert into configuration(name,label,kind,val) value ("bank_name","Nombre del Banco",1,"");
insert into configuration(name,label,kind,val) value ("bank_account","Numero de Cuenta",1,"");
insert into configuration(name,label,kind,val) value ("bank_card","Numero de Tarjeta",1,"");
/* for oxxpay */
insert into configuration(name,label,kind,val) value ("oxxo_apikey","Oxxo pay API key",1,"");
/* for conekta pay */
insert into configuration(name,label,kind,val) value ("conekta_public","Clave Conekta Publica",1,"");
insert into configuration(name,label,kind,val) value ("conekta_secret","Clave Conekta Secreta",1,"");

/* for version 4.5 */

create table rating(
	id int not null auto_increment primary key,
	rating float not null,
	comment varchar(512) not null,
	client_id int not null,
	status_id int default 0,/* 0. hidden, 1. public */
	created_at datetime not null,
	product_id int not null,
	foreign key(product_id) references product(id),
	foreign key(client_id) references client(id)
);

create table question(
	id int not null auto_increment primary key,
	question_id int, /* For answer */
	comment varchar(512) not null,
	client_id int ,
	user_id int ,
	status_id int default 0,/* 0. hidden, 1. public */
	created_at datetime not null,
	product_id int not null,
	foreign key(question_id) references question(id),
	foreign key(product_id) references product(id),
	foreign key(user_id) references user(id),
	foreign key(client_id) references client(id)
);

