<?php
//-----------------------------------------------------------
if (isset($_GET['action'])){
	switch($_GET['action']){
		case "create":
			create_data();
			break;
		case "read":
			read_data();
			break;
		case "update":
			update_data();
			break;
		case "delete":
			delete_data();
			break;		
		default:			
			echo "<h3>action <i>".$_GET['action']."</i> tidak ada!</h3>";
			read_data();
	}
}else{
	read_data();
}
?>


<?php
//-----------------------------------------------------------
function create_data(){
//-----------------------------------------------------------
// Load file koneksi.php
include "koneksi.php";

if(isset($_POST['submit'])){

	// Ambil Data yang Dikirim dari Form
	$nis = $_POST['nis'];
	$nama = $_POST['nama'];
	$jenis_kelamin = $_POST['jenis_kelamin'];
	$telp = $_POST['telp'];
	$alamat = $_POST['alamat'];
	$foto = $_FILES['foto']['name'];
	$tmp = $_FILES['foto']['tmp_name'];

	// Rename nama fotonya dengan menambahkan tanggal dan jam upload
	$fotobaru = date('dmYHis').$foto;

	// Set path folder tempat menyimpan fotonya
	$path = "siswa/images/".$fotobaru;

	// Proses upload
	if(move_uploaded_file($tmp, $path)){ // Cek apakah gambar berhasil diupload atau tidak
		// Proses simpan ke Database
		$sql = $pdo->prepare("INSERT INTO siswa_foto(nis, nama, jenis_kelamin, telp, alamat, foto) VALUES(:nis,:nama,:jk,:telp,:alamat,:foto)");
		$sql->bindParam(':nis', $nis);
		$sql->bindParam(':nama', $nama);
		$sql->bindParam(':jk', $jenis_kelamin);
		$sql->bindParam(':telp', $telp);
		$sql->bindParam(':alamat', $alamat);
		$sql->bindParam(':foto', $fotobaru);
		$sql->execute(); // Eksekusi query insert

		if($sql){ // Cek jika proses simpan ke database sukses atau tidak
			// Jika Sukses, Lakukan :
			header("location: ?page=view_siswa&action=read"); // Redirect ke halaman index.php
		}else{
			// Jika Gagal, Lakukan :
			echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
			echo "<br><a href='?page=view_siswa&action=create'>Kembali Ke Form</a>";
		}
	}else{
		// Jika gambar gagal diupload, Lakukan :
		echo "Maaf, Gambar gagal untuk diupload.";
		echo "<br><a href='?page=view_siswa&action=create'>Kembali Ke Form</a>";
	}
	
} //end POST SUBMIT
?>
	<h1>Tambah Data Siswa</h1>
	
	<form method="post" enctype="multipart/form-data">
	<table cellpadding="8">
	<tr>
		<td>NIS</td>
		<td><input type="text" name="nis" required></td>
	</tr>
	<tr>
		<td>Nama</td>
		<td><input type="text" name="nama"  required></td>
	</tr>
	<tr>
		<td>Jenis Kelamin</td>
		<td>
		<input type="radio" name="jenis_kelamin" value="Laki-laki" required> Laki-laki
		<input type="radio" name="jenis_kelamin" value="Perempuan" required> Perempuan
		</td>
	</tr>
	<tr>
		<td>Telepon</td>
		<td><input type="text" name="telp"></td>
	</tr>
	<tr>
		<td>Alamat</td>
		<td><textarea name="alamat"></textarea></td>
	</tr>
	<tr>
		<td>Foto</td>
		<td><input type="file" name="foto" accept="image/*" required></td>
	</tr>
	</table>
	
	<hr>
	<input type="submit" name="submit" value="Simpan">
	<a href="?page=view_siswa&action=read"><input type="button" value="Batal"></a>
	</form>
<?php
} // end function create_data
?>



