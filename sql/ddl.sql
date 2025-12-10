CREATE DATABASE IF NOT EXISTS buke_tours_db;

USE buke_tours_db;

DROP TABLE IF EXISTS tour;

-- 1. Crear la tabla tours
CREATE TABLE tour (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  sku VARCHAR(130) NOT NULL,
  title VARCHAR(150) NOT NULL,
  location VARCHAR(150) NOT NULL,
  price_usd DECIMAL(10,2) NOT NULL,
  cupon_code VARCHAR(50) NULL,
  cupon_discount INT DEFAULT 0,
  rating DECIMAL(3,1) NOT NULL,
  duration_hours DECIMAL(4,1) NOT NULL,
  discount INT NOT NULL,
  img VARCHAR(500) NOT NULL,
  description TEXT NOT NULL,
  iframe TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  adults_limit   INT UNSIGNED NOT NULL DEFAULT 0,
  children_limit INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY uq_tours_sku (sku)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

  DROP TABLE IF EXISTS customer;

  CREATE TABLE customer (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    full_name VARCHAR(200) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(120) NOT NULL,
    country VARCHAR(120) NOT NULL,
    passport VARCHAR(220) NOT NULL,
    lang VARCHAR(120) NOT NULL,
    genre ENUM('Masculino','Femenino') NOT NULL,
    home_addres VARCHAR(220) NOT NULL,
    city VARCHAR(220) NOT NULL,
    province VARCHAR(230) NOT NULL,
    zip_code VARCHAR(100) NOT NULL,
    birth_date DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_customer_email (email),
    UNIQUE KEY uq_customer_passport (passport),
    PRIMARY KEY (id)
  ) ENGINE=InnoDB
    DEFAULT CHARSET=utf8mb4
    COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS reservation;

-- 2. Crear la tabla reservation
CREATE TABLE IF NOT EXISTS  reservation(
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  full_name VARCHAR(200) NOT NULL,
  email VARCHAR(150) NOT NULL,
  telephone VARCHAR(120) NOT NULL,
  country VARCHAR(120) NOT NULL,
  passport VARCHAR(220) NOT NULL,
  adults INT NOT NULL,
  children INT NULL,
  idioma VARCHAR(120) NOT NULL,
  breakfast BOOLEAN NULL DEFAULT FALSE,
  lunch BOOLEAN NULL DEFAULT FALSE,
  dinner BOOLEAN NULL DEFAULT FALSE,
  transport BOOLEAN NULL DEFAULT FALSE,
  travel_insurance BOOLEAN NULL DEFAULT FALSE,
  photo_package BOOLEAN NULL DEFAULT FALSE,
  home_address  VARCHAR(220) NOT NULL,
  city VARCHAR(220) NOT NULL,
  province VARCHAR(230) NOT NULL,
  postal_code VARCHAR(100) NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  userId INT UNSIGNED NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_reservation_customer
      FOREIGN KEY (userId)
      REFERENCES customer(id)
      ON DELETE CASCADE
      ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;




DROP TABLE IF EXISTS reservation_tour;

CREATE TABLE reservation_tour (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,

  reservation_id INT UNSIGNED NOT NULL,
  tour_id        INT UNSIGNED NOT NULL,

  check_in_date  DATE NOT NULL,
  check_out_date DATE NOT NULL,

  adults   INT UNSIGNED NOT NULL DEFAULT 0, -- cantidad reservada
  children INT UNSIGNED NOT NULL DEFAULT 0, -- cantidad reservada

  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (id),

  CONSTRAINT fk_reservation_tour_reservation
    FOREIGN KEY (reservation_id)
    REFERENCES reservation(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  CONSTRAINT fk_reservation_tour_tour
    FOREIGN KEY (tour_id)
    REFERENCES tour(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS feedback;

CREATE TABLE feedback (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  score INT NOT NULL DEFAULT 1,
  tour_id INT UNSIGNED NOT NULL,
  customer_id INT UNSIGNED NOT NULL,
  full_name VARCHAR(200) NOT NULL,
  comment VARCHAR(200) NOT NULL,
  status ENUM('Aprobada','Denegada', 'Pendiente') NOT NULL,
  PRIMARY KEY (id),
   CONSTRAINT fk_feedback_customer_id
    FOREIGN KEY (customer_id)
    REFERENCES customer(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT fk_feedback_tour_id
    FOREIGN KEY (tour_id)
    REFERENCES tour(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

DROP VIEW IF EXISTS customer_purchased_tours;

CREATE VIEW customer_purchased_tours AS
SELECT DISTINCT
  r.userId     AS customer_id,
  t.id         AS tour_id,
  t.title      AS tour_title
FROM reservation r
JOIN reservation_tour rt ON rt.reservation_id = r.id
JOIN tour t              ON t.id = rt.tour_id;

INSERT INTO tour (
  sku,
  title,
  location,
  price_usd,
  cupon_code,
  cupon_discount,
  rating,
  duration_hours,
  discount,
  img,
  description,
  iframe,
  adults_limit,
  children_limit
) VALUES
(
  'cr-monteverde-hanging-bridges',
  'Puentes Colgantes Monteverde',
  'Monteverde, Puntarenas',
  75.00,
  'MONTE_VRD_CR',
  4,
  4.8,
  2.5,
  3,
  '/Buke-Tours/assets/img/tour2.jpg',
  'Caminata guiada por bosque nuboso, observación de aves y biodiversidad única.',
  NULL,
  25,
  10
),
(
  'cr-manuel-antonio-park',
  'Parque Nacional Manuel Antonio',
  'Quepos, Puntarenas',
  65.00,
  'MANUEL_ANT_CR',
  2,
  4.6,
  6.0,
  2,
  'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/16/5a/2f/50/photo0jpg.jpg?w=1000&h=-1&s=1',
  'Tour por senderos y playas con posibilidad de ver monos, perezosos y diversidad marina.',
  NULL,
  45,
  15
),
(
  'cr-tortuguero-boat',
  'Tour en Bote por Canales de Tortuguero',
  'Limón',
  99.00,
  'LIMON_TORT_CR',
  3,
  4.9,
  4.0,
  5,
  '/Buke-Tours/assets/img/tour3.jpg',
  'Recorrido en bote por canales, avistamiento de fauna y aprendizaje sobre conservación.',
  NULL,
  25,
  10
),
(
  'cr-arenal-zipline',
  'Canopy & Zipline en Arenal',
  'La Fortuna, Alajuela',
  89.00,
  'FORTU_CR',
  3,
  4.7,
  3.5,
  3,
  'https://cdn.pixabay.com/photo/2017/06/13/19/22/tyrolean-2399759_1280.jpg',
  'Aventura de canopy entre selva tropical con vistas al Volcán Arenal y plataformas seguras.',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7846.778461545224!2d-84.64554893119792!3d10.46995075869794!2m3!1f0!2f0!3f0!',
  45,
  20
),
(
  'cr-manuel-antonio',
  'Parque Nacional Manuel Antonio',
  'Quepos, Puntarenas',
  25.00,
  'QUEPOS_CR',
  3,
  4.9,
  5.0,
  2,
  'https://manuelantoniopark.net/wp-content/uploads/2020/03/landing-page-4-scaled.jpg',
  'Senderos, playas de arena blanca y abundante vida silvestre en uno de los parques más visitados del país.',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3929.0572090227285!2d-84.15740692502289!3d9.389434890683676',
  50,
  10
),
(
  'cr-monteverde-cloudforest',
  'Bosque Nuboso de Monteverde',
  'Monteverde, Puntarenas',
  40.00,
  NULL,
  0.00,
  4.9,
  6.0,
  8,
  'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/06/93/b4/50/monteverde-cloud-forest.jpg?w=600&h=-1&s=1',
  'Senderos colgantes, aves quetzales y un ecosistema único entre las nubes.',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3975.1059031874266!2d-84.8167535!3d10.3052498',
  30,
  10
),
(
  'cr-poas-volcano',
  'Volcán Poás',
  'Alajuela, Central Valley',
  15.00,
  NULL,
  0.00,
  4.6,
  2.5,
  7,
  'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3f/Poas_crater.jpg/800px-Poas_crater.jpg',
  'Impresionante cráter activo con lago ácido y miradores panorámicos a 2700 m.s.n.m.',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7874.443275581225!2d-84.233381!3d10.199263',
  40,
  10
),
(
  'cr-riosarapiqui',
  'Rafting Río Sarapiquí',
  'Heredia, Sarapiquí',
  95.00,
  NULL,
  0.00,
  4.8,
  4.5,
  1,
  'https://media-cdn.tripadvisor.com/media/attractions-splice-spp-720x480/10/6f/65/47.jpg',
  'Descenso en balsas inflables por rápidos clase III y IV entre paisajes tropicales.',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7875.217829854918!2d-84.01752!3d10.28822',
  20,
  5
),
(
  'cr-rio-celeste',
  'Catarata Río Celeste',
  'Parque Tenorio, Guanacaste',
  20.00,
  NULL,
  0.00,
  4.9,
  3.0,
  5,
  'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/02/67/45/0f/waterfall.jpg?w=900&h=-1&s=1',
  'El icónico río de color celeste, con una cascada espectacular y sendero natural.',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3968.112885385437!2d-84.9912758!3d10.6952739',
  25,
  10
),
(
  'cr-samara-beach',
  'Playa Sámara',
  'Nicoya, Guanacaste',
  80.00,
  NULL,
  0.00,
  4.7,
  6.0,
  3,
  'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80',
  'Playa familiar ideal para nadar, practicar surf suave y disfrutar del atardecer.',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3969.1796612687585!2d-85.44817!3d9.88167',
  40,
  20
),
(
  'cr-corcovado-hike',
  'Senderismo en Parque Nacional Corcovado',
  'Osa, Puntarenas',
  120.00,
  'CORCOVD_CR',
  3,
  5.0,
  8.0,
  4,
  'https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/Parque_Nacional_Corcovado.JPG/1024px-Parque_Nacional_Corcovado.JPG',
  'Uno de los lugares con mayor biodiversidad del planeta, hábitat del jaguar y tapir.',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3938.305045156768!2d-83.60933!3d8.5347',
  25,
  10
),
(
  'cr-nauyaca-falls',
  'Cataratas Nauyaca',
  'Dominical, Puntarenas',
  120.00,
  'DOMINI_CR',
  2,
  4.8,
  4.0,
  2,
  'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/12/15/d7/9d/img-20180217-133510-largejpg.jpg?w=1000&h=-1&s=1',
  'Dos impresionantes cascadas en medio de la selva, ideales para nadar y tomar fotos.',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3924.9879026989165!2d-83.7341!3d9.2546',
  45,
  10
),
(
  'cr-tamarindo-surf',
  'Clase de Surf en Tamarindo',
  'Tamarindo, Guanacaste',
  65.00,
  'TAMARIND_CR',
  5,
  4.9,
  2.0,
  8,
  'https://res.cloudinary.com/vacationscostarica-com/image/upload/q_auto:eco,c_fill,f_auto,g_center,w_1440,h_730/v1743457516/tamarindo_beach_aerial_view_costa_rica_f7d26d7356',
  'Aprende surf con instructores certificados en una de las playas más famosas del país.',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3968.193823833472!2d-85.841!3d10.2987',
  20,
  5
),
(
  'cr-rincon-de-la-vieja',
  'Volcán Rincón de la Vieja',
  'Liberia, Guanacaste',
  120.00,
  'RINCON_DELVIEJ_CR',
  4,
  4.7,
  5.0,
  4,
  'https://upload.wikimedia.org/wikipedia/commons/thumb/e/ea/Guanacaste_National_Park.jpg/800px-Guanacaste_National_Park.jpg',
  'Parque nacional con pozas termales, fumarolas y senderos entre la selva seca tropical.',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.235171080066!2d-85.3546!3d10.7819',
  60,
  25
),
(
  'cr-puerto-viejo',
  'Puerto Viejo de Talamanca',
  'Caribe Sur, Limón',
  70.00,
  'PUERTO_VIEJ_CR',
  7,
  4.6,
  6.0,
  1,
  'https://mytanfeet.com/wp-content/uploads/2018/01/Things-to-do-in-Puerto-Viejo-beach-hop.jpg',
  'Destino caribeño con playas de arena negra, cultura afrocaribeña y ambiente relajado.',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.2157!2d-82.754!3d9.659',
  60,
  20
),
(
  'cr-cahuita-reef',
  'Snorkel en el Arrecife de Cahuita',
  'Limón, Costa Caribe',
  155.00,
  'CAHUITA_CR',
  4,
  4.8,
  3.0,
  6,
  'https://media-cdn.tripadvisor.com/media/photo-s/09/bc/52/ad/willie-s-tours-cr-day.jpg',
  'Explora los corales del Parque Nacional Cahuita en un tour guiado en bote.',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.2177!2d-82.833!3d9.741',
  40,
  15
),
(
  'cr-cartago-basilica',
  'Basílica de Los Ángeles',
  'Cartago Centro',
  60.00,
  NULL,
  0.00,
  4.9,
  1.0,
  7,
  'https://upload.wikimedia.org/wikipedia/commons/thumb/2/28/Basilica_of_Our_Lady_of_the_Angels%2C_Cartago_01.jpg/800px-Basilica_of_Our_Lady_of_the_Angels%2C_Cartago_01.jpg',
  'Templo icónico de Costa Rica y centro de peregrinación anual en agosto.',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3936.2172!2d-83.920!3d9.863',
  50,
  15
),
(
  'cr-ojochal-gastronomy',
  'Tour Gastronómico en Ojochal',
  'Puntarenas Sur',
  80.00,
  'OJOCHAL_CR',
  4,
  4.9,
  3.0,
  2,
  'https://www.costaricavibes.com/wp-content/uploads/Santa-Teresa-Costa-Rica-19-1024x597.jpg',
  'Descubre la fusión culinaria internacional y local en la capital gastronómica del Pacífico Sur.',
  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3938.182!2d-83.667!3d9.040',
  20,
  10
);

INSERT INTO customer (
  full_name,
  email,
  password_hash,
  phone,
  country,
  passport,
  lang,
  genre,
  home_addres,
  city,
  province,
  zip_code,
  birth_date
) VALUES
('Juan Pérez', 'juan.perez@email.com', 'hash1', '8888-1111', 'Costa Rica', 'A1234567', 'es', 'Masculino', 'Calle 1', 'San José', 'San José', '10101', '1990-01-01'),
('María Rodríguez', 'maria.rodriguez@email.com', 'hash2', '8888-2222', 'Costa Rica', 'B2345678', 'es', 'Femenino', 'Calle 2', 'Alajuela', 'Alajuela', '20101', '1985-02-02'),
('Carlos Jiménez', 'carlos.jimenez@email.com', 'hash3', '8888-3333', 'Costa Rica', 'C3456789', 'es', 'Masculino', 'Calle 3', 'Cartago', 'Cartago', '30101', '1988-03-03'),
('Ana Morales', 'ana.morales@email.com', 'hash4', '8888-4444', 'Costa Rica', 'D4567890', 'es', 'Femenino', 'Calle 4', 'Heredia', 'Heredia', '40101', '1992-04-04'),
('Luis Fernández', 'luis.fernandez@email.com', 'hash5', '8888-5555', 'Costa Rica', 'E5678901', 'es', 'Masculino', 'Calle 5', 'Puntarenas', 'Puntarenas', '50101', '1987-05-05');



INSERT INTO reservation (
  full_name,
  email,
  telephone,
  country,
  passport,
  adults,
  children,
  idioma,
  breakfast,
  lunch,
  dinner,
  transport,
  travel_insurance,
  photo_package,
  home_address,
  city,
  province,
  postal_code,
  total,
  subtotal,
  userId
) VALUES
('Juan Pérez', 'juan.perez@email.com', '8888-1111', 'Costa Rica', 'A1234567', 2, 0, 'es', TRUE, FALSE, FALSE, TRUE, FALSE, FALSE, 'Calle 1', 'San José', 'San José', '10101', 150.00, 140.00, 1),
('María Rodríguez', 'maria.rodriguez@email.com', '8888-2222', 'Costa Rica', 'B2345678', 1, 1, 'es', FALSE, TRUE, FALSE, FALSE, TRUE, FALSE, 'Calle 2', 'Alajuela', 'Alajuela', '20101', 130.00, 120.00, 2),
('Carlos Jiménez', 'carlos.jimenez@email.com', '8888-3333', 'Costa Rica', 'C3456789', 2, 2, 'es', FALSE, FALSE, TRUE, FALSE, FALSE, TRUE, 'Calle 3', 'Cartago', 'Cartago', '30101', 200.00, 180.00, 3),
('Ana Morales', 'ana.morales@email.com', '8888-4444', 'Costa Rica', 'D4567890', 1, 0, 'es', TRUE, TRUE, FALSE, FALSE, FALSE, FALSE, 'Calle 4', 'Heredia', 'Heredia', '40101', 90.00, 85.00, 4),
('Luis Fernández', 'luis.fernandez@email.com', '8888-5555', 'Costa Rica', 'E5678901', 3, 1, 'es', FALSE, FALSE, TRUE, TRUE, TRUE, FALSE, 'Calle 5', 'Puntarenas', 'Puntarenas', '50101', 250.00, 230.00, 5);

INSERT INTO reservation_tour (
  reservation_id,
  tour_id,
  check_in_date,
  check_out_date,
  adults,
  children
) VALUES
(1, 1, '2024-07-01', '2024-07-02', 2, 0),
(2, 2, '2024-07-03', '2024-07-03', 1, 1),
(3, 3, '2024-07-04', '2024-07-05', 2, 2),
(4, 4, '2024-07-06', '2024-07-06', 1, 0),
(5, 5, '2024-07-07', '2024-07-08', 3, 1),
(1, 6, '2024-07-09', '2024-07-09', 2, 0),
(2, 7, '2024-07-10', '2024-07-10', 1, 1),
(3, 8, '2024-07-11', '2024-07-12', 2, 2),
(4, 9, '2024-07-13', '2024-07-13', 1, 0),
(5, 10, '2024-07-14', '2024-07-15', 3, 1),
(1, 11, '2024-07-16', '2024-07-16', 2, 0),
(2, 12, '2024-07-17', '2024-07-17', 1, 1),
(3, 13, '2024-07-18', '2024-07-19', 2, 2);

INSERT INTO feedback (score, tour_id, customer_id, full_name, comment , status) VALUES
(5, 1, 1, 'Juan Pérez', '¡Excelente tour, muy recomendado!', 'Aprobada'),
(4, 2, 2, 'María Rodríguez', 'Muy bonito el parque, pero había mucha gente.', 'Aprobada'),
(5, 3, 3, 'Carlos Jiménez', 'La experiencia en bote fue increíble.', 'Aprobada'),
(3, 4, 4, 'Ana Morales', 'Esperaba más adrenalina en el canopy.', 'Aprobada'),
(5, 5, 5, 'Luis Fernández', 'Hermoso lugar y guías muy atentos.', 'Aprobada'),
(4, 6, 1, 'Sofía Vargas', 'El bosque nuboso es mágico, pero llovió mucho.', 'Aprobada'),
(5, 7, 2, 'Pedro Castillo', 'El volcán es impresionante, volvería.', 'Aprobada'),
(4, 8, 3, 'Gabriela Soto', 'Rafting divertido, pero el agua estaba fría.', 'Aprobada'),
(5, 9, 4, 'Ricardo Salazar', 'La catarata es un espectáculo natural.', 'Aprobada'),
(5, 10, 5, 'Daniela Méndez', 'Playa Sámara es perfecta para familias.', 'Aprobada'),
(4, 11, 1, 'Esteban Rojas', 'Corcovado es único, pero el acceso es difícil.', 'Aprobada'),
(5, 12, 2, 'Valeria Quesada', 'Las cataratas Nauyaca son impresionantes.', 'Aprobada'),
(5, 13, 3, 'Jorge Navarro', 'Aprendí a surfear en Tamarindo, excelente clase.', 'Aprobada');
