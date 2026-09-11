<?php
/* Template Name: Customhome */

get_header();

// Redirect if user is not logged in
if (!is_user_logged_in()) {
    wp_redirect('/my-account/');
    exit();
}

// Fetch brand data
$brand_id = get_user_brand_id(); // Replace this with your actual logic
$brand_info = get_brand_data($brand_id);
$products = get_brand_products($brand_id);
?>

        <!-- Top Section -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

<div class="container mb-3 w-100" style="margin-top: 12%;">
    <div class="d-flex justify-content-between py-2 px-2 mcc-school">
        <p class="mcc-text m-0">
            <?php echo !empty($brand_info['name']) ? esc_html($brand_info['name']) : 'Default Brand Name'; ?>
        </p>
        <div class="mcc-logo-div">
            <img class="school-logo"
                 src="<?php echo !empty($brand_info['logo_url']) ? esc_url($brand_info['logo_url']) : '/wp-content/uploads/2024/05/default-logo.png'; ?>"
                 alt="<?php echo !empty($brand_info['name']) ? esc_attr($brand_info['name']) : 'Default Brand'; ?>"
                 class="w-100 h-100">
        </div>
    </div>
</div>

<!-- Desktop Section -->
<div class="container mob-hide">
    <div class="row py-5">
        <!-- Sidebar -->
        <div class="col-md-4 col-lg-3 col-sm-12 py-5">
            <div class="fixed-top">
                <div class="head-text">
                    <h3 class="border-bottom border-5 pb-2 border-black fw-bold">Categories</h3>
                </div>
                <ul class="p-0 list-unstyled m-0">
                    <?php
                    $categories_to_display = [];
                    $unique_categories = []; // To track unique categories

                    foreach ($products as $category) {
                        // Check if the category is already in the unique set
                        if (!isset($unique_categories[$category['category_slug']])) {
                            // Add category to the unique set
                            $unique_categories[$category['category_slug']] = true;

                            // Add category to the display array with its products
                            $categories_to_display[] = [
                                'category_slug' => $category['category_slug'],
                                'category_name' => $category['name'],
                                'products' => $category['products'] // Assuming you want to show products within this category
                            ];
                        }
                    }

                    // Get WooCommerce product categories
                    $product_categories = get_terms(array(
                        'taxonomy' => 'product_cat',
                        'hide_empty' => false, // Change to true if you want to hide empty categories
                    ));

                    // Loop through product categories
                    foreach ($categories_to_display as $category) {
                        // Skip Uncategorized category
                        if ($category['category_name'] == 'Uncategorized') {
                            continue;
                        }
                        ?>
                    <li class="mt-1">
                        <a class="text-decoration-none text-black category-link"
                           href="#<?php echo esc_attr($category['category_slug']); ?>">
                                <?php echo esc_attr($category['category_name']); ?>
                        </a>
                    </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Product Listing -->
        <div class="col-md-8 col-lg-9 col-sm-12 py-5">
            <div class="row">
                <?php
                $max_products_per_row = 4;

                // Flatten all products across categories while keeping category metadata
                $categories_to_render = [];
                foreach ($products as $category) {
                    foreach ($category['products'] as $product) {
                        $categories_to_render[] = [
                            'category_slug' => $category['category_slug'],
                            'category_name' => $category['name'],
                            'product' => $product
                        ];
                    }
                }

                // Function to dynamically calculate column classes based on product count
                function calculate_col_class($product_count)
                {
                    if ($product_count >= 4) {
                        return [12, 3];
                    } elseif ($product_count == 3) {
                        return [9, 4];
                    } elseif ($product_count == 2) {
                        return [6, 6];
                    } elseif ($product_count == 1) {
                        return [3, 12];
                    }
                    return [12, 3];
                }

                // Function to render a category block
                function render_category_block($category_slug, $category_name, $rows, $col_class) {
                    echo "<div id='{$category_slug}' class='mb-4 col-{$col_class[0]}'>
                            <h6 class='catTitle text-light py-3 px-2'>{$category_name}</h6>
                            <div class='row'>";

                    foreach ($rows as $i => $product_data) {
                        $product = $product_data['product'];
                        $product_obj = wc_get_product($product['id']);
                        $product_slug = $product_obj->get_slug();
                        $product_slug_link = get_permalink($product['id']);
                        $quantity_id = "quantity-{$category_slug}-{$i}";

                        // Fetch tiered pricing data
                        $tiered_prices = get_post_meta($product['id'], '_fixed_price_rules', true);

                        echo "<div class='col-md-{$col_class[1]} col-lg-{$col_class[1]} position-relative'>
                                <a href='" . esc_url($product_slug_link) . "'><img src='" . esc_url($product['image_url']) . "' alt='" . esc_attr($category_name) . "' class='responsive-img img-hover'></a>";
                        if ($tiered_prices) {
                            echo "<div class='eye-icon'><i class='fa-regular fa-eye'></i></div>
                                <div style='display:none;border-width:8px;' class='dropdown tabler-dropdown rounded-3 bg-white'><div class='position-relative'>
								<div class='close-icon  p-1 position-absolute end-0 me-1 top-0'><i class='fa fa-close'></i></div>
                                    <table class='table table-bordered mb-0 rounded-3 border-dark'>
                                        <thead>
                                            <tr>
                                                <th class='text-center' colspan='8'>{$product['name']}</th>
                                            </tr>
                                            <tr>
                                                <td class='bg-theme-light'><strong>Qty</strong></td>
                                                <td class='text-center bg-theme-light'><strong>Discount(%)</strong></td>
                                                <td class='bg-theme-light'><strong>Price</strong></td>
                                            </tr>
                                        </thead>
                                        <tbody>";
                            if (!empty($tiered_prices)) {
                                foreach ($tiered_prices as $quantity => $price) {
                                    $product_price = (float)substr(preg_replace('/[^0-9.]/', '', $product['price']), 2);
                                    $discount_percentage = ($product_price - $price) / $product_price * 100;
                                    $discount = (int)$discount_percentage;

                                    echo "<tr>
                                        <td>{$quantity}</td>
                                        <td class='text-center'>{$discount}%</td>
                                        <td>{$price}</td>
                                      </tr>";
                                }
                            } else {
                                echo "<tr>
                                    <td colspan='3'>No tiered pricing available</td>
                                  </tr>";
                            }

                            echo "          </tbody>
                                    </table>
									</div>
                                </div>";
                        }
                        echo "
                                <div class='d-flex align-items-center w-100 pt-2'>
                                    <h6 class='fw-medium price-text'>{$product['price']} ea</h6>
                                </div>
                                <div class='d-flex py-2 justify-content-between align-items-center'>
                                   ";
                        if ($product['type'] === "variable") {
                            echo "
							 <div class='d-flex qty gap-1'>
                                        <label for='{$quantity_id}' class='mt-2'>Qty:</label>
                                        <input type='number' id='{$quantity_id}' style='font-size: 12px;
    padding: 0px 0px 0px 8px;
    width: 39px;'  class='responsive-input' value='1' min='1' name='quantity'>
                                    </div>
							<a href='" . esc_url($product_slug_link) . "' type='button' class='btn info-btn ms-2 fw-bold' style='font-size:14px!important;'>View Options</a>
                                </div>";
                        } elseif ($tiered_prices) {
                            echo " <form method='post' action='" . esc_url(WC()->cart->get_cart_url()) . "'>
        <input type='hidden' name='add-to-cart' value='{$product['id']}' >
        <input type='hidden' name='product_id' value='{$product['id']}'>
        <div class='d-flex qty gap-1'>
            <label for='{$quantity_id}' class='mt-2'>Qty:</label>
            <input type='number' name='quantity' style='font-size: 12px;
    padding: 0px 0px 0px 8px;
    width: 39px;' id='quantity_{$product['id']}' value='1' min='1'>
	 <button type='submit' class='btn info-btn ms-2 px-3' style='font-size:14px!important;' onclick='document.getElementById(\"quantity_{$product['id']}\").value = document.getElementById(\"{$quantity_id}\").value;'>Add to cart</button>
        </div>
       
    </form>
                                </div>";
                        } else {
                            echo "  <form method='post' action='" . esc_url(WC()->cart->get_cart_url()) . "'>
        <input type='hidden' name='add-to-cart' value='{$product['id']}'>
        <input type='hidden' name='product_id' value='{$product['id']}'>
        <div class='d-flex qty gap-1'>
            <label for='{$quantity_id}' class='mt-2'>Qty:</label>
            <input type='number' name='quantity' id='quantity_{$product['id']}' value='1' min='1' style='font-size: 12px;
    padding: 0px 0px 0px 8px;
    width: 39px;'>
			<button type='submit' class='btn info-btn ms-2 px-3' style='font-size:14px!important;' onclick='document.getElementById(\"quantity_{$product['id']}\").value = document.getElementById(\"{$quantity_id}\").value;'>Add to cart</button>
        </div>
        
    </form>
        </div>";
                        }
                        echo "</div>";
                    }


                    // Function to generate rows dynamically with consistent layout
                    function render_row(&$categories_to_render, $max_products_per_row)
                    {
                        // Get up to max_products_per_row items
                        $row = array_splice($categories_to_render, 0, $max_products_per_row);

                        if (empty($row)) {
                            return;
                        }

                        // Find the primary category
                        $primary_category_slug = $row[0]['category_slug'];
                        $primary_category_name = $row[0]['category_name'];

                        // Separate the products by primary and secondary categories
                        $primary_items = array_filter($row, fn($item) => $item['category_slug'] === $primary_category_slug);
                        $secondary_items = array_filter($row, fn($item) => $item['category_slug'] !== $primary_category_slug);

                        // Dynamically calculate column classes
                        $primary_col_class = calculate_col_class(count($primary_items));
                        $remaining_slots = $max_products_per_row - count($primary_items);

                        // Conditional logic for remaining slots
                        if (empty($secondary_items) && count($categories_to_render) === 0) {
                            $secondary_col_class = calculate_col_class($remaining_slots); // Adjust remaining slots
                        } else {
                            $secondary_col_class = calculate_col_class(min($remaining_slots, count($secondary_items)));
                        }

                        // Render primary category block
                        if (!empty($primary_items)) {
                            render_category_block($primary_category_slug, $primary_category_name, $primary_items, $primary_col_class);
                        }

                        // Fill the remaining slots with products from secondary categories
                        if (!empty($secondary_items)) {
                            $secondary_slug = reset($secondary_items)['category_slug'];
                            $secondary_name = reset($secondary_items)['category_name'];
                            render_category_block($secondary_slug, $secondary_name, $secondary_items, $secondary_col_class);
                        }
                    }

                    // Render all rows
                    while (!empty($categories_to_render)) {
                        render_row($categories_to_render, $max_products_per_row);
                    }
                    ?>
            </div>
        </div>
    </div>
</div>
<!-- End -->

<!-- Mobile Section -->
<div class="container desktop-hide">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 py-5">
            <div class="fixed-top">
                <div class="head-text">
                    <h3 class="border-bottom border-5 pb-2 border-black fw-bold">Categories</h3>
                </div>
                <ul class="p-0 list-unstyled m-0">
                        <?php
                        // Get WooCommerce product categories
                        $product_categories = get_terms(array(
                            'taxonomy' => 'product_cat',
                            'hide_empty' => false, // Change to true if you want to hide empty categories
                        ));

                        // Loop through product categories
                    foreach ($product_categories as $category) {
                        // Skip Uncategorized category
                        if ($category->name == 'Uncategorized') {
                            continue;
                        }
                        ?>
                    <li class="mt-1">
                        <a class="text-decoration-none text-black category-link"
                           href="#<?php echo esc_attr($category->slug); ?>_mob">
                                <?php echo esc_html($category->name); ?>
                        </a>
                    </li>
                        <?php
                    }
                        ?>
                </ul>
            </div>
        </div>
    </div>

        <?php
        foreach ($products as $category) {
            echo "<div id='" . esc_attr($category['category_slug']) . "_mob' class='mb-4'>";
            echo "<h6 class='catTitle text-dark py-3 px-2'>" . esc_html($category['name']) . "</h6>
                <div class='owl-carousel'>";
            foreach ($category['products'] as $product) {
                $product_obj = wc_get_product($product['id']);
                $product_slug = $product_obj->get_slug();
                $product_slug_link = get_permalink($product['id']);
                $product_price = $product_obj->get_price_html(); // Get the formatted price

                echo "<div class='item px-5'>
                    <a href='" . esc_url($product_slug_link) . "'><img src='" . esc_url($product['image_url']) . "' alt='" . esc_attr($category['name']) . "' class='responsive-img img-hover'></a>
                    <div class='d-flex align-items-center w-100 pt-2'>
                        <h6 class='fw-medium'>" . $product_price . "</h6>
                    </div>
                    <div class='d-flex py-2 justify-content-between align-items-center'>
                        <div class='d-flex qty gap-1'>
                            <label for='quantity-" . esc_attr($product['id']) . "' class='mt-2'>Qty:</label>
                            <input type='number' id='quantity-" . esc_attr($product['id']) . "' class='responsive-input' value='1' min='1' name='quantity'>
                        </div>";
                if ($product['type'] === "variable") {
                    echo "<a href='" . esc_url($product_slug_link) . "' type='button' class='btn info-btn ms-2 fw-bold' style='font-size:14px!important;'>View Options</a>";
                } else {
                    echo "<button type='button' class='btn info-btn ms-2 px-3' style='font-size:14px!important;'>Add to Cart</button>";
                }
                echo "</div>
                  </div>";
            }
            echo "</div>
              </div>";
        }
        ?>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
    // Initialize Owl Carousel
    jQuery(document).ready(function ($) {
        $('.owl-carousel').owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            dots: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 5
                }
            }
        });
    });


</script>

    <?php get_footer(); ?>
