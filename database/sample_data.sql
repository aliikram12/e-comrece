-- Sample Data for TechStore

-- Insert Admin User
INSERT INTO users (username, email, password, first_name, last_name, role, status) VALUES 
('admin', 'admin@techstore.com', '$2y$10$8K1p5/wK9vvxL5FgXx8M8.u0dg2Lc5.6g.9z1K.3q.0L.5.8.z', 'Admin', 'User', 'admin', 'active');
 
-- Insert Demo Customer
INSERT INTO users (username, email, password, first_name, last_name, role, status) VALUES 
('demo', 'demo@techstore.com', '$2y$10$8K1p5/wK9vvxL5FgXx8M8.u0dg2Lc5.6g.9z1K.3q.0L.5.8.z', 'Demo', 'Customer', 'customer', 'active');

-- Insert Sample Categories
INSERT INTO categories (category_name, slug, description, is_active) VALUES
('Laptops', 'laptops', 'High-performance laptops and notebooks', TRUE),
('Smartphones', 'smartphones', 'Latest smartphones and mobile devices', TRUE),
('Tablets', 'tablets', 'Portable tablet computers', TRUE),
('Accessories', 'accessories', 'Tech accessories and peripherals', TRUE),
('Audio', 'audio', 'Headphones, speakers, and audio equipment', TRUE),
('Cameras', 'cameras', 'Digital cameras and photography equipment', TRUE);

-- Insert Sample Products
INSERT INTO products (product_name, slug, description, price, original_price, category_id, stock_quantity, sku, status, image) VALUES
('MacBook Pro 14"', 'macbook-pro-14', 'Powerful laptop with M1 Pro chip, 14-inch display, perfect for professionals', 1999.99, 2199.99, 1, 50, 'MBP-14-001', 'active', 'https://source.unsplash.com/featured/800x600?macbook+technology'),
('Dell XPS 13', 'dell-xps-13', 'Ultra-portable laptop with stunning display and all-day battery', 1299.99, 1499.99, 1, 30, 'DEL-XPS-13', 'active', 'https://source.unsplash.com/featured/800x600?laptop+technology'),
('iPhone 14 Pro', 'iphone-14-pro', 'Latest Apple smartphone with advanced camera and 5G', 999.99, 1099.99, 2, 100, 'APL-IP14P', 'active', 'https://source.unsplash.com/featured/800x600?iphone+technology'),
('Samsung Galaxy S23', 'samsung-galaxy-s23', 'Flagship Android smartphone with powerful processor', 899.99, 999.99, 2, 80, 'SAM-S23', 'active', 'https://source.unsplash.com/featured/800x600?smartphone+technology'),
('iPad Air', 'ipad-air', 'Versatile tablet with M1 chip', 599.99, 699.99, 3, 40, 'APL-IPAD-AIR', 'active', 'https://source.unsplash.com/featured/800x600?tablet+technology'),
('Sony WH-1000XM5', 'sony-wh-1000xm5', 'Premium noise-canceling headphones', 399.99, 449.99, 5, 60, 'SON-WH-XM5', 'active', 'https://source.unsplash.com/featured/800x600?headphones+technology'),
('AirPods Pro', 'airpods-pro', 'Wireless earbuds with active noise cancellation', 249.99, 299.99, 5, 150, 'APL-ACP-PRO', 'active', 'https://source.unsplash.com/featured/800x600?earbuds+technology'),
('GoPro Hero 11', 'gopro-hero-11', 'Action camera for extreme sports and adventures', 499.99, 549.99, 6, 25, 'GOP-HERO-11', 'active', 'https://source.unsplash.com/featured/800x600?camera+technology');

-- Note: Password hashes above correspond to 'password123' using PHP's password_hash()
-- To generate new hashes, use: password_hash('password123', PASSWORD_BCRYPT)

-- Additional Random Products
INSERT INTO products (product_name, slug, description, price, original_price, category_id, stock_quantity, sku, status, image) VALUES
('Logitech MX Master 3', 'logitech-mx-master-3', 'Advanced wireless mouse with ergonomic design and long battery life', 99.99, 129.99, 4, 120, 'LOG-MX3', 'active', 'https://source.unsplash.com/featured/800x600?mouse+technology'),
('Anker PowerCore 20000', 'anker-powercore-20000', 'High-capacity portable charger with fast charging support', 49.99, 69.99, 4, 200, 'ANK-PC-20000', 'active', 'https://source.unsplash.com/featured/800x600?battery+technology'),
('Bose SoundLink Revolve', 'bose-soundlink-revolve', 'Portable Bluetooth speaker with 360° sound', 179.99, 199.99, 5, 75, 'BOS-SLR-REV', 'active', 'https://source.unsplash.com/featured/800x600?speaker+technology'),
('Canon EOS R10', 'canon-eos-r10', 'Mirrorless camera with excellent autofocus and compact body', 979.99, 1099.99, 6, 30, 'CAN-EOS-R10', 'active', 'https://source.unsplash.com/featured/800x600?camera+lens');
