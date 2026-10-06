<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\Color;
use App\Models\Permission;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Role;
use App\Models\ShippingCharge;
use App\Models\SiteSetting;
use App\Models\Size;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles & Permissions
        $permissions = [
            // Products
            ['name' => 'View Products', 'slug' => 'view-products', 'group_name' => 'Product Management'],
            ['name' => 'Create Products', 'slug' => 'create-products', 'group_name' => 'Product Management'],
            ['name' => 'Edit Products', 'slug' => 'edit-products', 'group_name' => 'Product Management'],
            ['name' => 'Delete Products', 'slug' => 'delete-products', 'group_name' => 'Product Management'],
            // Categories
            ['name' => 'Manage Categories', 'slug' => 'manage-categories', 'group_name' => 'Catalog Management'],
            ['name' => 'Manage Sub Categories', 'slug' => 'manage-subcategories', 'group_name' => 'Catalog Management'],
            ['name' => 'Manage Child Categories', 'slug' => 'manage-childcategories', 'group_name' => 'Catalog Management'],
            // Attributes
            ['name' => 'Manage Colors & Sizes', 'slug' => 'manage-attributes', 'group_name' => 'Catalog Management'],
            // Orders
            ['name' => 'View Orders', 'slug' => 'view-orders', 'group_name' => 'Order Management'],
            ['name' => 'Update Orders', 'slug' => 'update-orders', 'group_name' => 'Order Management'],
            ['name' => 'Delete Orders', 'slug' => 'delete-orders', 'group_name' => 'Order Management'],
            // Shipping
            ['name' => 'Manage Shipping Charges', 'slug' => 'manage-shipping', 'group_name' => 'Settings'],
            // Customers
            ['name' => 'Manage Customers', 'slug' => 'manage-customers', 'group_name' => 'User Management'],
            // Roles
            ['name' => 'Manage Roles & Permissions', 'slug' => 'manage-roles', 'group_name' => 'User Management'],
            // Site Settings
            ['name' => 'Manage Site Settings', 'slug' => 'manage-settings', 'group_name' => 'Settings'],
        ];

        $createdPermissions = [];
        foreach ($permissions as $p) {
            $perm = Permission::firstOrCreate(['slug' => $p['slug']], $p);
            $createdPermissions[] = $perm->id;
        }

        // Super Admin Role
        $superAdminRole = Role::firstOrCreate(
            ['slug' => 'super-admin'],
            ['name' => 'Super Admin', 'description' => 'Full administrative access to everything']
        );
        $superAdminRole->permissions()->sync($createdPermissions);

        // Manager Role
        $managerRole = Role::firstOrCreate(
            ['slug' => 'manager'],
            ['name' => 'Store Manager', 'description' => 'Can manage products, categories and orders']
        );
        $managerRole->permissions()->sync(
            Permission::whereIn('slug', ['view-products', 'create-products', 'edit-products', 'manage-categories', 'view-orders', 'update-orders', 'manage-customers'])->pluck('id')
        );

        // 2. Admins
        $admin = Admin::firstOrCreate(
            ['email' => 'admin@indexfurniture.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'phone' => '+880 1847088444',
                'status' => true,
            ]
        );
        $admin->roles()->sync([$superAdminRole->id]);

        // 3. Customer User
        User::firstOrCreate(
            ['email' => 'customer@indexfurniture.com'],
            [
                'name' => 'Zainal Abedin',
                'password' => Hash::make('password123'),
                'phone' => '+880 1712345678',
                'address' => 'House 14, Road 5, Dhanmondi',
                'district' => 'Dhaka',
                'status' => true,
            ]
        );

        // 4. Shipping Charges
        $shippingMethods = [
            [
                'title' => 'Home Delivery (Inside Dhaka)',
                'key' => 'inside_dhaka',
                'charge' => 1094.00,
                'description' => 'Fast delivery inside Dhaka metropolitan area (3-5 business days)',
                'status' => true,
            ],
            [
                'title' => 'Home Delivery (Outside Dhaka)',
                'key' => 'outside_dhaka',
                'charge' => 2500.00,
                'description' => 'Nationwide home delivery across all districts (5-10 business days)',
                'status' => true,
            ],
            [
                'title' => 'Pickup From Showroom',
                'key' => 'pickup',
                'charge' => 0.00,
                'description' => 'Collect directly from your selected Indexfurniture showroom at no charge',
                'status' => true,
            ],
        ];

        foreach ($shippingMethods as $sm) {
            ShippingCharge::updateOrCreate(['key' => $sm['key']], $sm);
        }

        // 5. Colors & Sizes
        $colors = [
            ['name' => 'American Teak-Beige', 'code' => '#C4A482'],
            ['name' => 'Natural Oak', 'code' => '#D2B48C'],
            ['name' => 'Royal Purple', 'code' => '#5B2C6F'],
            ['name' => 'Mahogany', 'code' => '#4E1609'],
            ['name' => 'Charcoal Grey', 'code' => '#36454F'],
            ['name' => 'Warm Beige', 'code' => '#EEDC82'],
            ['name' => 'Wenge Dark Brown', 'code' => '#3B2F2F'],
        ];
        $createdColors = [];
        foreach ($colors as $c) {
            $createdColors[] = Color::firstOrCreate(['name' => $c['name']], $c);
        }

        $sizes = [
            ['name' => 'Standard', 'code' => 'STD'],
            ['name' => 'King Size', 'code' => 'KING'],
            ['name' => 'Queen Size', 'code' => 'QUEEN'],
            ['name' => '3 Seater', 'code' => '3S'],
            ['name' => 'Double Seater', 'code' => '2S'],
            ['name' => 'Single Seater', 'code' => '1S'],
        ];
        $createdSizes = [];
        foreach ($sizes as $s) {
            $createdSizes[] = Size::firstOrCreate(['name' => $s['name']], $s);
        }

        // 6. Categories & Subcategories & Childcategories
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ProductImage::truncate();
        DB::table('product_colors')->truncate();
        DB::table('product_sizes')->truncate();
        Product::truncate();
        ChildCategory::truncate();
        SubCategory::truncate();
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Exactly 10 Categories (All with Images)
        $categoriesData = [
            [
                'id' => 1,
                'name' => 'BEDROOM',
                'slug' => 'bedroom',
                'order' => 1,
                'image' => 'cat_bedroom.jpg',
                'is_popular_home' => true,
                'is_popular_office' => false,
                'is_featured' => true,
                'status' => true,
            ],
            [
                'id' => 2,
                'name' => 'LIVING',
                'slug' => 'living',
                'order' => 2,
                'image' => 'cat_living.jpg',
                'is_popular_home' => true,
                'is_popular_office' => false,
                'is_featured' => true,
                'status' => true,
            ],
            [
                'id' => 3,
                'name' => 'DINING',
                'slug' => 'dining',
                'order' => 3,
                'image' => 'cat_dining.jpg',
                'is_popular_home' => true,
                'is_popular_office' => false,
                'is_featured' => true,
                'status' => true,
            ],
            [
                'id' => 4,
                'name' => 'STUDY ROOM',
                'slug' => 'study-room',
                'order' => 4,
                'image' => 'cat_study.jpg',
                'is_popular_home' => true,
                'is_popular_office' => false,
                'is_featured' => false,
                'status' => true,
            ],
            [
                'id' => 5,
                'name' => 'STORAGE',
                'slug' => 'storage',
                'order' => 5,
                'image' => 'cat_storage.jpg',
                'is_popular_home' => false,
                'is_popular_office' => false,
                'is_featured' => false,
                'status' => true,
            ],
            [
                'id' => 6,
                'name' => 'OFFICE CHAIR',
                'slug' => 'office-chair',
                'order' => 6,
                'image' => 'cat_office_chair.jpg',
                'is_popular_home' => false,
                'is_popular_office' => true,
                'is_featured' => true,
                'status' => true,
            ],
            [
                'id' => 7,
                'name' => 'OFFICE TABLE',
                'slug' => 'office-table',
                'order' => 7,
                'image' => 'cat_office_table.jpg',
                'is_popular_home' => false,
                'is_popular_office' => true,
                'is_featured' => true,
                'status' => true,
            ],
            [
                'id' => 8,
                'name' => 'ALLIED',
                'slug' => 'allied',
                'order' => 8,
                'image' => 'cat_allied.jpg',
                'is_popular_home' => false,
                'is_popular_office' => false,
                'is_featured' => false,
                'status' => true,
            ],
            [
                'id' => 9,
                'name' => 'HOSPITAL',
                'slug' => 'hospital',
                'order' => 9,
                'image' => 'cat_hospital.jpg',
                'is_popular_home' => false,
                'is_popular_office' => false,
                'is_featured' => false,
                'status' => true,
            ],
            [
                'id' => 10,
                'name' => 'INTERIOR',
                'slug' => 'interior',
                'order' => 10,
                'image' => 'cat_interior.jpg',
                'is_popular_home' => false,
                'is_popular_office' => false,
                'is_featured' => false,
                'status' => true,
            ],
        ];

        foreach ($categoriesData as $c) {
            Category::create($c);
        }

        // Exactly 10 Subcategories (All with Images)
        $subCategoriesData = [
            [
                'id' => 1,
                'category_id' => 1, // BEDROOM
                'name' => 'Bed Side Cabinet',
                'slug' => 'bed-side-cabinet',
                'image' => 'subcat_bedside.jpg',
                'order' => 1,
                'status' => true,
            ],
            [
                'id' => 2,
                'category_id' => 1, // BEDROOM
                'name' => 'Bed',
                'slug' => 'bed',
                'image' => 'subcat_bed.jpg',
                'order' => 2,
                'status' => true,
            ],
            [
                'id' => 3,
                'category_id' => 1, // BEDROOM
                'name' => 'Wardrobe',
                'slug' => 'wardrobe',
                'image' => 'subcat_wardrobe.jpg',
                'order' => 3,
                'status' => true,
            ],
            [
                'id' => 4,
                'category_id' => 1, // BEDROOM
                'name' => 'Dressing Table',
                'slug' => 'dressing-table',
                'image' => 'subcat_dressing.jpg',
                'order' => 4,
                'status' => true,
            ],
            [
                'id' => 5,
                'category_id' => 2, // LIVING
                'name' => 'Living Room Sofa',
                'slug' => 'living-room-sofa',
                'image' => 'subcat_sofa.jpg',
                'order' => 5,
                'status' => true,
            ],
            [
                'id' => 6,
                'category_id' => 3, // DINING
                'name' => 'Dining Table & Set',
                'slug' => 'dining-table-set',
                'image' => 'subcat_dining.jpg',
                'order' => 6,
                'status' => true,
            ],
            [
                'id' => 7,
                'category_id' => 4, // STUDY ROOM
                'name' => 'Study Table',
                'slug' => 'study-table',
                'image' => 'subcat_study.jpg',
                'order' => 7,
                'status' => true,
            ],
            [
                'id' => 8,
                'category_id' => 5, // STORAGE
                'name' => 'Cupboard & Storage',
                'slug' => 'cupboard-storage',
                'image' => 'subcat_storage.jpg',
                'order' => 8,
                'status' => true,
            ],
            [
                'id' => 9,
                'category_id' => 6, // OFFICE CHAIR
                'name' => 'Executive Seating',
                'slug' => 'executive-seating',
                'image' => 'subcat_executive_chair.jpg',
                'order' => 9,
                'status' => true,
            ],
            [
                'id' => 10,
                'category_id' => 7, // OFFICE TABLE
                'name' => 'Workstation Table',
                'slug' => 'workstation-table',
                'image' => 'subcat_workstation.jpg',
                'order' => 10,
                'status' => true,
            ],
        ];

        foreach ($subCategoriesData as $sub) {
            SubCategory::create($sub);
        }

        // Exactly 10 Child Categories (All with Images)
        $childCategoriesData = [
            [
                'id' => 1,
                'category_id' => 1, // BEDROOM
                'sub_category_id' => 1, // Bed Side Cabinet
                'name' => 'Laminate Bed Side',
                'slug' => 'laminate-bed-side',
                'image' => 'childcat_laminate_bedside.jpg',
                'order' => 1,
                'status' => true,
            ],
            [
                'id' => 2,
                'category_id' => 1, // BEDROOM
                'sub_category_id' => 2, // Bed
                'name' => 'King Size Bed',
                'slug' => 'king-size-bed',
                'image' => 'childcat_king_bed.jpg',
                'order' => 2,
                'status' => true,
            ],
            [
                'id' => 3,
                'category_id' => 1, // BEDROOM
                'sub_category_id' => 3, // Wardrobe
                'name' => '3 Door Wardrobe',
                'slug' => '3-door-wardrobe',
                'image' => 'childcat_3door_wardrobe.jpg',
                'order' => 3,
                'status' => true,
            ],
            [
                'id' => 4,
                'category_id' => 1, // BEDROOM
                'sub_category_id' => 4, // Dressing Table
                'name' => 'Laminate Dressing Table',
                'slug' => 'laminate-dressing-table',
                'image' => 'childcat_dressing_table.jpg',
                'order' => 4,
                'status' => true,
            ],
            [
                'id' => 5,
                'category_id' => 2, // LIVING
                'sub_category_id' => 5, // Living Room Sofa
                'name' => 'Three Seater Sofa',
                'slug' => 'three-seater-sofa',
                'image' => 'childcat_3seater_sofa.jpg',
                'order' => 5,
                'status' => true,
            ],
            [
                'id' => 6,
                'category_id' => 3, // DINING
                'sub_category_id' => 6, // Dining Table & Set
                'name' => '6 Seater Dining Set',
                'slug' => '6-seater-dining-set',
                'image' => 'childcat_dining_set.jpg',
                'order' => 6,
                'status' => true,
            ],
            [
                'id' => 7,
                'category_id' => 4, // STUDY ROOM
                'sub_category_id' => 7, // Study Table
                'name' => 'Executive Study Table',
                'slug' => 'executive-study-table',
                'image' => 'childcat_study_table.jpg',
                'order' => 7,
                'status' => true,
            ],
            [
                'id' => 8,
                'category_id' => 5, // STORAGE
                'sub_category_id' => 8, // Cupboard & Storage
                'name' => 'Filing & Storage Cabinet',
                'slug' => 'filing-storage-cabinet',
                'image' => 'childcat_cabinet.jpg',
                'order' => 8,
                'status' => true,
            ],
            [
                'id' => 9,
                'category_id' => 6, // OFFICE CHAIR
                'sub_category_id' => 9, // Executive Seating
                'name' => 'Ergonomic Revolving Chair',
                'slug' => 'ergonomic-revolving-chair',
                'image' => 'childcat_office_chair.jpg',
                'order' => 9,
                'status' => true,
            ],
            [
                'id' => 10,
                'category_id' => 7, // OFFICE TABLE
                'sub_category_id' => 10, // Workstation Table
                'name' => 'Modular Computer Workstation',
                'slug' => 'modular-computer-workstation',
                'image' => 'childcat_workstation.jpg',
                'order' => 10,
                'status' => true,
            ],
        ];

        foreach ($childCategoriesData as $child) {
            ChildCategory::create($child);
        }

        // 7. Exactly 10 Products (with primary image AND multiple gallery images)
        $productsData = [
            [
                'id' => 1,
                'title' => 'CASTLE - Index Bed Side Cabinet A120LB',
                'slug' => 'castle-index-bed-side-cabinet-a120lb',
                'product_code' => 'CASTLE-A120LB',
                'category_id' => 1, // BEDROOM
                'sub_category_id' => 1, // Bed Side Cabinet
                'child_category_id' => 1, // Laminate Bed Side
                'price' => 3800.00,
                'discount_price' => null,
                'dimension' => '404mmX380mmX388mm',
                'warranty' => 'One Year for any manufacturing defects',
                'short_description' => 'Index Bed side Cabinet A120LB with open shelf and smooth finish.',
                'description' => "• Made of high quality scratch free melamine faced laminated board\n• PVC edge banded with hot melt glue to protect moisture gaining\n• Anti-insects chemicals added to make the board termite-proof\n• High-quality fasteners used for assembly\n• Knock down product - assembly required\n• Indoor use only.",
                'image' => 'prod_bedside_a120lb_1.jpg',
                'gallery' => ['prod_bedside_a120lb_2.jpg', 'prod_bedside_a120lb_3.jpg'],
                'stock' => 25,
                'is_featured' => true,
                'is_popular' => true,
                'cash_on_delivery' => true,
                'status' => true,
            ],
            [
                'id' => 2,
                'title' => 'ROYAL - Index King Size Master Bed KB101',
                'slug' => 'royal-index-king-size-master-bed-kb101',
                'product_code' => 'ROYAL-KB101',
                'category_id' => 1, // BEDROOM
                'sub_category_id' => 2, // Bed
                'child_category_id' => 2, // King Size Bed
                'price' => 48500.00,
                'discount_price' => 45000.00,
                'dimension' => '1880mmX2100mmX1050mm',
                'warranty' => 'One Year service warranty for structure and timber',
                'short_description' => 'Solid wooden look king size master bed with carved ornamental headboard.',
                'description' => "• Designed for ultimate comfort and royal ambiance\n• Sturdy base with anti-creak joints\n• Melamine laminated termite-proof construction\n• Superior weight support and premium finish.",
                'image' => 'prod_king_bed_1.jpg',
                'gallery' => ['prod_king_bed_2.jpg', 'prod_king_bed_3.jpg'],
                'stock' => 10,
                'is_featured' => true,
                'is_popular' => true,
                'cash_on_delivery' => false,
                'status' => true,
            ],
            [
                'id' => 3,
                'title' => 'ELEGANCE - Index 3-Door Cupboard & Wardrobe WDC07',
                'slug' => 'elegance-index-3-door-cupboard-wardrobe-wdc07',
                'product_code' => 'WDC-07',
                'category_id' => 1, // BEDROOM
                'sub_category_id' => 3, // Wardrobe
                'child_category_id' => 3, // 3 Door Wardrobe
                'price' => 34900.00,
                'discount_price' => 32500.00,
                'dimension' => '1200mmX550mmX1900mm',
                'warranty' => 'One Year service warranty',
                'short_description' => 'Spacious 3-door wooden cupboard with internal locker and hanging rails.',
                'description' => "• Premium melamine laminated moisture-proof board\n• Soft close European hinges for silent operation\n• Safety key locker inside for personal jewelry & cash\n• Ample shelving for organized garment storage.",
                'image' => 'prod_wardrobe_3door_1.jpg',
                'gallery' => ['prod_wardrobe_3door_2.jpg', 'prod_wardrobe_3door_3.jpg'],
                'stock' => 15,
                'is_featured' => true,
                'is_popular' => true,
                'cash_on_delivery' => false,
                'status' => true,
            ],
            [
                'id' => 4,
                'title' => 'AURORA - Index Dressing Table with Mirror DT156',
                'slug' => 'aurora-index-dressing-table-with-mirror-dt156',
                'product_code' => 'DT-156',
                'category_id' => 1, // BEDROOM
                'sub_category_id' => 4, // Dressing Table
                'child_category_id' => 4, // Laminate Dressing Table
                'price' => 15200.00,
                'discount_price' => 13900.00,
                'dimension' => '850mmX400mmX1750mm',
                'warranty' => 'One Year manufacturing warranty',
                'short_description' => 'Contemporary dressing table with high-clarity mirror and side shelves.',
                'description' => "• Full-length premium distortion-free crystal mirror\n• Concealed side cosmetic shelves and smooth gliding drawers\n• Ergonomic compact footprint ideal for modern bedrooms\n• Scratch-resistant finish with warm wood grain.",
                'image' => 'prod_dressing_table_1.jpg',
                'gallery' => ['prod_dressing_table_2.jpg', 'prod_dressing_table_3.jpg'],
                'stock' => 18,
                'is_featured' => true,
                'is_popular' => false,
                'cash_on_delivery' => true,
                'status' => true,
            ],
            [
                'id' => 5,
                'title' => 'COMFORT - Index Luxury Sofa Set-A130FF',
                'slug' => 'comfort-index-luxury-sofa-set-a130ff',
                'product_code' => 'SOFA-A130FF',
                'category_id' => 2, // LIVING
                'sub_category_id' => 5, // Living Room Sofa
                'child_category_id' => 5, // Three Seater Sofa
                'price' => 50900.00,
                'discount_price' => null,
                'dimension' => '1800mmX850mmX800mm',
                'warranty' => 'One Year service warranty for foam and structure',
                'short_description' => 'Contemporary luxury sofa set with unmatched durability and premium fabric upholstery.',
                'description' => "• Premium imported fabric upholstery with water-repellent finish\n• High resilience ergonomic foam cushions for long sitting comfort\n• Solid treated timber frame with termite and borer resistance\n• Clean contemporary lines tailored for modern living spaces.",
                'image' => 'prod_sofa_a130ff_1.jpg',
                'gallery' => ['prod_sofa_a130ff_2.jpg', 'prod_sofa_a130ff_3.jpg'],
                'stock' => 12,
                'is_featured' => true,
                'is_popular' => true,
                'cash_on_delivery' => false,
                'status' => true,
            ],
            [
                'id' => 6,
                'title' => 'IMPERIAL - Index 6-Seater Wooden Dining Set DS215',
                'slug' => 'imperial-index-6-seater-wooden-dining-set-ds215',
                'product_code' => 'DINING-DS215',
                'category_id' => 3, // DINING
                'sub_category_id' => 6, // Dining Table & Set
                'child_category_id' => 6, // 6 Seater Dining Set
                'price' => 48000.00,
                'discount_price' => 44500.00,
                'dimension' => '1600mmX900mmX760mm',
                'warranty' => 'One Year service warranty for wood joints',
                'short_description' => 'Elegant solid wood finish 6-seater dining table with 6 cushioned chairs.',
                'description' => "• High-durability heat & water-resistant lacquer top\n• 6 ergonomic wooden chairs with high-density foam cushions\n• Engineered for family gatherings and festive dining\n• Termite and fungal treated seasoned timber construction.",
                'image' => 'prod_dining_set_1.jpg',
                'gallery' => ['prod_dining_set_2.jpg', 'prod_dining_set_3.jpg'],
                'stock' => 8,
                'is_featured' => true,
                'is_popular' => true,
                'cash_on_delivery' => false,
                'status' => true,
            ],
            [
                'id' => 7,
                'title' => 'SCHOLAR - Index Executive Study Table TCCP001',
                'slug' => 'scholar-index-executive-study-table-tccp001',
                'product_code' => 'STUDY-TCCP001',
                'category_id' => 4, // STUDY ROOM
                'sub_category_id' => 7, // Study Table
                'child_category_id' => 7, // Executive Study Table
                'price' => 14500.00,
                'discount_price' => 12900.00,
                'dimension' => '1100mmX550mmX750mm',
                'warranty' => 'One Year manufacturing warranty',
                'short_description' => 'Student & work-from-home study desk with drawer and CPU shelf.',
                'description' => "• Spacious desktop surface for dual monitors or laptop + writing\n• Dedicated cable grommet for tidy wire management\n• Lockable drawer for stationery and vital documents\n• Durable melamine board resistant to liquid spills.",
                'image' => 'prod_study_table_1.jpg',
                'gallery' => ['prod_study_table_2.jpg', 'prod_study_table_3.jpg'],
                'stock' => 20,
                'is_featured' => false,
                'is_popular' => true,
                'cash_on_delivery' => true,
                'status' => true,
            ],
            [
                'id' => 8,
                'title' => 'STORAGE PRO - Index Multi-Utility Cabinet SC002',
                'slug' => 'storage-pro-index-multi-utility-cabinet-sc002',
                'product_code' => 'CAB-SC002',
                'category_id' => 5, // STORAGE
                'sub_category_id' => 8, // Cupboard & Storage
                'child_category_id' => 8, // Filing & Storage Cabinet
                'price' => 18700.00,
                'discount_price' => null,
                'dimension' => '800mmX400mmX1200mm',
                'warranty' => 'One Year manufacturing warranty',
                'short_description' => 'Compact multi-purpose storage cabinet with adjustable internal shelves.',
                'description' => "• Multi-tier reinforced storage shelves holding up to 25kg per tier\n• Clean minimalist design suited for bedrooms, study or office\n• Lockable double doors for secured filing and valuables\n• Sturdy moisture-resistant base support.",
                'image' => 'prod_storage_cabinet_1.jpg',
                'gallery' => ['prod_storage_cabinet_2.jpg', 'prod_storage_cabinet_3.jpg'],
                'stock' => 14,
                'is_featured' => false,
                'is_popular' => false,
                'cash_on_delivery' => true,
                'status' => true,
            ],
            [
                'id' => 9,
                'title' => 'PREMIUM - Index Executive High-Back Chair OC501',
                'slug' => 'premium-index-executive-high-back-chair-oc501',
                'product_code' => 'CHAIR-OC501',
                'category_id' => 6, // OFFICE CHAIR
                'sub_category_id' => 9, // Executive Seating
                'child_category_id' => 9, // Ergonomic Revolving Chair
                'price' => 19800.00,
                'discount_price' => 17900.00,
                'dimension' => '650mmX650mmX1180-1260mm',
                'warranty' => 'One Year warranty on hydraulic gas lift and mechanism',
                'short_description' => 'Ergonomic revolving executive chair with lumbar support and chrome base.',
                'description' => "• Breathable mesh backrest with integrated lumbar spine curve\n• Class-4 hydraulic gas lift for smooth height adjustment\n• Heavy-duty chrome swivel base with 360-degree nylon castors\n• Multi-position tilt locking mechanism for relaxing breaks.",
                'image' => 'prod_office_chair_1.jpg',
                'gallery' => ['prod_office_chair_2.jpg', 'prod_office_chair_3.jpg'],
                'stock' => 30,
                'is_featured' => true,
                'is_popular' => true,
                'cash_on_delivery' => true,
                'status' => true,
            ],
            [
                'id' => 10,
                'title' => 'MATRIX - Index Office Workstation Table WOSP001',
                'slug' => 'matrix-index-office-workstation-table-wosp001',
                'product_code' => 'WORK-WOSP001',
                'category_id' => 7, // OFFICE TABLE
                'sub_category_id' => 10, // Workstation Table
                'child_category_id' => 10, // Modular Computer Workstation
                'price' => 26400.00,
                'discount_price' => null,
                'dimension' => '1400mmX700mmX750mm',
                'warranty' => 'One Year structural warranty',
                'short_description' => 'Modern commercial workstation desk with powder-coated steel legs and modesty panel.',
                'description' => "• High-density melamine top with 2mm PVC impact edging\n• Heavy-gauge laser cut metal frame with rust-proof powder coating\n• Integrated cable tray raceway for clean computer setups\n• Modular expansion ready for single or multi-person pods.",
                'image' => 'prod_workstation_1.jpg',
                'gallery' => ['prod_workstation_2.jpg', 'prod_workstation_3.jpg'],
                'stock' => 16,
                'is_featured' => true,
                'is_popular' => true,
                'cash_on_delivery' => false,
                'status' => true,
            ],
        ];

        foreach ($productsData as $pData) {
            $gallery = $pData['gallery'] ?? [];
            unset($pData['gallery']);

            $product = Product::create($pData);

            // Attach Colors & Sizes
            $product->colors()->sync([$createdColors[0]->id, $createdColors[1]->id, $createdColors[2]->id]);
            $product->sizes()->sync([$createdSizes[0]->id, $createdSizes[1]->id]);

            // Seed Multiple Gallery Images
            foreach ($gallery as $gIndex => $gImage) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $gImage,
                    'order' => $gIndex + 1,
                ]);
            }
        }

        // 8. Site Settings
        $settings = [
            'site_name' => 'Indexfurniture',
            'tagline' => 'Living Reimagined',
            'phone_primary' => '+880 9613808080',
            'phone_secondary' => '+88 01847088444',
            'customer_care_hours' => '9 AM - 6 PM',
            'email' => 'info@indexfurniture.com',
            'address' => 'Indexfurniture Center (3rd Floor), Plot 12, Block CWS (C), Gulshan South Avenue, Gulshan-1, Dhaka 1212, Bangladesh.',
            'copyright' => 'Copyright ' . date('Y') . ' © Indexfurniture. All Rights Reserved.',
            'developed_by' => 'Developed By Access InfoTech Ltd.',
            'facebook' => 'https://facebook.com',
            'twitter' => 'https://twitter.com',
            'linkedin' => 'https://linkedin.com',
            'instagram' => 'https://instagram.com',
            'youtube' => 'https://youtube.com',
            'banner_title_1' => 'FOR YOUR COMFORT',
            'banner_subtitle_1' => 'Transform your bedroom into an oasis of relaxation with Index signature beds.',
            'banner_title_2' => 'UNMATCHED DURABILITY',
            'banner_subtitle_2' => 'Living room sofas engineered with high resilience foam and treated timber.',
        ];

        foreach ($settings as $key => $val) {
            SiteSetting::set($key, $val);
        }

        // 9. Sliders
        $slides = [
            [
                'image' => 'otobi_banner_slide_1.jpg',
                'subtitle' => 'FOR',
                'title' => 'YOUR COMFORT',
                'description' => 'Experience authentic master bedroom suites handcrafted with termite-proof laminated board and solid timber.',
                'btn_text' => 'Explore Bedroom Collection >',
                'btn_link' => '/products?category=bedroom',
                'order' => 1,
                'status' => true,
            ],
            [
                'image' => 'otobi_banner_slide_2.jpg',
                'subtitle' => 'SUPERIOR LIVING',
                'title' => 'UNMATCHED DURABILITY',
                'description' => 'Luxurious contemporary sofas engineered with high resilience foam and premium stain-resistant fabric.',
                'btn_text' => 'Explore Sofas & Living >',
                'btn_link' => '/products?category=living',
                'order' => 2,
                'status' => true,
            ],
            [
                'image' => 'otobi_banner_slide_3.jpg',
                'subtitle' => 'COMMERCIAL SPACES',
                'title' => 'OFFICE SOLUTIONS',
                'description' => 'Ergonomic workstations, executive director desks, and conference tables designed for optimum productivity.',
                'btn_text' => 'Explore Office Furniture >',
                'btn_link' => '/products?category=office-table',
                'order' => 3,
                'status' => true,
            ],
        ];

        foreach ($slides as $sData) {
            \App\Models\Slider::updateOrCreate(['image' => $sData['image']], $sData);
        }

        // 10. Solutions
        $livingCat = Category::where('slug', 'living')->orWhere('slug', 'home-furniture')->first();
        $officeCat = Category::where('slug', 'office-table')->orWhere('slug', 'office-furniture')->first();
        $hospitalCat = Category::where('slug', 'hospital')->first();
        $interiorCat = Category::where('slug', 'interior')->first();

        $solutions = [
            [
                'name' => 'Home Solutions',
                'image' => 'otobi_solution_home.jpg',
                'category_id' => $livingCat?->id,
                'custom_url' => null,
                'order' => 1,
                'status' => true,
            ],
            [
                'name' => 'Office Solutions',
                'image' => 'otobi_solution_office.jpg',
                'category_id' => $officeCat?->id,
                'custom_url' => null,
                'order' => 2,
                'status' => true,
            ],
            [
                'name' => 'Hospital Solutions',
                'image' => 'otobi_solution_hospital.jpg',
                'category_id' => $hospitalCat?->id,
                'custom_url' => null,
                'order' => 3,
                'status' => true,
            ],
            [
                'name' => 'Interior Solutions',
                'image' => 'otobi_solution_interior.jpg',
                'category_id' => $interiorCat?->id,
                'custom_url' => null,
                'order' => 4,
                'status' => true,
            ],
        ];

        foreach ($solutions as $sol) {
            \App\Models\Solution::updateOrCreate(['name' => $sol['name']], $sol);
        }

        // 11. Brand Portfolio
        $brands = [
            [
                'name' => 'Index Furniture',
                'image' => 'otobi_brand_otobi.jpg',
                'url' => 'https://www.indexfurniture.com',
                'order' => 1,
                'status' => true,
            ],
            [
                'name' => 'Otomat',
                'image' => 'otobi_brand_otomat.png',
                'url' => 'https://www.indexfurniture.com',
                'order' => 2,
                'status' => true,
            ],
            [
                'name' => 'Otoply',
                'image' => 'otobi_brand_otoply.png',
                'url' => 'https://www.indexfurniture.com',
                'order' => 3,
                'status' => true,
            ],
            [
                'name' => 'Apollo',
                'image' => 'otobi_brand_apollo.png',
                'url' => 'https://www.indexfurniture.com',
                'order' => 4,
                'status' => true,
            ],
            [
                'name' => 'Index Furniture Interior',
                'image' => 'otobi_brand_interior.png',
                'url' => 'https://www.indexfurniture.com',
                'order' => 5,
                'status' => true,
            ],
        ];

        foreach ($brands as $b) {
            \App\Models\Brand::updateOrCreate(['name' => $b['name']], $b);
        }

        // 12. Pages (The Company & More Information)
        $pages = [
            ['title' => 'About Us', 'slug' => 'about-us', 'section' => 'company', 'order' => 1, 'status' => true, 'content' => '<h2>Living Reimagined with Indexfurniture</h2><p>Established in 1975 by Nitun Kundu, Indexfurniture is Bangladesh’s premier furniture brand.</p>'],
            ['title' => 'Indexfurniture Factory', 'slug' => 'factory', 'section' => 'company', 'order' => 2, 'status' => true, 'content' => '<h2>State of the Art Manufacturing</h2><p>Equipped with computerized CNC technology from Germany and Italy.</p>'],
            ['title' => 'Awards & Milestones', 'slug' => 'awards', 'section' => 'company', 'order' => 3, 'status' => true, 'content' => '<h2>National Recognition</h2><p>National Export Trophy and Best Brand Awards in furniture category.</p>'],
            ['title' => 'Corporate Solutions', 'slug' => 'corporate', 'section' => 'company', 'order' => 4, 'status' => true, 'content' => '<h2>Turnkey Commercial Workspaces</h2><p>Ergonomic workstations, meeting tables, and executive suites.</p>'],
            ['title' => 'Privacy Policy', 'slug' => 'privacy', 'section' => 'information', 'order' => 1, 'status' => true, 'content' => '<h2>Privacy Policy & Data Security</h2><p>Your privacy and transaction safety through SSLCOMMERZ are guaranteed.</p>'],
            ['title' => 'Store Location', 'slug' => 'store', 'section' => 'information', 'order' => 2, 'status' => true, 'content' => '<h2>Nationwide Showrooms</h2><p>Visit Indexfurniture flagship showrooms across Dhaka, Chittagong, Sylhet, and other districts.</p>'],
            ['title' => 'Delivery Coverage Area', 'slug' => 'coverage', 'section' => 'information', 'order' => 3, 'status' => true, 'content' => '<h2>Nationwide Doorstep Delivery</h2><p>Fast delivery across all 64 districts in Bangladesh with free assembly.</p>'],
            ['title' => 'Sitemap', 'slug' => 'sitemap', 'section' => 'information', 'order' => 4, 'status' => true, 'content' => '<h2>Storefront Directory</h2><p>Browse full catalog, categories, cart, and account management.</p>'],
        ];

        foreach ($pages as $p) {
            \App\Models\Page::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // 13. Order Statuses
        $statuses = [
            ['name' => 'Pending', 'slug' => 'pending', 'bg_color' => '#fef3c7', 'text_color' => '#b45309', 'order' => 1, 'is_default' => true, 'status' => true],
            ['name' => 'Processing', 'slug' => 'processing', 'bg_color' => '#e0f2fe', 'text_color' => '#0369a1', 'order' => 2, 'is_default' => false, 'status' => true],
            ['name' => 'Shipped', 'slug' => 'shipped', 'bg_color' => '#f3e8ff', 'text_color' => '#7e22ce', 'order' => 3, 'is_default' => false, 'status' => true],
            ['name' => 'Delivered', 'slug' => 'delivered', 'bg_color' => '#dcfce7', 'text_color' => '#15803d', 'order' => 4, 'is_default' => false, 'status' => true],
            ['name' => 'Cancelled', 'slug' => 'cancelled', 'bg_color' => '#fee2e2', 'text_color' => '#b91c1c', 'order' => 5, 'is_default' => false, 'status' => true],
        ];

        foreach ($statuses as $st) {
            \App\Models\OrderStatus::updateOrCreate(['slug' => $st['slug']], $st);
        }
    }
}
