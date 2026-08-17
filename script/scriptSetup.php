<?php
	require_once("../myclass_m.php");
	$cls=new myclass_m();
	$cls->database_con();

	echo $requestID=$_REQUEST["requestID"];

	if($requestID=="loadEntryMode"){
		$schoolID=$cls->data($_REQUEST['schoolID']);
		$type=$cls->data($_REQUEST['type']);
		if($type=='App') $schoolID=$cls->getRecord('schoolid', 'settingstb', 'id', $schoolID);

		echo '<label>Mode of Entry *</label>
		<select class="form-control" id="mode_of_entry" name="mode_of_entry">';
			$cls->populate_select_withID("id", "entrymode", "entrymodetb", " WHERE schoolid='{$schoolID}' and status='Active' ORDER BY entrymode");
		echo '</select>'; //select2
	}

	if($requestID=="loadCourseRegCode"){
		$prog=$cls->data($_REQUEST['programmeID']);
		$session=$cls->data($_REQUEST['sessionID']);
		$level=$cls->data($_REQUEST['level']);
		
		$sq="SELECT distinct (coursecode), coursetitle FROM coursesregistered WHERE session='{$session}' AND programmeid='{$prog}' AND level='{$level}'";
		$q=mysqli_query($CONNECTOR, $sq);
		
		echo '<label>Course Code *</label>
		<select class="form-control" id="code" name="code">
		<option value="">---...---</option>';
		while($r=mysqli_fetch_array($q, 3)){
		    echo "<option value='".$r['coursecode']."'>".$r['coursecode'].": ".$r['coursetitle']."</option>";
		}
		echo '</select>';
	}

	if($requestID=="loadCourseLevel"){
		$schoolID=$cls->data($_REQUEST['schoolID']);
		echo '<label>Level *</label>
		<select class="form-control" id="level" name="level" onchange="sendRequest(\'loadCourseRegCode\', $(\'#programmeID\').val(), $(\'#sessionID\').val(), $(\'#level\').val() )">';
			$cls->populate_select_withID("level", "descr", "leveltb", " WHERE schoolid='{$schoolID}' AND status='Active'  ORDER BY descr");
		echo '</select>'; 
	}

     if($requestID=='loadCoursebySession'){
	$session=$_REQUEST['session'];
	$role_value=$_REQUEST['role_v'];	
	$sql="SELECT DISTINCT coursecode FROM coursesregistered WHERE session='$session' ORDER BY coursecode";  //control this based on role later
	
	$res_c=mysqli_query($CONNECTOR, $sql);
	echo "<select class='form-control' name='code' id='code'><option selected value=''>---...---</option>";
	while($rs_c=mysqli_fetch_array($res_c))
	 {
		 $code=$rs_c['coursecode'];
		 echo "<option value='$code'>$code</option>";
	 }//end of while
	 echo "</select>";
     } //end if

     if($requestID=="loadSession" || $requestID=="loadSessionAll"){
	$schoolID=$cls->data($_REQUEST['schoolID']);
	$sessionType=$cls->data($_REQUEST['sessionType']);

	echo '<label>Session *</label>
	<select class="form-control" id="sessionID" name="sessionID">';
	if($requestID=="loadSessionAll")
		if($sessionType=='') $cls->populate_select_withID("id", "sessionname", "sessiontb", " WHERE schoolid='{$schoolID}' ORDER BY sessionname");
		else $cls->populate_select_withID("id", "sessionname", "sessiontb", " WHERE schoolid='{$schoolID}' AND type='{$sessionType}' ORDER BY sessionname");
	else
		if($sessionType=='') $cls->populate_select_withID("id", "sessionname", "sessiontb", " WHERE schoolid='{$schoolID}' AND status='Active'  ORDER BY sessionname");
		else $cls->populate_select_withID("id", "sessionname", "sessiontb", " WHERE schoolid='{$schoolID}' AND status='Active' AND type='{$sessionType}' ORDER BY sessionname");
	echo '</select>'; //select2
}

	if($requestID=="loadLevel"){
		$schoolID=$cls->data($_REQUEST['schoolID']);
		$programmeID=$cls->data($_REQUEST['programmeID']);
          $award=$cls->getRecord( 'award', 'programmetb', 'programmeid', $programmeID );

          echo '<label>Level *</label>
		<select class="form-control" id="level" name="level">';
			if($programmeID!='')$cls->populate_select_withID("level", "descr", "leveltb", " WHERE schoolid='{$schoolID}' AND status='Active' AND level LIKE '{$award}%'  ORDER BY descr");
          
			else $cls->populate_select_withID("level", "descr", "leveltb", " WHERE schoolid='{$schoolID}' AND status='Active'  ORDER BY descr");
		echo '</select>'; //select2
	}

	if($requestID=="loadMode"){
		$schoolID=$cls->data($_REQUEST['schoolID']);
		echo '<label>Entry Mode *</label>
		<select class="form-control" id="entryMode" name="entryMode">';
			$cls->populate_select_withID("Null", "entrymode", "entrymodetb", " WHERE schoolid='{$schoolID}' AND status='Active'  ORDER BY entrymode");
		echo '</select>'; //select2
	}

	if($requestID=="loadProgramme"){
		$departmentID=$cls->data($_REQUEST['departmentID']);
		echo '<label>Course of Study *</label>
		<select class="form-control" id="programmeID" name="programmeID" onchange="sendRequest(\'loadLevel\', $(\'#schoolID\').val());">';
			$cls->populate_select_withID("programmeid", "programmename", "programmetb", " WHERE departmentid='{$departmentID}' AND status='Active'  ORDER BY programmename");
		echo '</select>'; //select2
	}

	if($requestID=="loadProgramme2"){
		$departmentID=$cls->data($_REQUEST['departmentID']);
		echo '<label>Course of Study *</label>
		<select class="form-control" id="programmeID2" name="programmeID2" onchange="sendRequest(\'loadLevel\', this.value);">';
			$cls->populate_select_withID("programmeid", "programmename", "programmetb", " WHERE departmentid='{$departmentID}' AND status='Active'  ORDER BY programmename");
		echo '</select>'; //select2
	}

	if($requestID=="loadALevelGrade"){
		$aLevelType=$cls->data($_REQUEST['aLevelID']);
		echo '<label>Grade *</label>
		<select class="form-control" name="aLevelGrade" id="aLevelGrade">';
			$cls->populate_select_withID("id", "grade", "alevelgradelist", " WHERE type='{$aLevelType}' AND status='Active'  ORDER BY grade_weight");
		echo '</select>'; //select2
	}

	if($requestID=="loadDepartment"){
		$facultyID=$cls->data($_REQUEST['facultyID']);
		echo '<label>Department *</label>
		<select class="form-control" id="departmentID" name="departmentID" onChange="sendRequest(\'loadProgramme\', this.value);">';
			$cls->populate_select_withID("id", "concat(departmentname, ' (', departmentid, ')') as dept", "departmenttb", " WHERE facultyid='{$facultyID}' AND status='Active'  ORDER BY departmentname");
		echo '</select>'; //select2
	}

	if($requestID=="loadUnit"){
		$deptID=$cls->data($_REQUEST['deptID']); ?>
		<select class="form-control" name="unit_code" id="unit_code">
                              <?php $cls->populate_select_withID("unit_code", "unit_name", "unittb", " WHERE dept_code='{$deptID}' ORDER BY unit_name ", $_SESSION['eData']['unit_code'] ); ?>
                    </select> <?php
	}

	if($requestID=="loadFaculty"){
		$schoolID=$cls->data($_REQUEST['schoolID']);
		echo '<label for="facultyID">School of *</label>
		<select class="form-control" id="facultyID" name="facultyID" onChange="sendRequest(\'loadDepartment\', $(\'#facultyID\').val());">';
			$cls->populate_select_withID("id", "facultyname", "facultytb", " WHERE schoolid='{$schoolID}' AND status='Active' AND category='Academic' ORDER BY facultyname");
		echo '</select>'; //select2
	}

	if($requestID=="loadState"){
		$countryName=$cls->data($_REQUEST['countryName']);
		if($countryName!="Nigeria") $countryName="Non-Nigerian"; ?>
		<label>State *</label>
			<select class="form-control" name="state" id="state" onchange="sendRequest('loadLGA',document.getElementById('state').value); ">

          <?php $cls->populate_select_withID("state_id", "state_name", "statetb", " WHERE countryname='{$countryName}' AND status='Active' ORDER BY state_name");
          echo "</select>";
	}

	if($requestID=="loadLGA"){
		$stateID=$cls->data($_REQUEST['stateID']); ?>
                    <select class="form-control" name="lga" id="lga">
                              <?php $cls->populate_select_withID("lga_id", "lga_name", "lgatb", " WHERE state_id='{$stateID}' ORDER BY lga_name ", $_SESSION['eData']['lga_id']); ?>
                    </select> <?php
	}

	if($requestID=="loadRank"){
		$category=$cls->data($_REQUEST['category']); ?>
		<select class="form-control" name="rank" id="rank">
                              <?php $cls->populate_select_withID("id", "rank", "hr_ranktb", " WHERE category='{$category}' ORDER BY rank ", $_SESSION['eData']['rank'] ); ?>
                    </select> <?php
	}
?>
