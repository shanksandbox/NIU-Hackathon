<footer class="app-footer">
      <div class="row">
        <div class="col-xs-12">
          <div class="footer-copyright">Copyright © <?php echo date('Y');?> <a href="" target="_blank">Team C.O.D.E</a>. All Rights Reserved.</div>
        </div>
      </div>
    </footer>
  </div>
</div>
<script type="text/javascript" src="assets/js/vendor.js"></script> 
<script type="text/javascript" src="assets/js/app.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="assets/js/notify.min.js"></script>

<script type="text/javascript" src="assets/duDialog-master/duDialog.min.js?v=<?=date('dmYhis')?>"></script>

<script>
  $( function() {
    $( ".datepicker" ).datepicker();
  } );
</script>

<?php if(isset($_SESSION['msg'])){?>

<script type="text/javascript">
  $('.notifyjs-corner').empty();
  $.notify(
    '<?php echo $client_lang[$_SESSION["msg"]];?>',
    { position:"top center",className: '<?=isset($_SESSION["class"]) ? $_SESSION["class"] : "success" ?>'}
  );
</script> 
<?php
  unset($_SESSION['msg']);
  unset($_SESSION['class']);	 
  } 
?>

</body>
</html>