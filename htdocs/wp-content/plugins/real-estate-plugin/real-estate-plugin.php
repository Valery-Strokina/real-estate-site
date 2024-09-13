<?php
/*
Plugin Name: Real Estate Plugin
Description: A plugin to manage and filter real estate properties with proper filtering for Foam Block and a clear layout.
Version: 1.4
Author: Valeriia Strokina
*/

// Register Custom Post Type for Real Estate
function create_real_estate_post_type() {
    $labels = array(
        'name' => 'Real Estate Objects',
        'singular_name' => 'Real Estate Object',
        'menu_name' => 'Real Estate',
        'name_admin_bar' => 'Real Estate Object',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Real Estate Object',
        'new_item' => 'New Real Estate Object',
        'edit_item' => 'Edit Real Estate Object',
        'view_item' => 'View Real Estate Object',
        'all_items' => 'All Real Estate Objects',
        'search_items' => 'Search Real Estate Objects',
        'not_found' => 'No Real Estate Objects found',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'rewrite' => array('slug' => 'real-estate'),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-admin-home',
    );

    register_post_type('real_estate', $args);
}
add_action('init', 'create_real_estate_post_type');

// Register Custom Taxonomy: District
function create_district_taxonomy() {
    $labels = array(
        'name' => 'Districts',
        'singular_name' => 'District',
        'search_items' => 'Search Districts',
        'all_items' => 'All Districts',
        'edit_item' => 'Edit District',
        'update_item' => 'Update District',
        'add_new_item' => 'Add New District',
        'new_item_name' => 'New District Name',
        'menu_name' => 'Districts',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'hierarchical' => true,
        'rewrite' => array('slug' => 'district'),
        'show_in_rest' => true,
    );

    register_taxonomy('district', array('real_estate'), $args);
}
add_action('init', 'create_district_taxonomy');

// Shortcode to display the filter form and results
function real_estate_filter_shortcode() {
    ob_start();
    ?>
    <form id="real-estate-filter">
        <label for="district">District:</label>
        <select name="district" id="district">
            <option value="">All Districts</option>
            <?php
            $districts = get_terms('district', array('hide_empty' => false));
            foreach ($districts as $district) {
                echo '<option value="' . esc_attr($district->slug) . '">' . esc_html($district->name) . '</option>';
            }
            ?>
        </select>

        <label for="floors">Number of Floors:</label>
        <select name="floors" id="floors">
            <option value="">All Floors</option>
            <?php for ($i = 1; $i <= 20; $i++) : ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>
        </select>

        <label for="type">Type of Building:</label>
        <select name="type" id="type">
            <option value="">All Types</option>
            <option value="Brick">Brick</option>
            <option value="Panel">Panel</option>
            <option value="Foam Block">Foam Block</option>
        </select>

        <button type="submit">Filter</button>
        <button type="button" id="clear-filter">Clear Filter</button>
    </form>

    <div id="real-estate-results"></div>

    <script type="text/javascript">
    jQuery(document).ready(function($) {
        function loadRealEstate(page) {
            var filterData = {
                action: 'real_estate_filter',
                district: $('#district').val(),
                floors: $('#floors').val(),
                type: $('#type').val(),
                page: page
            };

            $.ajax({
                url: '<?php echo admin_url("admin-ajax.php"); ?>',
                data: filterData,
                type: 'POST',
                success: function(response) {
                    $('#real-estate-results').html(response);
                }
            });
        }

        // Handle filter submission
        $('#real-estate-filter').submit(function(event) {
            event.preventDefault();
            loadRealEstate(1);
        });

        // Handle pagination click
        $(document).on('click', '.page-numbers', function(event) {
            event.preventDefault();
            var page = $(this).data('page');
            loadRealEstate(page);
        });

        // Handle Clear Filter button click
        $('#clear-filter').click(function() {
            $('#district').val('');
            $('#floors').val('');
            $('#type').val('');
            loadRealEstate(1); // Reload all real estate objects
        });
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('real_estate_filter', 'real_estate_filter_shortcode');

// AJAX handler for filtering real estate objects and pagination
function real_estate_filter_ajax() {
    $paged = (isset($_POST['page'])) ? $_POST['page'] : 1;

    $args = array(
        'post_type' => 'real_estate',
        'posts_per_page' => 5,
        'paged' => $paged,
        'tax_query' => array(),
        'meta_query' => array(),
    );

    // District filtering
    if (!empty($_POST['district'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'district',
            'field'    => 'slug',
            'terms'    => $_POST['district'],
        );
    }

    // Floors filtering
    if (!empty($_POST['floors'])) {
        $args['meta_query'][] = array(
            'key'     => 'number_of_floors',
            'value'   => $_POST['floors'],
            'compare' => '=',
        );
    }

    // Type of building filtering
    if (!empty($_POST['type'])) {
        $args['meta_query'][] = array(
            'key'     => 'type_of_building',
            'value'   => $_POST['type'],
            'compare' => '=',
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $house_name = get_field('house_name');
            $location_coordinates = get_field('location_coordinates');
            $number_of_floors = get_field('number_of_floors');
            $type_of_building = get_field('type_of_building');
            $environmentalism = get_field('environmentalism');
            $building_image = get_field('images');

            echo '<div class="real-estate-item">';
            // Title is now clickable
            echo '<h2><a href="' . get_permalink() . '" style="text-decoration: none;">' . get_the_title() . '</a></h2>';
            if ($building_image) {
                echo '<img src="' . esc_url($building_image['url']) . '" alt="' . esc_attr($building_image['alt']) . '" style="max-width: 100%; height: auto;">';
            }
            echo '<p><strong>House Name:</strong> ' . esc_html($house_name) . '</p>';
            echo '<p><strong>Location Coordinates:</strong> ' . esc_html($location_coordinates) . '</p>';
            echo '<p><strong>Number of Floors:</strong> ' . esc_html($number_of_floors) . '</p>';
            echo '<p><strong>Type of Building:</strong> ' . esc_html($type_of_building) . '</p>';
            echo '<p><strong>Environmentalism:</strong> ' . esc_html($environmentalism) . '</p>';
            echo '</div>';
        }

        // Pagination links with styling
        $total_pages = $query->max_num_pages;
        if ($total_pages > 1) {
            echo '<div class="pagination" style="margin-top: 20px; text-align: center;">';
            for ($i = 1; $i <= $total_pages; $i++) {
                echo '<a href="#" class="page-numbers" data-page="' . $i . '" style="margin: 0 10px; padding: 5px 10px; background: #f0f0f0; border: 1px solid #ccc; color: #333;">' . $i . '</a>';
            }
            echo '</div>';
        }
    } else {
        echo 'No real estate objects found.';
    }

    wp_die();
}
add_action('wp_ajax_real_estate_filter', 'real_estate_filter_ajax');
add_action('wp_ajax_nopriv_real_estate_filter', 'real_estate_filter_ajax');































