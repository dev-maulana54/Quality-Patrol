<!-- General JS Scripts -->
<script src="<?= base_url() ?>template/node_modules/jquery/dist/jquery.js"></script>
<script src="<?= base_url() ?>template/node_modules/popper.js/dist/umd/popper.js"></script>
<script src="<?= base_url() ?>template/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?= base_url() ?>template/node_modules/jquery.nicescroll/dist/jquery.nicescroll.min.js"></script>
<script src="<?= base_url() ?>template/node_modules/moment/moment.js"></script>
<script src="<?= base_url() ?>template/assets/js/stisla.js"></script>


<!-- Template JS File -->
<script src="<?= base_url() ?>assets/js/scripts.js"></script>
<script src="<?= base_url() ?>assets/js/custom.js"></script>

<!-- JS Libraries -->
<?php
if (isset($jsLibrariesLocation)) {
    foreach ($jsLibrariesLocation as $value) {
?>
        <script src="<?= base_url() . "template/" . $value ?>"></script>
<?php
    }
}
?>

<?php
if (isset($jsLibrariesLocationOuter)) {
    foreach ($jsLibrariesLocationOuter as $value) {
?>
        <script src="<?= base_url() . $value ?>"></script>
<?php
    }
}
?>

</body>

</html>