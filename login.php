<?PHP 
  require('app-lib.php');
  isset($_POST['a'])? $action = $_POST['a'] : $action = "";
  reg_log('',"customer accessing Login Page");
  $msg = null;
  if($action == "doLogin") {
    $chkLogin = false;
    isset($_POST['fldUsername'])?
      $uName = $_POST['fldUsername'] : $uName = "";
	isset($_POST['fldPassword'])?
      $uPassword = $_POST['fldPassword'] : $uPassword = "";
	
	$hashedPassword = hash("md5",$uPassword);
    openDB();
    $query =
      "
      SELECT
        lpa_user_ID,
        lpa_user_username,
        lpa_user_password,
		lpa_user_group
      FROM
        lpa_users
      WHERE
        lpa_user_username = '$uName'
      AND
        lpa_user_password = '$hashedPassword'
      LIMIT 1
      ";
    $result = $db->query($query);
    $row = $result->fetch_assoc();
	if($row['lpa_user_username'] == $uName) {
      if($row['lpa_user_password'] == $hashedPassword) {
        $_SESSION['authUser'] = $row['lpa_user_ID'];
		$_SESSION['isAdmin'] = (($row['lpa_user_group']=="administrator")?true:false);
		reg_log($uName,"Successful login");
		if(!empty($_SESSION['authUser'])){
			header("Location: index.php");
			exit;
		}else {
			$msg = "Login failed! Please try again.";
			reg_log($uName,$msg);
			}
      }
    }
	openDB();
    $query =
      "
      SELECT
        lpa_user_ID,
        lpa_user_username,
        lpa_user_password,
		lpa_user_group
      FROM
        lpa_users
      WHERE
        lpa_user_username = '$uName'
      AND
        lpa_user_password = '$uPassword'
      LIMIT 1
      ";
    $result = $db->query($query);
    $row = $result->fetch_assoc();
	if($row['lpa_user_username'] == $uName) {
      if($row['lpa_user_password'] == $uPassword) {
        $_SESSION['authUser'] = $row['lpa_user_ID'];
		$_SESSION['isAdmin'] = (($row['lpa_user_group']=="administrator")?true:false);
		reg_log($uName,"Successful login");
		if(!empty($_SESSION['authUser'])){
			header("Location: index.php");
			exit;
		}else {
			$msg = "Login failed! Please try again.";
			reg_log($uName,$msg);
			}
      }
    }
	if($chkLogin == false) {
      $msg = "Login failed! Please try again.";
	  reg_log($uName,$msg);
    }
}
 build_header();
?>
  <div id="contentLogin">
    <form name="frmLogin" id="frmLogin" method="post" action="login.php">
      <div class="titleBar">User Login</div>
      <div id="loginFrame">
        <div class="msgTitle">Please supply your login details:</div>
        <div>Username:</div>
        <input type="text" name="fldUsername" id="fldUsername">
        <div>Password:</div>
        <input type="password" name="fldPassword" id="fldPassword">
        <div class="buttonBar">
          <button type="button" onclick="do_login()">Login</button>
		  <button type="button" id="btnAddRec">Register</button>
        </div>	  
      </div>
      <input type="hidden" name="a" value="doLogin">
    </form>
 </div>
 <script>
 var msg = "<?PHP echo $msg; ?>";
  if(msg) {
    alert(msg);
  }
 function loadRegItem(ID,MODE) {
	window.location = "reg.php?sid=" +
	ID + "&a=" + MODE ;
	}
	$("#btnAddRec").click(function() {
	loadRegItem("","Add");
	});
	
  $( "#contentLogin").center().cs_draggable({
      handle : ".titleBar",
      containment : "window"
    });

  $("#frmLogin").keypress(function(e) {
    if(e.which == 13) {
      $(this).submit();
    }
  });

</script>
<?PHP
build_footer();
?>