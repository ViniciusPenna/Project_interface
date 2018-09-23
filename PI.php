<?PHP
  require('app-lib.php');
  $authChk = true;
  $msg = null;
  
  isset($_REQUEST['a'])? $action = $_REQUEST['a'] : $action = "";
  if(!$action) {
    isset($_POST['a'])? $action = $_POST['a'] : $action = "";
  }
 $userID = $_SESSION['authUser'];
 openDB();
 $query =
   "SELECT
       *
    FROM
       lpa_users
    WHERE
       lpa_user_ID LIKE '%$userID%' LIMIT 1";
 
$result = $db->query($query);
$row_cnt = $result->num_rows;
$row = $result->fetch_assoc();
$userName   = $row['lpa_user_username'];
$userPassword   = $row['lpa_user_password'];
$userFirstName = $row['lpa_user_firstname'];
$userLastname  = $row['lpa_user_lastname'];
$userGroup = $row['lpa_user_group'];
$userStatus = $row['lpa_user_status'];
$mode = "updateRec";

if($action == "updateRec") {
isset($_POST['txtUserName'])? $userName = $_POST['txtUserName'] : $msg = "user name empty";
isset($_POST['txtUserPassword'])? $userPassword = $_POST['txtUserPassword'] : $msg = "password empty";
isset($_POST['txtUserFirstName'])? $userFirstName = $_POST['txtUserFirstName'] : $msg = "First name empty";
isset($_POST['txtUserLastname'])? $userLastname = $_POST['txtUserLastname'] : $msg = "Last name empty";
if (empty($userName)) { array_push($msg, "Username is required"); }
$query =
 "UPDATE lpa_users SET
    lpa_user_ID = '$userID',
    lpa_user_username = '$userName',
    lpa_user_password = '$userPassword',
    lpa_user_firstname = '$userFirstName',
    lpa_user_lastname = '$userLastname',
lpa_user_group = '$userGroup',
    lpa_user_status = '$userStatus'
  WHERE
    lpa_user_ID = '$userID' LIMIT 1
 ";
openDB();
$result = $db->query($query);
if($db->error) {
reg_log($userName,"error to edit profile");
  printf("Errormessage: %s\n", $db->error);
  exit;
} else {
reg_log($userName,"profile edited");
    header("Location: index.php?a=recUpdate&txtSearch=$txtSearch");
  exit;
}
}


  build_header($displayName);
  build_navBlock();
  $fieldSpacer = "5px";
?>
<div id="content">
    <div class="PageTitle">Edit Personal Information (<?PHP echo $action; ?>)</div>
    <form name="frmUserRec" id="frmUserRec" method="post" action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
    </div>
		  <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <input name="txtUserName" id="txtUserName" placeholder="User Name" value="<?PHP echo $userName; ?>" style="width: 200px;"  title="User Name">
      </div>
	  <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
		<input type="password" name="txtUserPassword" id="txtUserPassword" placeholder="Password" style="width: 200px;height: 20px" value="<?PHP echo $userPassword; ?>" title="Password">
      </div> 
     	  <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <input name="txtUserFirstName" id="txtUserFirstName" placeholder="First Name" value="<?PHP echo $userFirstName; ?>" style="width: 400px;text-align: left"  title="First Name">
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <input name="txtUserLastname" id="txtUserLastname" placeholder="Last Name" value="<?PHP echo $userLastname; ?>" style="width: 400px;text-align: left"  title="Last Name">
      		</div>
	    <input name="a" id="a" value="<?PHP echo $mode;?>" type="hidden"> 
     
    </form>
    <div class="optBar">
      <button type="button" id="btnUserSave">Save</button>
      <button type="button" onclick="navMan('index.php')">Close</button>
    </div>
  </div>
  <script>
  var msg = "<?PHP echo $msg; ?>";
	if(msg) {
    alert(msg);
	}       
    $("#btnUserSave").click(function(){
        $("#frmUserRec").submit();
    });
    setTimeout(function(){
      $("#txtUserName").focus();
    },1);
  </script>
<?PHP
build_footer();
?>