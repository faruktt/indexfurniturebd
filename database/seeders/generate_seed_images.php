<?php

$dir = __DIR__ . '/../../public/uploads';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

function createIndexFurnitureImage($filename, $title, $category, $variant = 'Primary View', $bgColor = '#f8fafc', $accentColor = '#ea1d24') {
    global $dir;
    $path = $dir . '/' . $filename;

    $w = 600;
    $h = 600;
    $im = imagecreatetruecolor($w, $h);

    list($r, $g, $b) = sscanf($bgColor, "#%02x%02x%02x");
    imagefill($im, 0, 0, imagecolorallocate($im, $r, $g, $b));

    list($ar, $ag, $ab) = sscanf($accentColor, "#%02x%02x%02x");
    $accent = imagecolorallocate($im, $ar, $ag, $ab);
    $dark = imagecolorallocate($im, 15, 23, 42);
    $gray = imagecolorallocate($im, 100, 116, 139);
    $lightBorder = imagecolorallocate($im, 226, 232, 240);
    $white = imagecolorallocate($im, 255, 255, 255);
    $cardBg = imagecolorallocate($im, 241, 245, 249);

    // Outer subtle border
    imagerectangle($im, 0, 0, $w - 1, $h - 1, $lightBorder);

    // Inner showroom podium
    imagefilledrectangle($im, 30, 30, $w - 30, $h - 130, $white);
    imagerectangle($im, 30, 30, $w - 30, $h - 130, $lightBorder);

    // Floor shadow effect
    imagefilledellipse($im, 300, 380, 420, 60, imagecolorallocate($im, 235, 238, 242));

    // Furniture graphic silhouette / box
    imagefilledrectangle($im, 120, 100, 480, 360, $cardBg);
    imagerectangle($im, 120, 100, 480, 360, $accent);
    imagefilledrectangle($im, 150, 130, 450, 320, $white);
    imagerectangle($im, 150, 130, 450, 320, $lightBorder);

    // Brand tag
    imagefilledrectangle($im, 40, 40, 180, 70, $accent);
    imagestring($im, 4, 52, 47, "INDEX FURNITURE", $white);

    // Category / Collection tag
    imagefilledrectangle($im, 400, 40, 560, 68, imagecolorallocate($im, 241, 245, 249));
    imagestring($im, 3, 415, 47, strtoupper(substr($category, 0, 16)), $dark);

    // Product Title inside box
    imagestring($im, 5, 170, 200, "INDEX DESIGN", $dark);
    imagestring($im, 3, 170, 225, substr($title, 0, 30), $gray);
    imagestring($im, 3, 170, 245, "Angle: " . $variant, $accent);

    // Info Footer
    imagefilledrectangle($im, 30, $h - 115, $w - 30, $h - 30, $white);
    imagerectangle($im, 30, $h - 115, $w - 30, $h - 30, $lightBorder);

    imagestring($im, 4, 45, $h - 100, substr($title, 0, 42), $dark);
    imagestring($im, 2, 45, $h - 75, "Authentic Craftsmanship * 100% Termite Proof * Official Warranty", $gray);
    imagestring($im, 2, 45, $h - 55, "Index Furniture Industries Limited - Quality Certified", $accent);

    imagejpeg($im, $path, 90);
    imagedestroy($im);
}

// 1. 10 Category Images
$categories = [
    ['cat_bedroom.jpg', 'Bedroom Furniture Collection', 'BEDROOM', '#b45309'],
    ['cat_living.jpg', 'Living Room Luxury Suites', 'LIVING', '#7c3aed'],
    ['cat_dining.jpg', 'Dining Sets & Wagons', 'DINING', '#ea1d24'],
    ['cat_study.jpg', 'Study Tables & Storage', 'STUDY ROOM', '#0284c7'],
    ['cat_storage.jpg', 'Modern Cabinets & Chests', 'STORAGE', '#15803d'],
    ['cat_office_chair.jpg', 'Ergonomic Office Seating', 'OFFICE CHAIR', '#0f172a'],
    ['cat_office_table.jpg', 'Executive Desks & Tables', 'OFFICE TABLE', '#2563eb'],
    ['cat_allied.jpg', 'Allied Home Decor & Panels', 'ALLIED', '#c2410c'],
    ['cat_hospital.jpg', 'Medical & Care Solutions', 'HOSPITAL', '#0891b2'],
    ['cat_interior.jpg', 'Commercial Interior Fit-outs', 'INTERIOR', '#4f46e5'],
];

foreach ($categories as $cat) {
    createIndexFurnitureImage($cat[0], $cat[1], $cat[2], 'Category Banner', '#f8fafc', $cat[3]);
}

