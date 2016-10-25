<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  use OSC\OM\OSCOM;
?>

</div>

<?php require($oscTemplate->getFile('footer.php')); ?>

<script>
$(function() {
  var url = document.location.toString();

  if (url.match('#')) {
    if ($('.nav-tabs a[data-target="#' + url.split('#')[1] + '"]').length === 1) {
      $('.nav-tabs a[data-target="#' + url.split('#')[1] + '"]').tab('show');
    }
  }
});
</script>

<script src="<?= OSCOM::link('Shop/ext/bootstrap/js/bootstrap.min.js', '', 'AUTO', false); ?>"></script>

<script src="<?= OSCOM::link('Shop/ext/smartmenus/jquery.smartmenus.min.js', '', 'AUTO', false); ?>"></script>
<script src="<?= OSCOM::link('Shop/ext/smartmenus/jquery.smartmenus.bootstrap.min.js', '', 'AUTO', false); ?>"></script>

<script src="<?= OSCOM::link('Shop/ext/mustache/mustache.min.js', '', 'AUTO', false); ?>"></script>

<script src="<?= OSCOM::link('Shop/ext/sortable/sortable.min.js', '', 'AUTO', false); ?>"></script>

<script src="<?= OSCOM::link('Shop/ext/chartist/chartist.min.js', '', 'AUTO', false); ?>"></script>

</body>
</html>