<?php
//-----------------------------------------------------------
function read_data(){
//-----------------------------------------------------------
?>

	<h1>Data Siswa | <a href="?page=view_siswa&action=create">Add New</a></h1>
	<table border="1" width="100%">
	<tr>
		<th>Foto</th>
		<th>NIS</th>
		<th>Nama</th>
		<th>Jenis Kelamin</th>
		<th>Telepon</th>
		<th>Alamat</th>
		<th colspan="2">Aksi</th>
	</tr>
	<?php
	// Load file koneksi.php
	include "koneksi.php";

	// Buat query untuk menampilkan semua data siswa
	$sql = $pdo->prepare("SELECT * FROM siswa_foto");
	$sql->execute(); // Eksekusi querynya

	while($data = $sql->fetch()){ // Ambil semua data dari hasil eksekusi $sql
		echo "<tr>";
		echo "<td><img src='siswa/images/".$data['foto']."' width='100' height='100'></td>";
		echo "<td>".$data['nis']."</td>";
		echo "<td>".$data['nama']."</td>";
		echo "<td>".$data['jenis_kelamin']."</td>";
		echo "<td>".$data['telp']."</td>";
		echo "<td>".$data['alamat']."</td>";
		echo "<td><a href='?page=view_siswa&action=update&id=".$data['id']."'>Ubah</a></td>";
		echo "<td><a onclick=\"return confirm('Are you sure to delete this data ?')\" href='?page=view_siswa&action=delete&id=".$data['id']."'>Hapus</a></td>";
		echo "</tr>";
	}
	?>
	</table>
	
<?php
} // end function read_data
?>




<?php
//-----------------------------------------------------------
function update_data(){
//-----------------------------------------------------------
// Load file koneksi.php
include "koneksi.php";

	// Ambil data NIS yang dikirim oleh index.php melalui URL
	$id = $_GET['id'];

	// Query untuk menampilkan data siswa berdasarkan ID yang dikirim
	$sql = $pdo->prepare("SELECT * FROM siswa_foto WHERE id=:id");
	$sql->bindParam(':id', $id);
	$sql->execute(); // Eksekusi query insert
	$data = $sql->fetch(); // Ambil semua data dari hasil eksekusi $sql


if(isset($_POST['submit'])){

	// Ambil data ID yang dikirim oleh form_ubah.php melalui URL
	$id = $_GET['id'];

	// Ambil Data yang Dikirim dari Form
	$nis = $_POST['nis'];
	$nama = $_POST['nama'];
	$jenis_kelamin = $_POST['jenis_kelamin'];
	$telp = $_POST['telp'];
	$alamat = $_POST['alamat'];

	// Ambil data foto yang dipilih dari form
	$foto = $_FILES['foto']['name'];
	$tmp = $_FILES['foto']['tmp_name'];

	// Cek apakah user ingin mengubah fotonya atau tidak
	if(empty($foto)){ // Jika user tidak memilih file foto pada form
		// Lakukan proses update tanpa mengubah fotonya
		// Proses ubah data ke Database
		$sql = $pdo->prepare("UPDATE siswa_foto SET nis=:nis, nama=:nama, jenis_kelamin=:jk, telp=:telp, alamat=:alamat WHERE id=:id");
		$sql->bindParam(':nis', $nis);
		$sql->bindParam(':nama', $nama);
		$sql->bindParam(':jk', $jenis_kelamin);
		$sql->bindParam(':telp', $telp);
		$sql->bindParam(':alamat', $alamat);
		$sql->bindParam(':id', $id);
		$execute = $sql->execute(); // Eksekusi / Jalankan query

		if($sql){ // Cek jika proses simpan ke database sukses atau tidak
			// Jika Sukses, Lakukan :
			header("location: ?page=view_siswa&action=read"); // Redirect ke halaman index.php
		}else{
			// Jika Gagal, Lakukan :
			echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
			echo "<br><a href='?page=view_siswa&action=create'>Kembali Ke Form</a>";
		}
	}else{ // Jika user memilih foto / mengisi input file foto pada form
		// Lakukan proses update termasuk mengganti foto sebelumnya
		// Rename nama fotonya dengan menambahkan tanggal dan jam upload
		$fotobaru = date('dmYHis').$foto;

		// Set path folder tempat menyimpan fotonya
		$path = "siswa/images/".$fotobaru;

		// Proses upload
		if(move_uploaded_file($tmp, $path)){ // Cek apakah gambar berhasil diupload atau tidak
			// Query untuk menampilkan data siswa berdasarkan ID yang dikirim
			$sql = $pdo->prepare("SELECT foto FROM siswa_foto WHERE id=:id");
			$sql->bindParam(':id', $id);
			$sql->execute(); // Eksekusi query insert
			$data = $sql->fetch(); // Ambil semua data dari hasil eksekusi $sql

			// Cek apakah file foto sebelumnya ada di folder images
			if(is_file("siswa/images/".$data['foto'])) // Jika foto ada
				unlink("siswa/images/".$data['foto']); // Hapus file foto sebelumnya yang ada di folder images

			// Proses ubah data ke Database
			$sql = $pdo->prepare("UPDATE siswa_foto SET nis=:nis, nama=:nama, jenis_kelamin=:jk, telp=:telp, alamat=:alamat, foto=:foto WHERE id=:id");
			$sql->bindParam(':nis', $nis);
			$sql->bindParam(':nama', $nama);
			$sql->bindParam(':jk', $jenis_kelamin);
			$sql->bindParam(':telp', $telp);
			$sql->bindParam(':alamat', $alamat);
			$sql->bindParam(':foto', $fotobaru);
			$sql->bindParam(':id', $id);
			$execute = $sql->execute(); // Eksekusi / Jalankan query

			if($sql){ // Cek jika proses simpan ke database sukses atau tidak
				// Jika Sukses, Lakukan :
				header("location: ?page=view_siswa&action=read"); // Redirect ke halaman index.php
			}else{
				// Jika Gagal, Lakukan :
				echo "Maaf, Terjadi kesalahan saat mencoba untuk menyimpan data ke database.";
				echo "<br><a href='?page=view_siswa&action=create'>Kembali Ke Form</a>";
			}
		}else{
			// Jika gambar gagal diupload, Lakukan :
			echo "Maaf, Gambar gagal untuk diupload.";
			echo "<br><a href='?page=view_siswa&action=create'>Kembali Ke Form</a>";
		}
	}
}
?>

	<h1>Ubah Data Siswa</h1>

	<form method="post" enctype="multipart/form-data">
		<table cellpadding="8">
			<tr>
				<td>NIS</td>
				<td><input type="text" name="nis" value="<?php echo $data['nis']; ?>"></td>
			</tr>
			<tr>
				<td>Nama</td>
				<td><input type="text" name="nama" value="<?php echo $data['nama']; ?>"></td>
			</tr>
			<tr>
				<td>Jenis Kelamin</td>
				<td>
				<?php
				if($data['jenis_kelamin'] == "Laki-laki"){
					echo "<input type='radio' name='jenis_kelamin' value='laki-laki' checked='checked'> Laki-laki";
					echo "<input type='radio' name='jenis_kelamin' value='perempuan'> Perempuan";
				}else{
					echo "<input type='radio' name='jenis_kelamin' value='laki-laki'> Laki-laki";
					echo "<input type='radio' name='jenis_kelamin' value='perempuan' checked='checked'> Perempuan";
				}
				?>
				</td>
			</tr>
			<tr>
				<td>Telepon</td>
				<td><input type="text" name="telp" value="<?php echo $data['telp']; ?>"></td>
			</tr>
			<tr>
				<td>Alamat</td>
				<td><textarea name="alamat"><?php echo $data['alamat']; ?></textarea></td>
			</tr>
			<tr>
				<td>Foto</td>
				<td>
					<img src="siswa/images/<?php echo $data['foto']; ?>" width="100">
					<input type="file" name="foto" accept="image/*">
				</td>
			</tr>
		</table>

		<hr>
		<input type="submit" name="submit" value="Ubah">
		<a href="?page=view_siswa&action=read"><input type="button" value="Batal"></a>
	</form>

<?php
} //end function update_data
?>





