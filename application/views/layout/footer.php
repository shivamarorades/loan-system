</div> <!-- end container -->


<?php if(!empty($this->session->userdata("uid"))){
    ?>
<!-- Footer -->
<footer class="bg-dark text-white  text-center py-3 mt-5">
    &copy; <?php echo date('Y'); ?> LoanSys. All rights reserved.
</footer>
    <?php
}
?>
<script src="<?= base_url("assets/js/bootstrap.bundle.min.js"); ?>"></script>
<script src="<?= base_url("assets/js/jquery.min.js"); ?>"></script>
<script src="<?= base_url("assets/js/user_script.js"); ?>"></script>
</script>
</body>
</html>
