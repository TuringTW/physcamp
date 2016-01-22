<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="KEVIN">

  <?php echo css_url("/bootstrap.css") ?>
  <?php echo css_url("/signin.css") ?>
  <?php echo css_url("/bootstrap-fileupload.css") ?>
	<title>物理營後台系統</title>
</head>



<body>

    <div class="container">

      <form class="form-signin" role="form" method="POST" action="./login">
        <h2 class="form-signin-heading">物理營後台系統</h2>

        <input type="text" name="username" class="form-control" placeholder="帳號"  autofocus="">
        <input type="password" name="password"  class="form-control" placeholder="密碼" >
<!--         <label class="checkbox">
          <input type="checkbox" value="remember-me"> Remember me
        </label> -->
        <h5><?php echo validation_errors(); ?></h5>
        <button class="btn btn-lg btn-primary btn-block" name="btnlogin" value="login" type="submit">登入</button>
      </form>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  

</body>