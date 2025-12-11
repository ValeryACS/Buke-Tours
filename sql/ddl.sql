CREATE DATABASE IF NOT EXISTS buke_tours_db;

USE buke_tours_db;

DROP TABLE IF EXISTS tour;

CREATE TABLE tour (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  sku VARCHAR(130) NOT NULL,
  title VARCHAR(150) NOT NULL,
  location VARCHAR(150) NOT NULL,
  category VARCHAR(50) NOT NULL,
  price_usd DECIMAL(10,2) NOT NULL,
  cupon_code VARCHAR(50) NULL,
  cupon_discount INT DEFAULT 0,
  rating DECIMAL(3,1) NOT NULL,
  duration_hours DECIMAL(4,1) NOT NULL,
  discount INT NOT NULL,
  img VARCHAR(500) NOT NULL,
  description VARCHAR(500) NOT NULL,
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
    avatar_url VARCHAR(500),
    UNIQUE KEY uq_customer_email (email),
    UNIQUE KEY uq_customer_passport (passport),
    PRIMARY KEY (id)
  ) ENGINE=InnoDB
    DEFAULT CHARSET=utf8mb4
    COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS admins;

 CREATE TABLE admins (
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
    avatar_url VARCHAR(500),
    UNIQUE KEY uq_admin_email (email),
    UNIQUE KEY uq_admin_passport (passport),
    PRIMARY KEY (id)
  ) ENGINE=InnoDB
    DEFAULT CHARSET=utf8mb4
    COLLATE=utf8mb4_unicode_ci;
    
   

INSERT INTO admins (
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
) VALUES (
    'Juan Pérez García',
    'juan.perez@example.com',
    'aJrgba1235a@%.', -- Replace with a strong, hashed password
    '+34600123456',
    'CR',
    'ABC123456',
    'es',
    'Masculino',
    'Calle Falsa 123',
    'Alajuela',
    'Alajuela',
    '28001',
    '1985-05-15'
);