<?php
//-----------------------------------------------------------
function delete_data(){
//-----------------------------------------------------------
	// Load file koneksi.php
	include "koneksi.php";

	// Ambil data NIS yang dikirim oleh index.php melalui URL
	$id = $_GET['id'];

	// Query untuk menampilkan data siswa berdasarkan ID yang dikirim
	$sql = $pdo->prepare("SELECT foto FROM siswa_foto WHERE id=:id");
	$sql->bindParam(':id', $id);
	$sql->execute(); // Eksekusi query insert
	$data = $sql->fetch(); // Ambil semua data dari hasil eksekusi $sql

	// Cek apakah file fotonya ada di folder images
	if(is_file("siswa/images/".$data['foto'])) // Jika foto ada
		unlink("siswa/images/".$data['foto']); // Hapus foto yang telah diupload dari folder images

	// Query untuk menghapus data siswa berdasarkan ID yang dikirim
	$sql = $pdo->prepare("DELETE FROM siswa_foto WHERE id=:id");
	$sql->bindParam(':id', $id);
	$execute = $sql->execute(); // Eksekusi / Jalankan query

	if($execute){ // Cek jika proses simpan ke database sukses atau tidak
		// Jika Sukses, Lakukan :
		header("location: ?page=view_siswa&action=read"); // Redirect ke halaman index.php
	}else{
		// Jika Gagal, Lakukan :
		echo "Data gagal dihapus. <a href='?page=view_siswa&action=read'>Kembali</a>";
	}
	
} // end function delete_data
?>
