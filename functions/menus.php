<?php
function hein_menu($menu_id)
{
    wp_nav_menu(array(
        'theme_location' => $menu_id,
    ));
}

function hein_menu_array($menu_id)
{
    if (($menu_id) && ($locations = get_nav_menu_locations()) && isset($locations[$menu_id])) {
        $menu = get_term($locations[$menu_id], 'nav_menu');
        return wp_get_nav_menu_items($menu->term_id);
    }
}
register_nav_menus(
    array(
        'help' => __('လမ်းညွန်များ', ''),
    )
);

function clean_custom_menu($theme_location)
{
    if (($theme_location) && ($locations = get_nav_menu_locations()) && isset($locations[$theme_location])) {
        $menu = get_term($locations[$theme_location], 'nav_menu');
        $menu_items = wp_get_nav_menu_items($menu->term_id);

        $menu_list  = '<nav>' . "\n";
        $menu_list .= '<ul class="main-nav">' . "\n";

        $count = 0;
        $submenu = false;

        foreach ($menu_items as $menu_item) {

            $link = $menu_item->url;
            $title = $menu_item->title;

            if (!$menu_item->menu_item_parent) {
                $parent_id = $menu_item->ID;

                $menu_list .= '<li class="item">' . "\n";
                $menu_list .= '<a href="' . $link . '" class="title">' . $title . '</a>' . "\n";
            }

            if ($parent_id == $menu_item->menu_item_parent) {

                if (!$submenu) {
                    $submenu = true;
                    $menu_list .= '<ul class="sub-menu">' . "\n";
                }

                $menu_list .= '<li class="item">' . "\n";
                $menu_list .= '<a href="' . $link . '" class="title">' . $title . '</a>' . "\n";
                $menu_list .= '</li>' . "\n";


                if ($menu_items[$count + 1]->menu_item_parent != $parent_id && $submenu) {
                    $menu_list .= '</ul>' . "\n";
                    $submenu = false;
                }
            }

            if ($menu_items[$count + 1]->menu_item_parent != $parent_id) {
                $menu_list .= '</li>' . "\n";
                $submenu = false;
            }

            $count++;
        }

        $menu_list .= '</ul>' . "\n";
        $menu_list .= '</nav>' . "\n";
    } else {
        $menu_list = '<!-- no menu defined in location "' . $theme_location . '" -->';
    }
    echo $menu_list;
}
