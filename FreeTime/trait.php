<?php
if(isset($_POST['testID']))
{

    $uid = $_POST['testID'];
    $serializedData = serialize($uid); //where '$array' is your array
	$serializedJsonData = json_encode($uid); //where '$array' is your array
	file_put_contents('result_file.txt', $serializedData);
	file_put_contents('result_file_Json.txt', $serializedData);
	file_put_contents('result_file_NOrm.txt', $uid);

	/*
	//at a later point, you can convert it back to array like
	$recoveredData = file_get_contents('your_file_name.txt');
	$recoveredArray = unserialize($recoveredData);

	// you can print your array like
	print_r($recoveredArray);*/
	
}else{
file_put_contents('C:\Users\Ayoub\Desktop\gridster_sample\gridster_test\result_failed.txt', "failed to recover data");
}
?>