// 2. 10 Subcategory Images
$subcategories = [
    ['subcat_bedside.jpg', 'Bed Side Cabinets', 'BEDROOM', '#b45309'],
    ['subcat_bed.jpg', 'Master Bedsteads', 'BEDROOM', '#b45309'],
    ['subcat_wardrobe.jpg', 'Multi-Door Wardrobes', 'BEDROOM', '#b45309'],
    ['subcat_dressing.jpg', 'Dressing Tables & Mirrors', 'BEDROOM', '#b45309'],
    ['subcat_sofa.jpg', 'Living Room Sofas & Couches', 'LIVING', '#7c3aed'],
    ['subcat_dining.jpg', 'Dining Table Collections', 'DINING', '#ea1d24'],
    ['subcat_study.jpg', 'Ergonomic Study Desks', 'STUDY ROOM', '#0284c7'],
    ['subcat_storage.jpg', 'Utility Cupboards', 'STORAGE', '#15803d'],
    ['subcat_executive_chair.jpg', 'High-Back Executive Chairs', 'OFFICE CHAIR', '#0f172a'],
    ['subcat_workstation.jpg', 'Modular Workstation Tables', 'OFFICE TABLE', '#2563eb'],
];

foreach ($subcategories as $sub) {
    createIndexFurnitureImage($sub[0], $sub[1], $sub[2], 'Subcategory View', '#f8fafc', $sub[3]);
}

// 3. 10 Child Category Images
$childcategories = [
    ['childcat_laminate_bedside.jpg', 'Laminate Bed Side', 'BEDROOM', '#b45309'],
    ['childcat_king_bed.jpg', 'King Size Beds', 'BEDROOM', '#b45309'],
    ['childcat_3door_wardrobe.jpg', '3 Door Wardrobes', 'BEDROOM', '#b45309'],
    ['childcat_dressing_table.jpg', 'Laminate Dressing Table', 'BEDROOM', '#b45309'],
    ['childcat_3seater_sofa.jpg', 'Three Seater Sofa', 'LIVING', '#7c3aed'],
    ['childcat_dining_set.jpg', '6 Seater Dining Set', 'DINING', '#ea1d24'],
    ['childcat_study_table.jpg', 'Executive Study Table', 'STUDY ROOM', '#0284c7'],
    ['childcat_cabinet.jpg', 'Filing & Storage Cabinet', 'STORAGE', '#15803d'],
    ['childcat_office_chair.jpg', 'Ergonomic Revolving Chair', 'OFFICE CHAIR', '#0f172a'],
    ['childcat_workstation.jpg', 'Modular Computer Workstation', 'OFFICE TABLE', '#2563eb'],
];

foreach ($childcategories as $child) {
    createIndexFurnitureImage($child[0], $child[1], $child[2], 'Child Category View', '#f8fafc', $child[3]);
}

// 4. 10 Products with Primary + Multiple Gallery Images (3 images per product = 30 images)
$products = [
    [
        'prefix' => 'prod_bedside_a120lb',
        'title' => 'CASTLE - Index Bed Side Cabinet A120LB',
        'category' => 'BEDROOM',
        'color' => '#b45309'
    ],
    [
        'prefix' => 'prod_king_bed',
        'title' => 'ROYAL - Index King Size Master Bed KB101',
        'category' => 'BEDROOM',
        'color' => '#b45309'
    ],
    [
        'prefix' => 'prod_wardrobe_3door',
        'title' => 'ELEGANCE - Index 3-Door Cupboard & Wardrobe WDC07',
        'category' => 'BEDROOM',
        'color' => '#b45309'
    ],
    [
        'prefix' => 'prod_dressing_table',
        'title' => 'AURORA - Index Dressing Table with Mirror DT156',
        'category' => 'BEDROOM',
        'color' => '#b45309'
    ],
    [
        'prefix' => 'prod_sofa_a130ff',
        'title' => 'COMFORT - Index Luxury Sofa Set-A130FF',
        'category' => 'LIVING',
        'color' => '#7c3aed'
    ],
    [
        'prefix' => 'prod_dining_set',
        'title' => 'IMPERIAL - Index 6-Seater Wooden Dining Set DS215',
        'category' => 'DINING',
        'color' => '#ea1d24'
    ],
    [
        'prefix' => 'prod_study_table',
        'title' => 'SCHOLAR - Index Executive Study Table TCCP001',
        'category' => 'STUDY ROOM',
        'color' => '#0284c7'
    ],
    [
        'prefix' => 'prod_storage_cabinet',
        'title' => 'STORAGE PRO - Index Multi-Utility Cabinet SC002',
        'category' => 'STORAGE',
        'color' => '#15803d'
    ],
    [
        'prefix' => 'prod_office_chair',
        'title' => 'PREMIUM - Index Executive High-Back Chair OC501',
        'category' => 'OFFICE CHAIR',
        'color' => '#0f172a'
    ],
    [
        'prefix' => 'prod_workstation',
        'title' => 'MATRIX - Index Office Workstation Table WOSP001',
        'category' => 'OFFICE TABLE',
        'color' => '#2563eb'
    ],
];

foreach ($products as $p) {
    // Primary Image (Index 1)
    createIndexFurnitureImage($p['prefix'] . '_1.jpg', $p['title'], $p['category'], 'Front View / Main Angle', '#ffffff', $p['color']);
    // Gallery Image 1 (Index 2)
    createIndexFurnitureImage($p['prefix'] . '_2.jpg', $p['title'], $p['category'], 'Side View / Details', '#f8fafc', $p['color']);
    // Gallery Image 2 (Index 3)
    createIndexFurnitureImage($p['prefix'] . '_3.jpg', $p['title'], $p['category'], 'Interior / Dimensions Angle', '#f1f5f9', $p['color']);
}

echo "Successfully generated all seed images in $dir\n";
