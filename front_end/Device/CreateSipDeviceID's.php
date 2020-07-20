<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Device Module</title>
    </head>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="bootstrap.min.css">
    <body>
        <?php
        include_once "../DBconnection.php";
        include_once '../Controller.php';
        if ($_SESSION['bool'] != 1) {
            header('Location:../ATSLogin.php');
            exit();
        }
        require_once '../PHPExcel-1.8.1/Classes/PHPExcel/IOFactory.php';
        $exceldata = array();
        $oldname = $_FILES['filename']['tmp_name'];
        $newname = substr($oldname, 0, strrpos($oldname, "/") + 1) . $_FILES['filename']['name'];
        rename($oldname, $newname);
        $inputfilename = $newname;
//        echo "Its Correct".$inputfilename;
//        $inputfilename= "/home/imca5/Desktop/Excel_Formats/OLD/Device_Module.xls";
        try {
            //$inputf ilename= PHPExcel_IOFactory::load($_FILES['excel']['tem_name']);
            $inputfiletype = PHPExcel_IOFactory::identify($inputfilename);  //checks whether file is excel or not
            $objReader = PHPExcel_IOFactory::createReader($inputfiletype);  //reads and assigns to objReader
            $objPHPExcel = $objReader->load($inputfilename);                //loads the read file to objPHPExcel
        } catch (Exception $e) {
            die('Error loading file"' . pathinfo($inputfilename, PATHINFO_BASENAME) . '": ' . $e->getMessage());
            //if anything is worng with excel it genarates the message.
        }
        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        //Loop through each row of the worksheet in turn
        for ($row = 2; $row <= $highestRow; $row++) { //data starts from 2nd row
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row);
            $sdid = $rowData[0][2];
            if (preg_match("/^[ ]*$/", $sdid) || strlen($sdid) <= 2 || strlen($sdid) >= 6 || preg_match("/^[a-zA-Z]*$/", $sdid)) {
//                print "<script>alert('Sip device id should be greater then two digits,less than six and also it should not be empty or contain alpabets!!!')</script>";
            } else {
                $open = "[";
                $close = "]\n";
                $sipdeviceid = $open . $sdid . $close;       //sipdeviceid ex:-[1001] and so on...
                echo "Sip device Id is " . $sipdeviceid . "<br\n>";
                $type = "type=friend\n";
                $context = "context=from-internal\n";
                $host = "host=dynamic\n";
                $sec = "secret=sairam\n";
                $allow = "allowguest=yes\n";
                $finaldata = $sipdeviceid . $type . $context . $host . $sec . $allow . "\n";
                $searchfor = $sdid;
                $oldname = $_FILES['filename1']['tmp_name'];
                $newname = substr($oldname, 0, strrpos($oldname, "/") + 1) . $_FILES['filename1']['name'];
                rename($oldname, $newname);
//              $inputfilename = "/tmp/" . $_FILES['filename']['name'];
                $file = $newname;
//                echo "File Name is ".$file;
//                $file = '/home/imca5/myfile.txt';
                $contents = file_get_contents($file);
                $pattern = preg_quote($searchfor, '/');
                $pattern = "/^.*$pattern.*\$/m";
                if (preg_match($pattern, $contents, $matches)) {
                    echo "Matches found can't send data to file.<br />";
                    echo implode("<br />", $matches[0]);
//                    $msg = urlencode("Matches found can't send data to file");
//                    header("Location:Device Allocation.php?msg=" . $msg."#sipDevice");
                } else {
                    echo "No matches found! Data succussfully written";
                    $handle = fopen($file, "a");
                    fwrite($handle, $finaldata);
                    fclose($file);
                    fclose($handle);
//                    $result = file_put_contents("$file", $finaldata, FILE_APPEND);
//                    if ($result === false) {
//                        die('There was an error writing this file');
//                    } else {
//                        echo "$result bytes written to file";
//                    }
//                    
                }
            }
        }
//        $msg = urlencode("No matches found! Data succussfully written");
//        header("Location:DeviceAllocation.php?msg=" . $msg . "#sipdevice");
        ?>
    </body>
</html>