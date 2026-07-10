<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/init.php';
require_once dirname(__DIR__) . '/includes/cms-contact.php';

$current_page = 'contact';
$product = 'freelance';
$cms_t = cms_contact_texts($product, $lang);
$cms_alert = '';
$cms_alert_type = '';
$cms_values = ['name' => '', 'email' => '', 'phone' => '', 'subject' => '', 'message' => ''];

require_once __DIR__ . '/includes/integrations.php';
$cms_recaptcha_site_key = fl_recaptcha_enabled() ? fl_recaptcha_site_key() : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (fl_recaptcha_enabled() && !fl_verify_recaptcha($_POST['g-recaptcha-response'] ?? '')) {
        $result = ['alert' => $cms_t['error_captcha'], 'alert_type' => 'error', 'values' => [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'subject' => trim($_POST['subject'] ?? ''),
            'message' => trim($_POST['message'] ?? ''),
        ]];
    } else {
        $result = cms_contact_handle_post($product, $lang, 'https://bilohash.com/freelance/contact.php', true);
    }
    $cms_alert = $result['alert'];
    $cms_alert_type = $result['alert_type'];
    $cms_values = $result['values'];
}

$cms_csrf = cms_contact_ensure_csrf();
$page_title = $cms_t['page_title'];
$page_desc  = $cms_t['meta_description'];
$canonical  = $site_url . '/contact.php' . ($lang !== 'no' ? '?lang=' . $lang : '');
$canon_abs  = fl_absolute_url($canonical);
$seo_schemas = [
    fl_seo_organization(),
    fl_seo_webpage($canon_abs, $page_title, $page_desc),
    fl_seo_breadcrumbs([
        ['name' => $t['breadcrumb_home'], 'url' => fl_absolute_url(fl_url('index.php'))],
        ['name' => $cms_t['h1'], 'url' => $canon_abs],
    ]),
    fl_seo_professional_service(),
    fl_seo_software_app($canon_abs, $page_desc),
];

$cms_prefix = 'fl';
$cms_action = fl_url('contact.php') . ($lang !== 'no' ? '?lang=' . urlencode($lang) : '');
$body_class = 'fl-contact-page';

require __DIR__ . '/includes/header.php';
require dirname(__DIR__) . '/includes/cms-contact-form.php';
require __DIR__ . '/includes/footer.php';