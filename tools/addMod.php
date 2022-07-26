<?php
//to use the tool the account needs to be admin
//to make an account admin go to the accounts table and change isAdmin from 0 to 1

include "../incl/lib/connection.php";
require "../incl/lib/generatePass.php";
require_once "../incl/lib/exploitPatch.php";
$ep = new exploitPatch();
$accid = $ep->remove($_POST["accid"]);
$role = $ep->remove($_POST["role"]);
$TuserName = $ep->remove($_POST["TuserName"]);
$query4 = $db->prepare("SELECT isAdmin FROM accounts WHERE userName=:userName");
        $query4->execute([':userName' => $userName]);
		$query4 = $query4->fetchColumn();
		if($query4 != 1) {
			echo "not admin account";
			exit();
		}

		 if(empty($TuserName) && empty($accid)) {
			 echo "Target Account ID and Username empty";
			 exit();
		 }

		 if(!empty($accid) && !empty($TuserName)) {
		echo "Submit only Account ID or Usrename, not both";
		exit();
		 }
			if(empty($accid)){



        $query3 = $db->prepare("SELECT accountID FROM accounts WHERE userName=:userName");
        $query3->execute([':userName' => $TuserName]);
		if($query3->rowCount() == 0) {
		echo "invalid target username"; 
		exit();
		}
		$accid = $query3->fetchColumn();

			}


		$query2 = $db->prepare("INSERT INTO roleassign (assignID, roleID, accountID) 
		VALUES (NULL, :roleID, :accountID)");






	$querye = $db->query("SELECT assignID FROM `roleassign` WHERE accountID = $accid");
	$ID = $querye->fetchColumn();
	$rows = $querye->rowCount();


	if($rows == 1) {


			$query1 = $db->query("UPDATE `roleassign` SET `roleID` = $role WHERE `roleassign`.`assignID` = $ID");

			echo "probably worked";

	}else{


				$query2->execute([':roleID' => $role, ':accountID' => $accid]);

			echo "probably worked";

	}
	echo "<br> Target Account <br> Use username <b>OR</b> Account ID, leave the other one empty <br>";

	echo '<form action="addMod.php" method="post">
	Account ID: <input type"number" name="accid">
	<br>
	Username: <input type"text" name="TuserName">
	<br>';

echo "<br> Role <br>";
echo "0 = No Mod <br> 1 = Normal Mod <br> 2 = Elder Mod <br>";

echo '<form action="addMod.php" method="post">
	Role ID: <input type"number" name="role">
	<br>
	<br>
	<input type="Submit" value="Submit">
	</form>';
?>
