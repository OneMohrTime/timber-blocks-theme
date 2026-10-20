<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<footer class="l-footer">
    <div class="l-wrap l-footer__inner">
        <p>© <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