DROP TABLE IF EXISTS reservation;

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

  adults   INT UNSIGNED NOT NULL DEFAULT 0,
  children INT UNSIGNED NOT NULL DEFAULT 0,

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
  category,
  price_usd,
  cupon_code,
  cupon_discount,
  rating,
  duration_hours,
  discount,
  img,
  description,
  adults_limit,
  children_limit
) VALUES
(
  'cr-monteverde-hanging-bridges',
  'Puentes Colgantes Monteverde',
  'Monteverde, Puntarenas',
  'bosque nuboso',
  75.00,
  'MONTE_VRD_CR',
  4,
  4.8,
  2.5,
  3,
  '/Buke-Tours/assets/img/tour2.jpg',
  'Caminata guiada por bosque nuboso, observación de aves y biodiversidad única. Incluye interpretación naturalista sobre flora endémica y especies que habitan entre los puentes, además de paradas en miradores suspendidos para tomar fotografías panorámicas. El recorrido finaliza en un centro de visitantes con degustación de café artesanal y chocolate local.',
  25,
  10
),
(
  'cr-manuel-antonio-park',
  'Parque Nacional Manuel Antonio',
  'Quepos, Puntarenas',
  'playa',
  65.00,
  'MANUEL_ANT_CR',
  2,
  4.6,
  6.0,
  2,
  'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/16/5a/2f/50/photo0jpg.jpg?w=1000&h=-1&s=1',
  'Tour por senderos y playas con posibilidad de ver monos, perezosos y diversidad marina. Un guía local comparte historias del parque mientras exploras miradores y piscinas naturales, y se reserva tiempo para snorkelear en Bahía Manuel Antonio. Finaliza con un picnic ligero y recomendaciones personalizadas para extender tu visita.',
  45,
  15
),
(
  'cr-tortuguero-boat',
  'Tour en Bote por Canales de Tortuguero',
  'Limón',
  'canales',
  99.00,
  'LIMON_TORT_CR',
  3,
  4.9,
  4.0,
  5,
  '/Buke-Tours/assets/img/tour3.jpg',
  'Recorrido en bote por canales, avistamiento de fauna y aprendizaje sobre conservación. Descubre manglares centenarios y escucha curiosidades sobre la anidación de tortugas, mientras disfrutas refrigerios tropicales a bordo. Incluye visita a un pequeño museo comunitario que explica la historia de Tortuguero.',
  25,
  10
),
(
  'cr-arenal-zipline',
  'Canopy & Zipline en Arenal',
  'La Fortuna, Alajuela',
  'canopy,senderismo,montaña',
  89.00,
  'FORTU_CR',
  3,
  4.7,
  3.5,
  3,
  'https://cdn.pixabay.com/photo/2017/06/13/19/22/tyrolean-2399759_1280.jpg',
  'Aventura de canopy entre selva tropical con vistas al Volcán Arenal y plataformas seguras. Incluye charla de seguridad, equipo profesional y fotografías digitales opcionales, además de un corto sendero interpretativo sobre flora volcánica. Culmina con bebidas refrescantes en una terraza con vista al lago Arenal.',
  45,
  20
),
(
  'cr-manuel-antonio',
  'Parque Nacional Manuel Antonio',
  'Quepos, Puntarenas',
  'playa',
  25.00,
  'QUEPOS_CR',
  3,
  4.9,
  5.0,
  2,
  'https://manuelantoniopark.net/wp-content/uploads/2020/03/landing-page-4-scaled.jpg',
  'Senderos, playas de arena blanca y abundante vida silvestre en uno de los parques más visitados del país. Disfruta tiempo libre para nadar y tomar fotografías en Playa Espadilla Sur, seguido de un paseo interpretativo por el manglar. La excursión incluye equipo básico para snorkel y transporte de regreso al hotel.',
  50,
  10
),
(
  'cr-monteverde-cloudforest',
  'Bosque Nuboso de Monteverde',
  'Monteverde, Puntarenas',
  'bosque',
  40.00,
  NULL,
  0.00,
  4.9,
  6.0,
  8,
  'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/06/93/b4/50/monteverde-cloud-forest.jpg?w=600&h=-1&s=1',
  'Senderos colgantes, aves quetzales y un ecosistema único entre las nubes. La caminata se acompaña de estaciones educativas sobre la historia del bosque nuboso y visitas a jardines de colibríes. Termina con una demostración de orquídeas y consejos para apoyar la conservación local.',
  30,
  10
),
(
  'cr-poas-volcano',
  'Volcán Poás',
  'Alajuela, Central Valley',
  'volcan',
  15.00,
  NULL,
  0.00,
  4.6,
  2.5,
  7,
  'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3f/Poas_crater.jpg/800px-Poas_crater.jpg',
  'Impresionante cráter activo con lago ácido y miradores panorámicos a 2700 m.s.n.m. Aprende sobre los ciclos geotérmicos del volcán durante una parada en el centro de visitantes y explora los senderos del bosque nuboso circundante. Incluye degustación de fresas cultivadas en la zona y tiempo libre en el pueblo de Poásito.',
  40,
  10
),
(
  'cr-riosarapiqui',
  'Rafting Río Sarapiquí',
  'Heredia, Sarapiquí',
  'rafting,rio',
  95.00,
  NULL,
  0.00,
  4.8,
  4.5,
  1,
  'https://media-cdn.tripadvisor.com/media/attractions-splice-spp-720x480/10/6f/65/47.jpg',
  'Descenso en balsas inflables por rápidos clase III y IV entre paisajes tropicales. Se incluye instrucción previa, chaleco salvavidas y snacks energéticos a mitad del río, además de fotografías digitales de la travesía. Finaliza con un almuerzo campesino y degustación de bebidas naturales en una finca cercana.',
  20,
  5
),
(
  'cr-rio-celeste',
  'Catarata Río Celeste',
  'Parque Tenorio, Guanacaste',
  'cascada,rio,rafting',
  20.00,
  NULL,
  0.00,
  4.9,
  3.0,
  5,
  'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/02/67/45/0f/waterfall.jpg?w=900&h=-1&s=1',
  'El icónico río de color celeste, con una cascada espectacular y sendero natural. Visita los teñideros donde se mezclan los minerales que brindan el tono turquesa al agua y maravíllate con el bosque lluvioso que lo rodea. Incluye parada en miradores de volcanes y almuerzo típico en un rancho rural.',
  25,
  10
),
(
  'cr-samara-beach',
  'Playa Sámara',
  'Nicoya, Guanacaste',
  'playa',
  80.00,
  NULL,
  0.00,
  4.7,
  6.0,
  3,
  'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80',
  'Playa familiar ideal para nadar, practicar surf suave y disfrutar del atardecer. Incluye tiempo para recorrer el pueblo de Sámara y degustar un ceviche local, además de clases básicas de paddle board para principiantes. Se reserva un espacio al atardecer para fotografiar el horizonte y participar en una breve sesión de relajación guiada en la arena.',
  40,
  20
),
(
  'cr-corcovado-hike',
  'Senderismo en Parque Nacional Corcovado',
  'Osa, Puntarenas',
  'senderismo',
  120.00,
  'CORCOVD_CR',
  3,
  5.0,
  8.0,
  4,
  'https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/Parque_Nacional_Corcovado.JPG/1024px-Parque_Nacional_Corcovado.JPG',
  'Uno de los lugares con mayor biodiversidad del planeta, hábitat del jaguar y tapir. El recorrido incluye caminatas guiadas por sectores Sirena y San Pedrillo para maximizar el avistamiento y charlas con guardaparques sobre esfuerzos de conservación. Se suma un trayecto en bote por la costa de Osa y un almuerzo con ingredientes orgánicos.',
  25,
  10
),
(
  'cr-nauyaca-falls',
  'Cataratas Nauyaca',
  'Dominical, Puntarenas',
  'cascada',
  120.00,
  'DOMINI_CR',
  2,
  4.8,
  4.0,
  2,
  'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/12/15/d7/9d/img-20180217-133510-largejpg.jpg?w=1000&h=-1&s=1',
  'Dos impresionantes cascadas en medio de la selva, ideales para nadar y tomar fotos. Incluimos cabalgata opcional y box lunch tradicional para disfrutar en la base de la caída, además de guía fotográfico para capturar cada salto. El tour también visita un trapiche artesanal donde podrás probar miel de caña recién hecha.',
  45,
  10
),
(
  'cr-tamarindo-surf',
  'Clase de Surf en Tamarindo',
  'Tamarindo, Guanacaste',
  'playa',
  65.00,
  'TAMARIND_CR',
  5,
  4.9,
  2.0,
  8,
  'https://res.cloudinary.com/vacationscostarica-com/image/upload/q_auto:eco,c_fill,f_auto,g_center,w_1440,h_730/v1743457516/tamarindo_beach_aerial_view_costa_rica_f7d26d7356',
  'Aprende surf con instructores certificados en una de las playas más famosas del país. Clases personalizadas según tu nivel e incluye análisis en video para mejorar tu técnica, junto con ejercicios de respiración para controlar el ritmo de las olas. Termina con una sesión de stretching frente al mar y bebidas hidratantes con frutas tropicales.',
  20,
  5
),
(
  'cr-rincon-de-la-vieja',
  'Volcán Rincón de la Vieja',
  'Liberia, Guanacaste',
  'volcan',
  120.00,
  'RINCON_DELVIEJ_CR',
  4,
  4.7,
  5.0,
  4,
  'https://upload.wikimedia.org/wikipedia/commons/thumb/e/ea/Guanacaste_National_Park.jpg/800px-Guanacaste_National_Park.jpg',
  'Parque nacional con pozas termales, fumarolas y senderos entre la selva seca tropical. La excursión visita cataratas ocultas y permite baños de barro volcánico relajante, además de cabalgatas cortas por bosques secundarios. Incluye degustación de comida criolla y tiempo para compras en Liberia.',
  60,
  25
),
(
  'cr-puerto-viejo',
  'Puerto Viejo de Talamanca',
  'Caribe Sur, Limón',
  'playa',
  70.00,
  'PUERTO_VIEJ_CR',
  7,
  4.6,
  6.0,
  1,
  'https://mytanfeet.com/wp-content/uploads/2018/01/Things-to-do-in-Puerto-Viejo-beach-hop.jpg',
  'Destino caribeño con playas de arena negra, cultura afrocaribeña y ambiente relajado. Incluye visita a mercados artesanales y degustación de platillos caribeños caseros, además de clases cortas de calipso con músicos locales. Termina con un atardecer en Punta Uva y coctel de frutas tropicales.',
  60,
  20
),
(
  'cr-cahuita-reef',
  'Snorkel en el Arrecife de Cahuita',
  'Limón, Costa Caribe',
  'snorkel,playa',
  155.00,
  'CAHUITA_CR',
  4,
  4.8,
  3.0,
  6,
  'https://media-cdn.tripadvisor.com/media/photo-s/09/bc/52/ad/willie-s-tours-cr-day.jpg',
  'Explora los corales del Parque Nacional Cahuita en un tour guiado en bote. Se proporcionan máscaras, aletas y guía experto que identifica peces multicolores y tortugas, junto con sesión educativa sobre restauración de arrecifes. Incluye merienda caribeña y paseo por senderos costeros para observar perezosos.',
  40,
  15
),
(
  'cr-cartago-basilica',
  'Basílica de Los Ángeles',
  'Cartago Centro',
  'cultural',
  60.00,
  NULL,
  0.00,
  4.9,
  1.0,
  7,
  'https://upload.wikimedia.org/wikipedia/commons/thumb/2/28/Basilica_of_Our_Lady_of_the_Angels%2C_Cartago_01.jpg/800px-Basilica_of_Our_Lady_of_the_Angels%2C_Cartago_01.jpg',
  'Templo icónico de Costa Rica y centro de peregrinación anual en agosto. Descubre la leyenda de la Virgen de los Ángeles y los tesoros artísticos resguardados en el museo, con acceso especial a los jardines interiores. También se visita el antiguo acueducto de Cartago y se degustan bocadillos tradicionales en una cafetería histórica.',
  50,
  15
),
(
  'cr-ojochal-gastronomy',
  'Tour Gastronómico en Ojochal',
  'Puntarenas Sur',
  'gastronomia',
  80.00,
  'OJOCHAL_CR',
  4,
  4.9,
  3.0,
  2,
  'https://www.costaricavibes.com/wp-content/uploads/Santa-Teresa-Costa-Rica-19-1024x597.jpg',
  'Descubre la fusión culinaria internacional y local en la capital gastronómica del Pacífico Sur. Incluye degustaciones en restaurantes boutique y explicaciones sobre ingredientes tropicales de temporada, además de un taller práctico de ceviche y maridaje con vino costarricense. Finaliza en una terraza con vista al Pacífico degustando postres de cacao local.',
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
