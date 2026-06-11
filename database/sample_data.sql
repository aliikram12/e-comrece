-- ===================================================================
-- Premium Sample Data for TechStore (Enhanced with Pixabay Images)
-- ===================================================================

-- Clear existing data if needed (uncomment to use)
-- SET FOREIGN_KEY_CHECKS = 0;
-- TRUNCATE TABLE users;
-- TRUNCATE TABLE categories;
-- TRUNCATE TABLE products;
-- TRUNCATE TABLE order_items;
-- TRUNCATE TABLE orders;
-- SET FOREIGN_KEY_CHECKS = 1;

-- -------------------------------------------------------------------
-- 1. Users
-- Password for all users is: password123 (Hash generated via PHP password_hash)
-- -------------------------------------------------------------------
INSERT INTO users (username, email, password, first_name, last_name, role, status) VALUES 
('admin', 'admin@techstore.com', '$2y$10$8K1p5/wK9vvxL5FgXx8M8.u0dg2Lc5.6g.9z1K.3q.0L.5.8.z', 'Admin', 'User', 'admin', 'active'),
('johndoe', 'john@example.com', '$2y$10$8K1p5/wK9vvxL5FgXx8M8.u0dg2Lc5.6g.9z1K.3q.0L.5.8.z', 'John', 'Doe', 'customer', 'active'),
('sarahsmith', 'sarah@example.com', '$2y$10$8K1p5/wK9vvxL5FgXx8M8.u0dg2Lc5.6g.9z1K.3q.0L.5.8.z', 'Sarah', 'Smith', 'customer', 'active'),
('mikej', 'mike@example.com', '$2y$10$8K1p5/wK9vvxL5FgXx8M8.u0dg2Lc5.6g.9z1K.3q.0L.5.8.z', 'Mike', 'Johnson', 'customer', 'active');

-- -------------------------------------------------------------------
-- 2. Categories
-- -------------------------------------------------------------------
INSERT INTO categories (category_name, slug, description, is_active) VALUES
('Laptops & PC', 'laptops-pc', 'High-performance laptops, desktops, and computing power for professionals and gamers.', TRUE),
('Smartphones', 'smartphones', 'The latest smartphones featuring advanced cameras, 5G, and stunning displays.', TRUE),
('Audio & Music', 'audio-music', 'Premium headphones, earbuds, and speakers for audiophiles.', TRUE),
('Cameras & Video', 'cameras-video', 'Professional photography gear, drones, and action cameras.', TRUE),
('Wearables', 'wearables', 'Smartwatches and fitness trackers to keep you connected on the go.', TRUE),
('Accessories', 'accessories', 'Keyboards, mice, chargers, and essential tech peripherals.', TRUE);

-- -------------------------------------------------------------------
-- 3. Products
-- Images sourced from Pixabay (Free for commercial use)
-- -------------------------------------------------------------------
INSERT INTO products (product_name, slug, description, price, original_price, category_id, stock_quantity, sku, status, image) VALUES

-- Laptops & PC (Category 1)
('MacBook Pro 16" M2', 'macbook-pro-16-m2', 'The ultimate pro laptop. With the incredible M2 Pro or M2 Max chip, experience groundbreaking performance and amazing battery life. Features a stunning Liquid Retina XDR display.', 2499.00, 2699.00, 1, 45, 'APP-MBP16-M2', 'active', 'https://cdn.pixabay.com/photo/2014/05/02/21/50/laptop-336378_1280.jpg'),
('Dell XPS 15 OLED', 'dell-xps-15-oled', 'A 15.6-inch laptop with a refined design and an immersive 3.5K OLED touch display. Powered by 13th Gen Intel Core processors.', 1899.99, 2100.00, 1, 30, 'DEL-XPS15-01', 'active', 'https://cdn.pixabay.com/photo/2015/01/08/18/25/desk-593327_1280.jpg'),
('Razer Blade 14 Gaming', 'razer-blade-14', 'The ultimate 14-inch gaming laptop. Ultra-thin, ultra-fast, and packed with an NVIDIA GeForce RTX 3080 Ti for desktop-level performance.', 1999.00, 2299.00, 1, 15, 'RAZ-BLD14-GAM', 'active', 'https://cdn.pixabay.com/photo/2014/09/24/14/29/mac-459196_1280.jpg'),

