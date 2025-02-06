<?php
/**
 * Plugin Name: Mass Article Importer
 * Description: Plugin para importar artigos em massa de um arquivo XLSX.
 * Version: 1.0.0
 * Author: Luiz Reimann
 * Author URI: https://luizreimann.dev
 * Requires PHP: 7.2
 * Requires at least: 5.0
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

// Adiciona o menu ao painel
function mass_article_import_menu() {
    add_menu_page('Importar Artigos', 'Importar Artigos', 'manage_options', 'mass-article-import', 'mass_article_import_page', 'dashicons-calendar-alt');
}
add_action('admin_menu', 'mass_article_import_menu');

// Página de importação
function mass_article_import_page() {
    ?>
    <div class="wrap">
        <h1>Importar Artigos</h1>
        <form method="post" enctype="multipart/form-data">
            <table class="form-table">
                <tr>
                    <th><label for="xlsx_file">Arquivo XLSX</label> <span class="dashicons dashicons-editor-help" title="Ordem das colunas: UUID, Title, Content, Excerpt, Description, Keywords, Focus keyword"></span><br><small>A importação ocorre da segunda até a última linha do arquivo.</small></th>
                    <td><input type="file" name="xlsx_file" required></td>
                </tr>
                <tr>
                    <th><label for="start_date">Data/Hora Inicial</label><br><small>Utilizado para o primeiro artigo importado.</small></th>
                    <td><input type="datetime-local" name="start_date" required></td>
                </tr>
                <tr>
                    <th><label for="interval">Intervalo entre posts</label></th>
                    <td>
                        <select name="interval" required>
                            <option value="1">1 hora</option>
                            <option value="3">3 horas</option>
                            <option value="6">6 horas</option>
                            <option value="12">12 horas</option>
                            <option value="24">1 dia</option>
                            <option value="72">3 dias</option>
                        </select>
                    </td>
                </tr>
            </table>
            <input type="submit" name="import_articles" value="Importar" class="button button-primary">
        </form>
    </div>
    <?php

    if (isset($_POST['import_articles'])) {
        mass_article_import_process();
    }
}

// Processa o upload e importação dos artigos
function mass_article_import_process() {
    if (!isset($_FILES['xlsx_file']['name']) || pathinfo($_FILES['xlsx_file']['name'], PATHINFO_EXTENSION) !== 'xlsx') {
        echo '<div class="error"><p>O arquivo enviado não está no formato XLSX.</p></div>';
        return;
    }

    if (empty($_FILES['xlsx_file']['tmp_name']) || empty($_POST['start_date']) || empty($_POST['interval'])) {
        echo '<div class="error"><p>Todos os campos são obrigatórios.</p></div>';
        return;
    }

    $file_path = $_FILES['xlsx_file']['tmp_name'];
    $spreadsheet = IOFactory::load($file_path);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();
    array_shift($rows);

    $start_date = strtotime($_POST['start_date']);
    $interval = (int)$_POST['interval'] * 3600;
    $post_date = $start_date;

    $imported_count = 0;
    foreach ($rows as $row) {
        $title = sanitize_text_field($row[1]);
        $content = wp_kses_post($row[2]);
        $excerpt = sanitize_text_field($row[3]);
        $description = sanitize_text_field($row[4]);
        $keywords = sanitize_text_field($row[5]);
        $focus_keyword = sanitize_text_field($row[6]);

        $post_data = [
            'post_title'    => $title,
            'post_content'  => $content,
            'post_excerpt'  => $excerpt,
            'post_status'   => 'future',
            'post_type'     => 'post',
            'post_date'     => date('Y-m-d H:i:s', $post_date),
            'meta_input'    => [
                'description' => $description,
                'keywords'    => $keywords,
                'focus_keyword' => $focus_keyword
            ]
        ];

        wp_insert_post($post_data);
        $imported_count++;
        $post_date += $interval;
    }

    echo '<div class="updated"><p>Foram importados ' . $imported_count . ' artigos com sucesso!</p></div>';
}

// Garante que a biblioteca PhpSpreadsheet seja carregada corretamente
function mass_article_import_activate() {
    if (!class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) {
        wp_die('Este plugin requer a biblioteca PhpSpreadsheet. Instale-a via Composer.');
    }
}
register_activation_hook(__FILE__, 'mass_article_import_activate');