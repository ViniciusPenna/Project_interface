<?PHP
  $authChk = true;
  $errors = [];
  require('app-lib.php');
  isset($_REQUEST['sid'])? $sid = $_REQUEST['sid'] : $sid = "";
  if(!$sid) {
    isset($_POST['sid'])? $sid = $_POST['sid'] : $sid = "";
  }
  isset($_REQUEST['a'])? $action = $_REQUEST['a'] : $action = "";
  if(!$action) {
    isset($_POST['a'])? $action = $_POST['a'] : $action = "";
  }
  isset($_POST['txtSearch'])? $txtSearch = $_POST['txtSearch'] : $txtSearch = "";
  if(!$txtSearch) {
    isset($_REQUEST['txtSearch'])? $txtSearch = $_REQUEST['txtSearch'] : $txtSearch = "";
  }
  if($action == "delRec") {
    $query =
      "UPDATE lpa_users SET
         lpa_user_status = 'D'
       WHERE
         lpa_user_ID = '$sid' LIMIT 1
      ";
    openDB();
    $result = $db->query($query);
    if($db->error) {
      printf("Errormessage: %s\n", $db->error);
      exit;
    } else {
      header("Location: users.php?a=recDel&txtSearch=$txtSearch");
      exit;
    }
  }

  isset($_POST['txtUserID'])? $userID = $_POST['txtUserID'] : $userID = gen_ID();
  isset($_POST['txtUserName'])? $userName = $_POST['txtUserName'] : $userName = "";
  isset($_POST['txtUserPassword'])? $userPassword = $_POST['txtUserPassword'] : $userPassword = "";
  isset($_POST['txtUserFirstName'])? $userFirstName = $_POST['txtUserFirstName'] : $userFirstName = "";
  isset($_POST['txtUserLastname'])? $userLastname = $_POST['txtUserLastname'] : $userLastname = "";
  isset($_POST['txtUserGroup'])? $userGroup = $_POST['txtUserGroup'] : $userGroup = "";
  isset($_POST['txtStatus'])? $userStatus = $_POST['txtStatus'] : $userStatus = "";
  $mode = "insertRec";
  $hashedPassword = hash("md5",$userPassword);
  if($action == "updateRec") {
	 if (empty($userName)) { array_push($errors, "Username is required"); }
    $query =
      "UPDATE lpa_users SET
         lpa_user_ID = '$userID',
         lpa_user_username = '$userName',
         lpa_user_password = '$hashedPassword',
         lpa_user_firstname = '$userFirstName',
         lpa_user_lastname = '$userLastname',
		 lpa_user_group = '$userGroup',
         lpa_user_status = '$userStatus'
       WHERE
         lpa_user_ID = '$sid' LIMIT 1
      ";
     openDB();
     $result = $db->query($query);
     if($db->error) {
	    reg_log($userName,"error to edit user");
       printf("Errormessage: %s\n", $db->error);
       exit;
     } else {
		  reg_log($userName," user edited");
         header("Location: users.php?a=recUpdate&txtSearch=$txtSearch");
       exit;
     }
  }
  if($action == "insertRec") {
	  if (empty($userName)) { array_push($errors, "Username is required"); }
    $query =
      "INSERT INTO lpa_users (
         lpa_user_ID,
         lpa_user_username,
		 lpa_user_password,
         lpa_user_firstname,
		 lpa_user_lastname,
		 lpa_user_group,
         lpa_user_status
       ) VALUES (
         '$userID',
         '$userName',
         '$hashedPassword',
         '$userFirstName',
	     '$userLastname',
         '$userGroup',
		 '$userStatus'
       )
      ";
    openDB();
    $result = $db->query($query);
    if($db->error) {
      printf("Errormessage: %s\n", $db->error);
      exit;
    } else {
      header("Location: users.php?a=recInsert&txtSearch=".$userID);
      exit;
    }
  }

  if($action == "Edit") {
	if (empty($userName)) { array_push($errors, "Username is required"); }
	//reg_log($sid,"debug Sid useraddedit");
    $query = "SELECT * FROM lpa_users WHERE lpa_user_ID = '$sid' LIMIT 1";
	//reg_log($query,"debug query useraddedit");
    $result = $db->query($query);
    $row_cnt = $result->num_rows;
    $row = $result->fetch_assoc();
    $userID     = $row['lpa_user_ID'];
    $userName   = $row['lpa_user_username'];
    $userPassword   = $row['lpa_user_password'];
    $userFirstName = $row['lpa_user_firstname'];
    $userLastname  = $row['lpa_user_lastname'];
	$userGroup = $row['lpa_user_group'];
    $userStatus = $row['lpa_user_status'];
    $mode = "updateRec";
  }
  build_header($displayName);
  build_navBlock();
  $fieldSpacer = "5px";
