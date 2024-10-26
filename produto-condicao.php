<?php
/*
Plugin Name: Condição do Produto para WooCommerce
Description: Adiciona um campo para especificar se o produto é novo, seminovo ou usado.
Version: 1.0
Author: Davy
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Adiciona o campo na aba Inventário do produto no admin
add_action('woocommerce_product_options_general_product_data', 'add_custom_product_field');

function add_custom_product_field() {
    echo '<div class="options_group">';
    
    woocommerce_wp_select( array(
        'id'            => '_product_condition',
        'label'         => __('Condição do Produto', 'woocommerce'),
        'options'       => array(
            'new'       => __('Novo', 'woocommerce'),
            'used'      => __('Usado', 'woocommerce'),
            'semi_new'  => __('Semi-novo', 'woocommerce')
        ),
        'description'   => __('Selecione a condição do produto.', 'woocommerce'),
    ) );
    
    echo '</div>';
}

// Salva o valor do campo ao salvar o produto
add_action('woocommerce_process_product_meta', 'save_custom_product_field');

function save_custom_product_field($post_id) {
    $product_condition = isset($_POST['_product_condition']) ? sanitize_text_field($_POST['_product_condition']) : '';
    update_post_meta($post_id, '_product_condition', $product_condition);
}

// Exibe a condição do produto no front-end
add_action('woocommerce_single_product_summary', 'display_product_condition', 20);

function display_product_condition() {
    global $product;
    $product_condition = get_post_meta($product->get_id(), '_product_condition', true);
    
    if ($product_condition) {

        // Converter a condição para PT-BR
        switch ( $product_condition ) {
            case 'new':
                $product_condition_br = 'Novo';
                break;
            case 'used':
                $product_condition_br = 'Usado';
                break;
            case 'semi_new':
                $product_condition_br = 'Semi Novo'; // Seminovo tratado como usado
                break;
            default:
                $product_condition_br = '';
                break;
        }

        echo '<p class="product-condition"><strong>' . __('Condição:', 'woocommerce') . '</strong> ' . ucfirst($product_condition_br) . '</p>';
    }
}
