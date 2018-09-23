<?PHP
  $authChk = true;
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
      "UPDATE lpa_invoices SET
         lpa_inv_status = 'D'
       WHERE
         lpa_inv_no = '$sid' LIMIT 1
      ";
    openDB();
    $result = $db->query($query);
    if($db->error) {
      printf("Errormessage: %s\n", $db->error);
      exit;
    } else {
      header("Location: sales.php?a=recDel&txtSearch=$txtSearch");
      exit;
    }
  }

  isset($_POST['txtSaleID'])? $saleID = $_POST['txtSaleID'] : $saleID = gen_ID();
  isset($_POST['txtInvoiceName'])? $saleName = $_POST['txtInvoiceName'] : $saleName = "";
  isset($_POST['txtInvoiceAddress'])? $saleAddress = $_POST['txtInvoiceAddress'] : $saleAddress = "";
  isset($_POST['txtInvoiceDate'])? $saleDate = $_POST['txtInvoiceDate'] : $saleDate = "0000-00-00";
  isset($_POST['txtInvoiceAmount'])? $saleAmount = $_POST['txtInvoiceAmount'] : $saleAmount = "0.00";
  isset($_POST['txtInvoiceStatus'])? $saleStatus = $_POST['txtInvoiceStatus'] : $saleStatus = "";
  $mode = "insertRec";
  if($action == "updateRec") {
    $query =
      "UPDATE lpa_invoices SET
         lpa_inv_no = '$saleID',
         lpa_inv_client_name = '$saleName',
         lpa_inv_client_address = '$saleAddress',
         lpa_inv_date = '$saleDate',
         lpa_inv_amount = '$saleAmount',
         lpa_inv_status = '$saleStatus'
       WHERE
         lpa_inv_no = '$sid' LIMIT 1
      ";
     openDB();
     $result = $db->query($query);
     if($db->error) {
       printf("Errormessage: %s\n", $db->error);
       exit;
     } else {
         header("Location: sales.php?a=recUpdate&txtSearch=$txtSearch");
       exit;
     }
  }
  if($action == "insertRec") {
    $query =
      "INSERT INTO lpa_invoices (
         lpa_inv_no,
         lpa_inv_client_name,
         lpa_inv_client_address,
         lpa_inv_date,
		 lpa_inv_client_ID,
         lpa_inv_amount,
         lpa_inv_status
       ) VALUES (
         '$saleID',
         '$saleName',
         '$saleAddress',
         '$saleDate',
		 '$saleName',
         '$saleAmount',
         '$saleStatus'
       )
      ";
    openDB();
    $result = $db->query($query);
    if($db->error) {
      printf("Errormessage: %s\n", $db->error);
      exit;
    } else {
      header("Location: sales.php?a=recInsert&txtSearch=".$saleID);
      exit;
    }
  }

  if($action == "Edit") {
    $query = "SELECT * FROM lpa_invoices WHERE lpa_inv_no = '$sid' LIMIT 1";
    $result = $db->query($query);
    $row_cnt = $result->num_rows;
    $row = $result->fetch_assoc();
    $saleID     = $row['lpa_inv_no'];
    $saleName   = $row['lpa_inv_client_name'];
    $saleAddress   = $row['lpa_inv_client_address'];
    $saleDate = $row['lpa_inv_date'];
    $saleAmount  = $row['lpa_inv_amount'];
    $saleStatus = $row['lpa_inv_status'];
    $mode = "updateRec";
  }
  build_header($displayName);
  build_navBlock();
  $fieldSpacer = "5px";
?>

  <div id="content">
    <div class="PageTitle">Invoice Record Management (<?PHP echo $action; ?>)</div>
    <form name="frmSaleRec" id="frmSaleRec" method="post" action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
      <div>
        <input name="txtSaleID" id="txtSaleID" placeholder="Client ID" value="<?PHP echo $saleID; ?>" style="width: 100px;" title="Client ID">
      </div>
	  <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <input name="txtInvoiceDate" id="txtInvoiceDate" placeholder="Date" value="<?PHP echo $saleDate; ?>" style="width: 90px;text-align: right"  title="Date">
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <input name="txtInvoiceName" id="txtInvoiceName" placeholder="Client Name" value="<?PHP echo $saleName; ?>" style="width: 400px;"  title="Client Name">
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <textarea name="txtInvoiceAddress" id="txtInvoiceAddress" placeholder="Client Address" style="width: 400px;height: 80px"  title="Client Address"><?PHP echo $saleAddress; ?></textarea>
      </div> 
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <input name="txtInvoiceAmount" id="txtInvoiceAmount" placeholder="Amount" value="<?PHP echo $saleAmount; ?>" style="width: 90px;text-align: right"  title="Amount">
      </div>
      <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
        <div>Client Status:</div>
        <input name="txtInvoiceStatus" id="txtsaleStatusActive" type="radio" value="a">
          <label for="txtsaleStatusActive">Active</label>
        <input name="txtInvoiceStatus" id="txtsaleStatusInactive" type="radio" value="i">
          <label for="txtsaleStatusInactive">Inactive</label>
      </div>
      <input name="a" id="a" value="<?PHP echo $mode; ?>" type="hidden">
      <input name="sid" id="sid" value="<?PHP echo $sid; ?>" type="hidden">
      <input name="txtSearch" id="txtSearch" value="<?PHP echo $txtSearch; ?>" type="hidden">
    </form>
    <div class="optBar">
      <button type="button" id="btnSaleSave">Save</button>
      <button type="button" onclick="navMan('sales.php')">Close</button>
      <?PHP if($action == "Edit") { ?>
      <button type="button" onclick="delRec('<?PHP echo $sid; ?>')" style="color: darkred; margin-left: 20px">DELETE</button>
      <?PHP } ?>
    </div>
  </div>
  <script>
    var stockRecStatus = "<?PHP echo $saleStatus; ?>";
    if(stockRecStatus == "a") {
      $('#txtsaleStatusActive').prop('checked', true);
    } else {
      $('#txtsaleStatusInactive').prop('checked', true);
    }
    $("#btnSaleSave").click(function(){
        $("#frmSaleRec").submit();
    });
    function delRec(ID) {
      navMan("Salesaddedit.php?sid=" + ID + "&a=delRec");
    }
    setTimeout(function(){
      $("#txtInvoiceName").focus();
    },1);
  </script>
<?PHP
build_footer();