INSERT INTO categories (id, parent_id, name, slug, lft, rght, created, modified) VALUES
    (1, NULL, 'Living Room', 'living-room', 1, 2, NOW(), NOW()),
    (2, NULL, 'Workspace', 'workspace', 3, 4, NOW(), NOW()),
    (3, NULL, 'Kitchen', 'kitchen', 5, 6, NOW(), NOW()),
    (4, NULL, 'Wellness', 'wellness', 7, 8, NOW(), NOW());

INSERT INTO products (category_id, name, slug, description, price, stock, image, is_active, created, modified) VALUES
    (1, 'Luna Accent Chair', 'luna-accent-chair', 'A compact lounge chair with a curved silhouette, textured fabric, and an easy neutral tone for modern living rooms.', 189.00, 12, NULL, 1, NOW(), NOW()),
    (1, 'Oakline Coffee Table', 'oakline-coffee-table', 'Solid oak inspired coffee table with a soft matte finish and a lower shelf for books, trays, and everyday decor.', 149.00, 8, NULL, 1, NOW(), NOW()),
    (2, 'North Desk Lamp', 'north-desk-lamp', 'Focused LED desk lamp with warm brightness control and a sturdy metal arm built for long evening work sessions.', 59.00, 24, NULL, 1, NOW(), NOW()),
    (2, 'Canvas Task Desk', 'canvas-task-desk', 'Minimal writing desk with a slim profile, cable-friendly layout, and enough space for a laptop, notebook, and coffee mug.', 229.00, 7, NULL, 1, NOW(), NOW()),
    (3, 'Mori Ceramic Dinner Set', 'mori-ceramic-dinner-set', 'Twelve-piece ceramic dinnerware set with a handmade look, durable glaze, and a warm stone finish.', 84.00, 18, NULL, 1, NOW(), NOW()),
    (3, 'Brew Ritual Kettle', 'brew-ritual-kettle', 'Stovetop pour-over kettle with a gooseneck spout that gives better flow control for tea and coffee prep.', 42.00, 16, NULL, 1, NOW(), NOW()),
    (4, 'Cloud Weave Throw', 'cloud-weave-throw', 'Soft oversized throw blanket woven for year-round comfort with a breathable finish and subtle pattern.', 68.00, 20, NULL, 1, NOW(), NOW()),
    (4, 'Aroma Stone Diffuser', 'aroma-stone-diffuser', 'Quiet ultrasonic diffuser designed to bring a gentle scent and calm light into bedrooms, desks, and reading corners.', 47.00, 14, NULL, 1, NOW(), NOW());
