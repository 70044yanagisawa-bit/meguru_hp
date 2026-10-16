  <footer class="footer">
    <p class="footer__big" aria-hidden="true"><?php echo esc_html( meguru_brand( 'meguru_footer_big' ) ); ?></p>
    <div class="footer__row">
      <p><?php echo esc_html( meguru_brand( 'meguru_footer' ) ); ?></p>
<?php if ( meguru_brand( 'meguru_link_text' ) ) : ?>
      <a href="<?php echo esc_url( meguru_main_url() ); ?>" target="_blank" rel="noopener"><?php echo esc_html( meguru_brand( 'meguru_link_text' ) ); ?>へ</a>
<?php endif; ?>
      <p><small>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( meguru_brand( 'meguru_copyright' ) ); ?></small></p>
    </div>
  </footer>

<?php wp_footer(); ?>
</body>
</html>
