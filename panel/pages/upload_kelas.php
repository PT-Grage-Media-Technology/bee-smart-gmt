<?php
	if(!isset($_COOKIE['beeuser'])){
	header("Location: login.php");}

if(isset($_REQUEST['modul'])){
	if($_REQUEST['modul']=="upl_kelas"){
	$kata = "Data Kelas"; }
	elseif($_REQUEST['modul']=="upl_mapel"){
	$kata = "Data Mata Pelajaran"; }
	elseif($_REQUEST['modul']=="upl_siswa"){
	$kata = "Data Siswa"; }
}
?>
 <!-- /.row -->
            <div class="row">
                <div class="col-lg-10" style="margin-top:10px;">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                           Download File Excel (Template Data Kelas)
                        </div>
                        <div class="panel-body">
<div style="width: 20%; float:left">
   <a href="../../file-excel/bee_kelas_temp.xls" target="_blank"><img src="images/xls.png" style=" width:90%; max-width:100px;padding-right:10px;"/></a>
</div>

<div style="width: 80%; float:right">
   Silahkan Klik logo Excel disamping, untuk <b>download </b> file excel database Kelas. 
   <br><span style="color: #ff0000;">Jangan ada inputan apapun setelah nomer terakhir</span>  Karena akan dibaca dan diacak oleh sistem. <p>Setelah selesai edit, Upload kembali untuk ditransfer ke
   database melalui tool dibawah ini. 
   
</div>
                        </div>
                        <div class="panel-footer">
       <a href="../../file-excel/bee_kelas_temp.xls" target="_blank"><button class="btn btn-success btn-lg btn-small" id="baru" value="Buat" name="baru"><i class="fa fa-cloud-download"></i>
                            Download Tempalte</button></a>
        
        <a href="?modul=daftar_kelas"><button class="btn btn-success btn-lg btn-small" id="baru" value="Buat" name="baru"><i class="fa fa-list"></i>
                            Lihat Data Kelas</button></a>
                        </div>
                    </div>
                    <!-- /.col-lg-4 -->
                </div>
            </div>
            <!-- /.row -->
            
            
              <div class="row">
                <div class="col-lg-10" style="margin-top:10px;">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Upload Template Excel - Data Kelas 
                           
                        </div>
                        <div class="panel-body">
						<form method="post" enctype="multipart/form-data" action="<?php echo "?modul=uploadkelas"; ?>">
                        File Excel Daftar Kelas : 
                        <table border="0" width="78%" cellpadding="20px" cellspacing="20px"><tr><td width="30%"><input name="userfile" type="file" class="btn btn-default" style="width:250px"></td><td>
                        &nbsp;<input name="upload" type="submit" value="Import"  class="btn btn-info" style="margin-top:0px">
                        </td></tr></table>
                        </form>
                        <div style="margin-top:10px;">Persentase Proses Upload <? echo $kata; ?> </div>
<!-- Progress bar holder -->
<div id="progress" style="width:75%; border:1px solid #ccc; padding:5px; margin-top:10px; height:33px"></div>
<!-- Progress information -->
<div id="information" style="width"></div>

<?php

if($_REQUEST['modul']=="uploadkelas"){
// menggunakan class phpExcelReader
include "excel_reader2.php";

// membaca file excel yang diupload
$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);

// membaca jumlah baris dari data excel
$baris = $data->rowcount($sheet_index=0);

// nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
$sukses = 0;
$gagal = 0;
$query0 = "TRUNCATE TABLE cbt_kelas";
		  $hasil0 = mysql_query($query0);

// import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
for ($i=2; $i<=$baris; $i++)
{
  // membaca data soalid (kolom ke-1 FIELD)
//  $fieldz = $data->val($i, 1);
  // membaca data pertanyaan (kolom ke-2 R)
  $x1 = $data->val($i, 1);
  $x2 = $data->val($i, 2);
  $x3 = $data->val($i, 3);
  $x4 = $data->val($i, 4);
  $x5 = $data->val($i, 5);  
// $xlevel = str_replace("'","\'",$xlevel);
 //$xkelas = str_replace("'","\'",$xkelas);
 
 $xlevel = mysql_real_escape_string($x2);
 $xkelas = mysql_real_escape_string($x4);
 
 if(!$x1==""){
		  // setelah data dibaca, sisipkan ke dalam tabel cbt_kelas
		  $query = "INSERT INTO cbt_kelas ( XKodeKelas, XKodeLevel, XNamaKelas, XKodeJurusan, XStatusKelas, XKodeSekolah) VALUES ('$x1','$x2', '$x3', '$x4','1', '$x5')";
		  $hasil = mysql_query($query);
  if ($hasil) $sukses++;
 } else {
 $gagal++;
 } 
			// Calculate the percentation
			$percent = intval($i/$baris * 100)."%";
    
    // Javascript for updating the progress bar and information
    echo '<script language="javascript">
    document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-image:url(images/pbar-ani1.gif);\">&nbsp;</div>";
    document.getElementById("information").innerHTML="  Proses Entri : '.$xkelas.' ... <b>'.$i.'</b> row(s) of <b>'. $baris.'</b> processed.";
    </script>';
// This is for the buffer achieve the minimum size in order to flush data
    echo str_repeat(' ',1024*64);
    

// Send output to browser immediately
    flush();
// Tell user that the process is completed
   echo '<script language="javascript">document.getElementById("information").innerHTML=" Proses update database Kelas : Completed"</script>';
  
  }
  // jika proses insert data sukses, maka counter $sukses bertambah
  // jika gagal, maka counter $gagal yang bertambah


// tampilan status sukses dan gagal
?>
<div style="width:75%; margin-top:10px">
    <div class="alert alert-success">
    <?php
    echo "<p>Jumlah data yang sukses diimport : ".$sukses."<br>";
    ?>
    </div>
    
    <?php
        if($gagal>0){
        ?>
        <div class="alert alert-danger">
        <?php
        echo "Jumlah data yang gagal diimport : ".$gagal."</p>";
        ?></div>
        <?php
        }
    }
    ?>
	</div>
 
</div>

                    </div>
                    <!-- /.col-lg-4 -->
                </div>

            </div>
            <!-- /.row -->
            

            

<script src="../../mesin/js/jquery.js"></script>
