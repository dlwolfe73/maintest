<?php
//Testing it out

require 'dicom/nanodicom.php';
$dir = 'C:/wamp/www/chiro/documents/dicom/wrix/';
//$dir="C:/wamp/www/chiro/dicom/samples/"; //C:\wamp\www\chiro\dicom\samples\jpg
$can_change_test_for_svn = '1';
$jpg_dir = $dir . 'jpg/';

$web_dir = 'documents/dicom/wrix/jpg/';
//$web_dir = 'dicom/samples/jpg/';

 $dcm_array = array();
//print each file name
if ($handle = opendir($dir)) {
    while (false !== ($file = readdir($handle))) 
	{
        $ext = end(explode('.', $file));
		if ($file != "." && $file != ".." && is_file($dir.$file) && $ext == 'dcm') 
		{
			//echo $file;
			$dcm_array[] = $file;
		}
	}
    closedir($handle);
}
	
foreach ($dcm_array as $image)
{
	

	$filename = $dir.$image;
	try
	{

		$dicom = Nanodicom::factory($filename, 'simple');
		$dicom->parse(array('PatientName','PatientPosition', 'ImageType', 'StudyDescription' ));
		//echo $dicom->profiler_diff('parse')."\n";
		echo '<p>File Name: '.$image."</p>"; 
		echo '<p>Patient name if exists: '.$dicom->PatientName."</p>"; 
		echo '<p>Patient position if exists: '.$dicom->PatientPosition."</p>"; 
		echo '<p>Image Type if exists: '.$dicom->Imagetype."\n"; 
		echo '<p>Study Description if exists: '.$dicom->StudyDescription."</p>"; 
				

		//system("convert -delete 1-9999 -compress jpeg -quality 90 $filename $jpg_dir$image.jpg");
		//system("convert $filename $jpg_dir$image.jpg");
		$convert = "convert -quality 100 $filename $jpg_dir$image.jpg";
		system($convert);
		unset($dicom);
		echo "<p><img src='" . $web_dir . $image . ".jpg' /></p>";
		
	}
	catch (Nanodicom_Exception $e)
	{
		echo 'File failed. '.$e->getMessage()."\n";
	}
	

}