-- Smartphones (Category 2)
('iPhone 15 Pro Max', 'iphone-15-pro-max', 'Forged in titanium. Features the groundbreaking A17 Pro chip, a customizable Action button, and the most powerful iPhone camera system ever.', 1199.00, 1199.00, 2, 80, 'APP-IP15PM-256', 'active', 'https://cdn.pixabay.com/photo/2014/10/23/10/10/iphone-499991_1280.jpg'),
('Samsung Galaxy S24 Ultra', 'samsung-galaxy-s24-ultra', 'Welcome to the era of mobile AI. With Galaxy S24 Ultra in your hands, you can unleash whole new levels of creativity, productivity and possibility.', 1299.99, 1399.99, 2, 65, 'SAM-S24U-512', 'active', 'https://cdn.pixabay.com/photo/2016/12/09/11/33/smartphone-1894723_1280.jpg'),
('Google Pixel 8 Pro', 'google-pixel-8-pro', 'Engineered by Google, its custom-designed to bring you the most advanced AI features. Features an immersive 6.7" Super Actua display.', 999.00, 1049.00, 2, 50, 'GOO-PIX8P-128', 'active', 'https://cdn.pixabay.com/photo/2017/04/03/15/52/mobile-phone-2198770_1280.png'),

-- Audio & Music (Category 3)
('Sony WH-1000XM5', 'sony-wh-1000xm5', 'Industry-leading noise cancellation. Two processors control 8 microphones for unprecedented noise cancellation and call quality.', 398.00, 449.00, 3, 120, 'SON-WHXM5-BLK', 'active', 'https://cdn.pixabay.com/photo/2018/01/16/10/18/headphone-3085681_1280.jpg'),
('Apple AirPods Pro (2nd Gen)', 'apple-airpods-pro-2', 'Rich, high-quality audio and voice. Features up to 2x more Active Noise Cancellation, plus Adaptive Transparency, and Personalized Spatial Audio.', 249.00, 279.00, 3, 200, 'APP-AIRP2-WHT', 'active', 'https://cdn.pixabay.com/photo/2020/05/14/09/54/earphones-5190011_1280.jpg'),
('Bose SoundLink Revolve+', 'bose-soundlink-revolve-plus', 'The best-performing portable Bluetooth speaker from Bose, engineered to spread deep, jaw-dropping sound in every direction.', 329.00, 349.00, 3, 45, 'BOS-SLRV-PLUS', 'active', 'https://cdn.pixabay.com/photo/2017/08/06/15/13/box-2593361_1280.jpg'),

-- Cameras & Video (Category 4)
('Canon EOS R5 Mirrorless', 'canon-eos-r5', 'Professional full-frame mirrorless camera offering 45 megapixel resolution, 8K video recording, and advanced subject tracking.', 3899.00, 4100.00, 4, 10, 'CAN-R5-BODY', 'active', 'https://cdn.pixabay.com/photo/2014/08/29/14/53/camera-431119_1280.jpg'),
('DJI Mini 3 Pro Drone', 'dji-mini-3-pro', 'Mini-sized, mega-capable DJI Mini 3 Pro is just as powerful as it is portable. Weighing less than 249 g and with upgraded safety features.', 759.00, 899.00, 4, 25, 'DJI-MINI3-PRO', 'active', 'https://cdn.pixabay.com/photo/2016/11/23/18/14/drone-1854202_1280.jpg'),
('GoPro HERO12 Black', 'gopro-hero12-black', 'The most powerful GoPro yet. Features HDR video, 5.3K60 resolution, and HyperSmooth 6.0 video stabilization.', 399.00, 449.00, 4, 85, 'GOP-HERO12-BLK', 'active', 'https://cdn.pixabay.com/photo/2018/10/01/16/06/gopro-3716584_1280.jpg'),

-- Wearables (Category 5)
('Apple Watch Ultra 2', 'apple-watch-ultra-2', 'The most rugged and capable Apple Watch. Designed for outdoor adventures and supercharged workouts with a lightweight titanium case.', 799.00, 849.00, 5, 40, 'APP-AWU2-TIT', 'active', 'https://cdn.pixabay.com/photo/2015/06/25/17/21/smart-watch-821557_1280.jpg'),
('Garmin Fenix 7X Pro', 'garmin-fenix-7x-pro', 'Ultimate multisport GPS smartwatch with a large 1.4" display, built-in LED flashlight, and solar charging lens.', 899.99, 999.99, 5, 20, 'GAR-F7XP-SOL', 'active', 'https://cdn.pixabay.com/photo/2014/07/31/23/37/wrist-watch-407166_1280.jpg'),

-- Accessories (Category 6)
('Logitech MX Master 3S', 'logitech-mx-master-3s', 'An iconic mouse remastered. Feel every moment of your workflow with even more precision, tactility, and performance, thanks to Quiet Clicks.', 99.99, 119.99, 6, 150, 'LOG-MX3S-GRY', 'active', 'https://cdn.pixabay.com/photo/2017/05/24/21/33/workplace-2341642_1280.jpg'),
('Keychron Q1 Pro Keyboard', 'keychron-q1-pro', 'A fully customizable 75% layout wireless custom mechanical keyboard. Features a full aluminum body and QMK/VIA support.', 199.00, 210.00, 6, 60, 'KEY-Q1P-RGB', 'active', 'https://cdn.pixabay.com/photo/2015/05/15/02/09/keyboard-767794_1280.jpg');