?>

  <div id="content">
    <div class="PageTitle">Users Record (<?PHP echo $action; ?>)</div>
    <form name="frmUserRec" id="frmUserRec" method="post" action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
      <div>
        <input name="txtUserID" id="txtUserID" placeholder="User ID" value="<?PHP echo $userID; ?>" style="width: 100px;" title="User ID">
      </div>
	  <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <input name="txtUserName" id="txtUserName" placeholder="User Name" value="<?PHP echo $userName; ?>" style="width: 200px;"  title="User Name">
      </div>
	  <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
		<input type="password" name="txtUserPassword" id="txtUserPassword" placeholder="Password" value="<?PHP echo $userPassword; ?>" style="width: 200px;height: 20px"  title="Password">
      </div> 
	  <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <input name="txtUserFirstName" id="txtUserFirstName" placeholder="First Name" value="<?PHP echo $userFirstName; ?>" style="width: 400px;text-align: left"  title="First Name">
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <input name="txtUserLastname" id="txtUserLastname" placeholder="Last Name" value="<?PHP echo $userLastname; ?>" style="width: 400px;text-align: left"  title="Last Name">
      </div>
	  	  
	  <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <div>User Group:</div>
        <input name="txtUserGroup" id="txtUserGroupAdministrator" type="radio" value="administrator">
          <label for="txtUserGroupAdministrator">Administrator</label>
        <input name="txtUserGroup" id="txtUserGroupUser" type="radio" value="user">
          <label for="txtUserGroupUser">User</label>
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <div>User Status:</div>
        <input name="txtStatus" id="txtUserStatusActive" type="radio" value="1">
          <label for="txtUserStatusActive">Active</label>
        <input name="txtStatus" id="txtUserStatusInactive" type="radio" value="0">
          <label for="txtUserStatusInactive">Inactive</label>
      </div>
      <input name="a" id="a" value="<?PHP echo $mode; ?>" type="hidden">
      <input name="sid" id="sid" value="<?PHP echo $sid; ?>" type="hidden">
      <input name="txtSearch" id="txtSearch" value="<?PHP echo $txtSearch; ?>" type="hidden">
    </form>
    <div class="optBar">
      <button type="button" id="btnUserSave">Save</button>
      <button type="button" onclick="navMan('users.php')">Close</button>
      <?PHP if($action == "Edit") { ?>
      <button type="button" onclick="delRec('<?PHP echo $sid; ?>')" style="color: darkred; margin-left: 20px">DELETE</button>
      <?PHP } ?>
    </div>
  </div>
  <script>
    var userRecStatus = "<?PHP echo $userStatus; ?>";
    if(userRecStatus == "a") {
      $('#txtUserStatusActive').prop('checked', true);
    } else {
      $('#txtUserStatusInactive').prop('checked', true);
    }
	var userRecGroup = "<?PHP echo $userGroup; ?>";
    if(userRecGroup == "a") {
      $('#txtUserStatusActive').prop('checked', true);
    } else {
      $('#txtUserStatusInactive').prop('checked', true);
    }
    $("#btnUserSave").click(function(){
        $("#frmUserRec").submit();
    });
    function delRec(ID) {
      navMan("usersadedit.php?sid=" + ID + "&a=delRec");
    }
    setTimeout(function(){
      $("#txtUserName").focus();
    },1);
  </script>
<?PHP
build_footer();
?>